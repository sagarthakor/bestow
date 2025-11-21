<?php

namespace App\Library\Api;

use App\Models\CourierPickupLocation;
use App\Models\OrderInvoice;
use App\Models\ShipRocketApiLog;
use App\Models\StoreManager\StoreSupply;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Collection;

class ShipRocket
{
    private $token;

    function __construct()
    {
        $login_details = json_decode(\File::get(public_path('data/ship-rocket-login.json')));

        if (Carbon::parse($login_details->created_at)->diffInHours(Carbon::now()) > 12) {

            try {

                $response = (new Client())->request('POST', env('SHIP_ROCKET_BASE_URL') . 'auth/login', [
                    'form_params' => [
                        'email' => env('SHIP_ROCKET_USERNAME'), 'password' => env('SHIP_ROCKET_PASSWORD')
                    ]
                ]);

                $response = json_decode($response->getBody());

                file_put_contents(public_path('data/ship-rocket-login.json'), json_encode([
                    'email' => $response->email, 'company_id' => $response->company_id, 'created_at' => Carbon::now()->toDateTimeString(), 'token' => $response->token
                ], JSON_PRETTY_PRINT));

                $this->token = $response->token;

            } catch (RequestException $e) {

                $this->token = false;

            }

        }
        else {

            $this->token = $login_details->token;
        }

    }

    /**
     * Create Pickup Locations
     * @param  CourierPickupLocation $pickupLocation,
     * @return bool|mixed|\Psr\Http\Message\ResponseInterface
     */
    public function generatePickUpLocation(CourierPickupLocation $pickupLocation)
    {

        try {

            $response = (new Client())->request('POST', env('SHIP_ROCKET_BASE_URL') . 'settings/company/addpickup',  [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token
                ],
                'form_params' => [
                    'pickup_location' => $pickupLocation->name,
                    'name' => $pickupLocation->owner,
                    'email' => $pickupLocation->email,
                    'phone' => $pickupLocation->phone,
                    'address' => $pickupLocation->address,
                    'address_2' => $pickupLocation->landmark,
                    'city' => $pickupLocation->city,
                    'state' => $pickupLocation->state->name,
                    'country' => 'India',
                    'pin_code' => $pickupLocation->pincode
                ]
            ]);

            $response = json_decode($response->getBody());

            return (object) [
                'status' => true,
                'response' => $response
            ];

        } catch (RequestException $e) {

            $response = json_decode($e->getResponse()->getBody());

            \Log::warning('createShipLocation Exception', [
                $response->errors
            ]);

            return (object) [
                'status' => false,
                'message' => $response->message,
                'errors' => $response->errors
            ];

        }
    }

    public function createOrder(OrderInvoice $orderInvoice, $pickup_location)
    {
        if (isset($orderInvoice->order->shipping_address->mobile)) {
            $mobile = $orderInvoice->order->shipping_address->mobile;
        }
        else {
            $mobile = $orderInvoice->user ? $orderInvoice->user->mobile : $orderInvoice->customer->mobile;
        }

        $email = $orderInvoice->user ? $orderInvoice->user->email : $orderInvoice->customer->email;

        $total_weight = collect($orderInvoice->items)->sum(function ($item) {
            return $item->product_price->weight * $item->qty;
        });

        $requestData = [
            'order_id' => $orderInvoice->invoice_number,
            'order_date' => $orderInvoice->order->created_at->toDateString(),
            'pickup_location' => $pickup_location,
            'billing_customer_name' => $orderInvoice->order->shipping_address->name,
            'billing_last_name' => ' ',
            'billing_address' => $orderInvoice->order->shipping_address->address,
            'billing_address_2' => $orderInvoice->order->shipping_address->landmark,
            'billing_city' => $orderInvoice->order->shipping_address->city,
            'billing_pincode' => $orderInvoice->order->shipping_address->pincode,
            'billing_state' => $orderInvoice->order->shipping_address->state->name,
            'billing_country' => 'India',
            'billing_email' => $email ?: 'logistics.winzera@gmail.com',
            'billing_phone' => $mobile,
            'shipping_is_billing' => true,
            'order_items' => collect($orderInvoice->items)->groupBy('product_price_id')->values()->map(function ($order_details) {

                $order_detail = collect($order_details)->first();

                return [
                    'name' => trim($order_detail->product_price->product->name),
                    'sku' => $order_detail->product_price->code,
                    'units' => collect($order_details)->sum('qty'),
                    'selling_price' => $order_detail->selling_price,
                    'hsn' => $order_detail->product_price->gst->code,
                    'tax' => $order_detail->product_price->gst->percentage
                ];
            })->toArray(),
            'payment_method' => 'Prepaid',
            'sub_total' => $orderInvoice->amount,
            'length' =>  15, // cms
            'breadth' => 15, // cms
            'height' =>  10, // cms
            'weight' =>  round(($total_weight) * 0.001, 2) // Weight in Kg
        ];

        try {

            $response = (new Client())->request('POST', env('SHIP_ROCKET_BASE_URL') . 'orders/create/adhoc',  [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token
                ],
                'form_params' => $requestData
            ]);

            $response = json_decode($response->getBody());

            if (!in_array($response->status_code, [35,200,201,1])) {

                \Log::error('ShipRocket Order Create Status Code Error', [
                    'response' => $response,
                    'invoice_number' => $orderInvoice->invoice_number
                ]);

                ShipRocketApiLog::create([
                    'order_id' => $orderInvoice->order_id,
                    'order_invoice_id' => $orderInvoice->id,
                    'cmd' => 'orderCreate',
                    'request' => $requestData, 'response' => $response, 'status' => ShipRocketApiLog::FAILED
                ]);

                return (object) ['status' => false, 'message' => 'Not Able to create order at Shipping Partner'];
            }

            return (object) [
                'status' => true,
                'order_id' => $response->order_id,
                'shipment_id' => $response->shipment_id
            ];


        } catch (RequestException $e) {

            \Log::error('ShipRocket Create Order Exception', [
                'exception' => $e->getMessage(),
                'invoice_number' => $orderInvoice->invoice_number
            ]);

            ShipRocketApiLog::create([
                'order_id' => $orderInvoice->order_id,
                'order_invoice_id' => $orderInvoice->id,
                'cmd' => 'orderCreate',
                'request' => $requestData,
                'response' => [
                    'exception' => $e->getMessage(),
                    'invoice_number' => $orderInvoice->invoice_number
                ],
                'status' => ShipRocketApiLog::EXCEPTION
            ]);

            return (object) [
                'status' => false,
                'message' => 'Not Able to create order at Shipping Partner, Exception'
            ];

        }

    }


    /* Store Supply Order */
    public function createSupplyOrder(StoreSupply $supply, $pickup_location)
    {
        $total_weight = collect($supply->details)->sum(function ($item) {
            return $item->product_price->weight * $item->qty;
        });

        $requestData = [
            'order_id' => $supply->supply_number,
            'order_date' => $supply->created_at->toDateString(),
            'pickup_location' => $pickup_location,
            'billing_customer_name' => $supply->store->name,
            'billing_last_name' => ' ',
            'billing_address' => $supply->store->address,
            'billing_address_2' => '',
            'billing_city' => $supply->store->city,
            'billing_pincode' => $supply->store->pincode,
            'billing_state' => $supply->store->state->name,
            'billing_country' => 'India',
            'billing_email' => $supply->store->email ?: 'logistics.winzera@gmail.com',
            'billing_phone' => $supply->store->mobile,
            'shipping_is_billing' => true,
            'order_items' => collect($supply->details)->groupBy('product_price_id')->values()->map(function ($supply_details) {

                $supply_detail = collect($supply_details)->first();

                return [
                    'name' => trim($supply_detail->product_price->product->name),
                    'sku' => $supply_detail->product_price->code,
                    'units' => collect($supply_details)->sum('qty'),
                    'selling_price' => $supply_detail->supply_price,
                    'hsn' => $supply_detail->product_price->gst->code,
                    'tax' => $supply_detail->product_price->gst->percentage
                ];
            })->toArray(),
            'payment_method' => 'Prepaid',
            'sub_total' => $supply->total,
            'length' =>  15, // cms
            'breadth' => 15, // cms
            'height' =>  10, // cms
            'weight' =>  round(($total_weight) * 0.001, 2) // Weight in Kg
        ];

        try {

            $response = (new Client())->request('POST', env('SHIP_ROCKET_BASE_URL') . 'orders/create/adhoc',  [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token
                ],
                'form_params' => $requestData
            ]);

            $response = json_decode($response->getBody());

            if (!in_array($response->status_code, [35,200,201,1])) {

                \Log::error('ShipRocket Supply Order Create Status Code Error', [
                    'response' => $response,
                    'supply_number' => $supply->supply_number
                ]);

                ShipRocketApiLog::create([
                    'supply_id' => $supply->id,
                    'cmd' => 'orderCreate',
                    'request' => $requestData, 'response' => $response, 'status' => ShipRocketApiLog::FAILED
                ]);

                return (object) ['status' => false, 'message' => 'Not Able to create order at Shipping Partner'];
            }

            return (object) [
                'status' => true,
                'order_id' => $response->order_id,
                'shipment_id' => $response->shipment_id
            ];


        } catch (RequestException $e) {

            \Log::error('ShipRocket Create Order Exception', [
                'exception' => $e->getMessage(),
                'supply_number' => $supply->supply_number
            ]);

            ShipRocketApiLog::create([
                'supply_id' => $supply->id,
                'cmd' => 'orderCreate',
                'request' => $requestData,
                'response' => [
                    'exception' => $e->getMessage(),
                    'supply_number' => $supply->supply_number
                ],
                'status' => ShipRocketApiLog::EXCEPTION
            ]);

            return (object) [
                'status' => false,
                'message' => 'Not Able to create order at Shipping Partner, Exception'
            ];

        }

    }

    /**
     * @param $shipment_id
     * @param $courier_id
     * @return bool|mixed|\Psr\Http\Message\ResponseInterface
     */
    public function generateAwb($shipment_id, $courier_id)
    {
        try {

            $response = (new Client())->request('POST', env('SHIP_ROCKET_BASE_URL') . 'courier/assign/awb',  [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token
                ],
                'form_params' => [
                    'shipment_id' => $shipment_id,
                    'courier_id' => $courier_id,
                ]
            ]);

            $response = json_decode($response->getBody());

            if (isset($response->awb_assign_status) && $response->awb_assign_status != 1) {
                return (object) ['status' => false, 'message' => 'Not Able to create AWB, Contact Support Team'];
            }

            /* Generate Manifest */
            $manifest = $this->generateManifest($shipment_id);

            return (object) [
                'status' => true,
                'awb_code' => $response->response->data->awb_code,
                'routing_code' => $response->response->data->routing_code,
                'is_manifest_generated' => $manifest,
            ];

        } catch (RequestException $e) {

            \Log::error('ShipRocket Create AWB Exception', [
                'exception' => $e->getMessage(), 'shipment_id' => $shipment_id
            ]);

            return (object) [
                'status' => false,
                'exception' => $e->getMessage(),
                'message' => 'Not Able to create AWB, Exception Thrown'
            ];

        }
    }

    /**
     * Generate Manifest
     * @param mixed $shipment_id
     * @return bool|mixed|\Psr\Http\Message\ResponseInterface
     */
    public function generateManifest($shipment_id)
    {
        try {

            $response = (new Client())->request('POST', env('SHIP_ROCKET_BASE_URL') . 'manifests/generate',  [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token
                ],
                'form_params' => [
                    'shipment_id' => [$shipment_id],
                ]
            ]);

            $response = json_decode($response->getBody());

            if (isset($response->status) && $response->status == 1) {
                return true;
            }

            \Log::error('ShipRocket Generate Manifest Error', [
                'response' => $response, 'shipment_id' => $shipment_id
            ]);

            return false;

        } catch (RequestException $e) {

            \Log::error('ShipRocket Generate Manifest Exception', [
                'response' => $e->getMessage(), 'shipment_id' => $shipment_id
            ]);

            return false;

        }
    }

    public function getManifest($Id)
    {
        try {

            $response = (new Client())->request('POST', env('SHIP_ROCKET_BASE_URL') . 'manifests/print',  [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token
                ],
                'form_params' => [
                    'order_ids' => [$Id],
                ]
            ]);

            $response = json_decode($response->getBody());


            if (isset($response)) {
                return $response;
            }

            \Log::error('ShipRocket Pickup Manifest Error', [
                'response' => $response, 'order_ids' => $Id
            ]);

            return false;

        } catch (RequestException $e) {

            \Log::error('ShipRocket generate Manifest Exception', [
                'exception' => $e->getMessage(), 'order_ids' => $Id
            ]);

            return false;

        }
    }

    /**
     * Schedule Pickup
     * @param mixed $shipment_ids
     * @return bool|mixed|\Psr\Http\Message\ResponseInterface
     */
    public function pickupSchedule($shipment_ids)
    {

        try {

            $response = (new Client())->request('POST', env('SHIP_ROCKET_BASE_URL') . 'courier/generate/pickup',  [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token
                ],
                'form_params' => [
                    'shipment_id' => [$shipment_ids],
                ]
            ]);

            $response = json_decode($response->getBody());

            if (isset($response->pickup_status) && $response->pickup_status == 1) {

                return (object) [
                    'status' => true,
                    'pickup' => (object) [
                        'token_number' => $response->response->pickup_token_number,
                        'scheduled_date' => $response->response->pickup_scheduled_date
                    ]
                ];
            }

            \Log::error('ShipRocket Pickup Schedule Error', [
                'response' => $response, 'shipment_ids' => $shipment_ids
            ]);

            return false;

        } catch (RequestException $e) {

            \Log::error('ShipRocket Pickup Schedule Exception', [
                'exception' => $e->getMessage(), 'shipment_ids' => $shipment_ids
            ]);

            return false;

        }
    }


    /**
     * Generate Shipping Label
     * @param  mixed $shipment_id
     * @return bool|mixed|\Psr\Http\Message\ResponseInterface
     */
    public function shippingLabel($shipment_id)
    {
        try {

            $response = (new Client())->request('POST', env('SHIP_ROCKET_BASE_URL') . 'courier/generate/label',  [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token
                ],
                'form_params' => [
                    'shipment_id' => [$shipment_id],
                ]
            ]);

            $response = json_decode($response->getBody());


            if ($response->label_created == 1) {

                return (object) [
                    'status' => true,
                    'url' => $response->label_url
                ];
            }

            return (object) [
                'status' => false,
                'message' => 'Not able to generate Print Label, Try after Some time'
            ];

        } catch (RequestException $e) {

            return (object) [
                'status' => false,
                'message' => 'Not able to generate Print Label, Try after Some time. Exception'
            ];

        }
    }

    /**
     * Generate Shipping Label
     * @param  array $order_ids
     * @return bool|mixed|\Psr\Http\Message\ResponseInterface
     */
    public function invoice($order_ids)
    {

        try {

            $response = (new Client())->request('POST', env('SHIP_ROCKET_BASE_URL') . 'orders/print/invoice',  [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token
                ],
                'form_params' => [
                    'ids' => [$order_ids],
                ]
            ]);

            $response = json_decode($response->getBody());



            if (isset($response->is_invoice_created) && $response->is_invoice_created) {

                return (object) [
                    'status' => true,
                    'url' => $response->invoice_url
                ];
            }

            return (object) [
                'status' => false,
                'message' => 'Not able to generate Invoice, Try after Some time'
            ];

        } catch (RequestException $e) {

            return (object) [
                'status' => false,
                'message' => 'Not able to generate Invoice, Try after Some time. Exception'
            ];

        }
    }

    /**
     * Generate Shipping Label
     * @param  array $order_id
     * @return bool|mixed|\Psr\Http\Message\ResponseInterface
     */
    public function printManifest($order_id)
    {
        try {

            $response = (new Client())->request('POST', env('SHIP_ROCKET_BASE_URL') . 'manifests/print',  [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token
                ],
                'form_params' => [
                    'order_ids' => [$order_id],
                ]
            ]);

            $response = json_decode($response->getBody());

            return (object) [
                'status' => true,
                'url' => $response->manifest_url
            ];

        } catch (RequestException $e) {

            return (object) [
                'status' => false,
                'message' => 'Not able to generate Manifest, Try after Some time. Exception'
            ];

        }
    }

    /**
     * @param array $awb_numbers
     * @return object
     */
    public function multipleAwbTracking($awb_numbers)
    {
        try {

            $response = (new Client())->request('POST', env('SHIP_ROCKET_BASE_URL') . 'courier/track/awbs',  [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token
                ],
                'form_params' => [
                    'awbs' => $awb_numbers,
                ]
            ]);

            $response = json_decode($response->getBody());

            dd($response);

        } catch (RequestException $e) {

            dd($e);

        }
    }

    /**
     * Check Courier Serviceability
     * @param  Collection $details
     * @return bool|mixed|\Psr\Http\Message\ResponseInterface
     */
    public function domesticServiceAvailable($details)
    {
        try {

            $response = (new Client())->request('GET', env('SHIP_ROCKET_BASE_URL') . 'courier/serviceability/',  [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token
                ],
                'query' => [
                    'pickup_postcode' => $details->get('pickup_postcode'),
                    'delivery_postcode' => $details->get('delivery_postcode'),
                    'cod' => 0,
                    'weight' => $details->get('weight') ? $details->get('weight') : 1,
                ]
            ]);

            $response = json_decode($response->getBody());

            if (isset($response->status) && $response->status == '404') {
                return (object) ['status' => false, 'message' => $response->message];
            }

            if (isset($response->status_code) && !in_array($response->status_code, [200,201,1]) || isset($response->status) && !in_array($response->status, [200,201,1])) {
                return (object) ['status' => false, 'message' => 'Not Able to check Domestic Service Availability'];
            }

            $selected_courier_company = collect($response->data->available_courier_companies)->reject(function ($courier_company) {
                return in_array($courier_company->courier_company_id, [32, 29, 4]);
            })->sortBy('rate')->first();

            return (object) [
                'status' => true,
                'message' => 'Courier Service available in your location: ' . $details->get('delivery_postcode'),
                'selected_courier_company' => $selected_courier_company,
                'available_courier_companies' => $response->data->available_courier_companies
            ];

        } catch (RequestException $e) {

            return (object) [
                'status' => false,
                'message' => 'Domestic Service Check Failed, Exception'
            ];

        }
    }

    /**
     * Check  International Courier Serviceability
     * @param  Collection $details
     * @return bool|mixed|\Psr\Http\Message\ResponseInterface
     */
    public function internationalServiceAvailable($details)
    {
        try {

            $response = (new Client())->request('GET', env('SHIP_ROCKET_BASE_URL') . 'courier/international/serviceability',  [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token
                ],
                'query' => [
                    'weight' => $details->get('weight'),
                    'cod' => 0,
                    'delivery_country' => $details->get('country_code'),
                ]
            ]);

            $response = json_decode($response->getBody());

            return $response;

        } catch (RequestException $e) {

            return false;

        }
    }

    /**
     * @param string $awb_numbers
     * @return object
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function awbTracking(string $awb_numbers)
    {
        try {

            $response = (new Client())->request('GET', env('SHIP_ROCKET_BASE_URL') . 'courier/track/awb/' . $awb_numbers,  [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token
                ]
            ]);

            $response = json_decode($response->getBody());

            if (!isset($response->tracking_data))
                return (object) ['status' => false, 'message' => 'No Shipping Record Available'];

            if ($response->tracking_data->track_status == 0) {
                return (object) ['status' => false, 'message' => 'No Shipping Record Available'];
            }

            return (object) [
                'status' => true, 'response' => $response->tracking_data
            ];

        } catch (RequestException $e) {

            \Log::info('Track Shipping Exception', [$e->getMessage()]);

            return (object) ['status' => false, 'message' => 'No Shipping Record Available'];

        }
    }

    public function getOrderDetails($shiprocket_order_id)
    {
        try {

            $response = (new Client())->request('GET', env('SHIP_ROCKET_BASE_URL') . 'orders/show/' . $shiprocket_order_id,  [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token
                ]
            ]);

            $response = json_decode($response->getBody());

            return $response;

        } catch (RequestException $e) {

            echo $e->getMessage() . PHP_EOL;

            return false;

        }
    }
}