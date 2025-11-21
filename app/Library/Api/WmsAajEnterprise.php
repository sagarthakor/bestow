<?php


namespace App\Library\Api;


use App\Library\Helper;
use App\Models\OrderInvoice;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\StoreManager\StoreStockRequest;
use App\Models\StoreManager\StoreSupply;
use App\Models\WmsApiLog;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class WmsAajEnterprise
{
    private $provider = 'AAJ ENTERPRISE';

    /**
     * Manage Products: Create or Update Products for $provider
     * @param Product $product
     * @return object
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function manageProduct(Product $product)
    {
//        Product::get()->map(function ($product) { return WmsAajEnterprise::manageProduct($product); });

        $product_price = $product->prices->first();

        $response = (new Client())->request('POST', env('WMS_BASE_URL') . 'ManageProduct', [
            'json' => [
                "ClientCode" => env('WMS_CLIENT_CODE'),
                "CreatedBy" => 'ADMIN',
                "Currency" => "INR",
                "ProductName" => $product->name,
                "Description" => $product->description,
                "FinancialYear" => str_replace('-', '_', Helper::getFinancialYear()),
                "HsnCode" => $product_price->gst->code,
                "ProductCode" => $product_price->code,
                "ProductGroup" => $product->category->name,
                "ReleaseDate" => $product_price->created_at->toDateString(),
                "SellingPrice" => $product_price->distributor_price,
                "CostPrice" => $product_price->distributor_price,
                "CoverPrice" => $product_price->distributor_price,
                "Weight" => $product_price->weight,
                "Height" => $product_price->dimension->height,
                "Length" => $product_price->dimension->length,
                "Width" => $product_price->dimension->width
            ]
        ]);

        $response = json_decode($response->getBody());

        if ($response->Success) {
            return (object)['status' => true, 'message' => $response->Remarks];
        } else {
            return (object)['status' => false, 'message' => $response->Remarks];
        }
    }


    /**
     * @param ProductPrice $productPrice
     * @return object
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getItemBalance(ProductPrice $productPrice)
    {
        try {

            $response = (new Client())->request('POST', env('WMS_BASE_URL') . 'GetProductInventory', [
                'json' => [
                    'FinancialYear' => self::getFinancialYear(),
                    'ClientCode' => env('WMS_CLIENT_CODE'),
                    'ProductCode' => $productPrice->code
                ]
            ]);

            $response = json_decode($response->getBody());

            $response = collect($response)->first();

            return (object)[
                'status' => true,
                'qty' => $response->Quantity,
                'product_name' => $response->ProductName
            ];

        } catch (RequestException $e) {
            return (object)['status' => false, 'message' => 'Exception:' . $e->getMessage()];
        }
    }


    /**
     * @param OrderInvoice $orderInvoice
     * @return object
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createOrder(OrderInvoice $orderInvoice)
    {
        if (isset($orderInvoice->order->shipping_address->mobile)) {
            $mobile = $orderInvoice->order->shipping_address->mobile;
        }
        else {
            $mobile = $orderInvoice->user ? $orderInvoice->user->mobile : $orderInvoice->customer->mobile;
        }

        if (isset($orderInvoice->order->shipping_address->email)) {
            $customer_email = $orderInvoice->order->shipping_address->email;
        }
        else {
            $customer_email = $orderInvoice->user ? $orderInvoice->user->email : $orderInvoice->customer->email;
        }

        $customer_name = $orderInvoice->user ? $orderInvoice->user->detail->full_name : $orderInvoice->customer->name;
        $customer_code = $orderInvoice->user ? $orderInvoice->user->tracking_id : $orderInvoice->customer->mobile;

        $items = collect($orderInvoice->items)->groupBy('product_price_id')->map(function ($items, $index) {

            $item = $items->first();

            return [
                'batchnumber' => '',
                'productcode' => $item->product_price->code,
                'productdiscount_percent' => 0,
                'quantity' => collect($items)->sum('qty'),
                'rate' => $item->selling_price,
                'srno' => ($index + 1)
            ];
        })->values()->toArray();
        
        $requestData = [
            'financialyear' => self::getFinancialYear(),
            'vouchers' => [
                'saleorder' => [
                    'clientcode' => env('WMS_CLIENT_CODE'),
                    'facilitycode' => env('WMS_FACILITY_CODE'),
                    'contactno' => $mobile,
                    'billing_customer_email' => $customer_email,
                    'shipping_customer_email' => $customer_email,
                    'customer_referenceno' => $orderInvoice->invoice_number,
                    'customercode' => $customer_code,
                    'customername' => $customer_name,
                    'billing_address1' => $orderInvoice->order->shipping_address->address,
                    'billing_address2' => $orderInvoice->order->shipping_address->landmark,
                    'billing_address3' => '',
                    'billing_address4' => '',
                    'shipping_address1' => $orderInvoice->order->shipping_address->address,
                    'shipping_address2' => $orderInvoice->order->shipping_address->landmark,
                    'shipping_address3' => '',
                    'shipping_address4' => '',
                    'city' => $orderInvoice->order->shipping_address->city,
                    'pincode' => $orderInvoice->order->shipping_address->pincode,
                    'state' => $orderInvoice->order->shipping_address->state->name,
                    'client_orderno' => $orderInvoice->invoice_number,
                    'orderamount' => $orderInvoice->amount,
                    'orderdiscount_percent' => 0,
                    'orderquantity' => $orderInvoice->items->sum('qty'),
                    'ordertype' => 'Regular',
                    'products' => [
                        'product' => $items
                    ],
                    'remarks' => 'Order from Winzera ' . $orderInvoice->order->customer_order_id,
                    'user' => $customer_name,
                    'transport' => 'Shiprocket',
                    'transportmode' => 'Road'
                ]
            ]
        ];

        $apiLog = WmsApiLog::create([
            'provider' => $this->provider,
            'order_id' => $orderInvoice->order_id,
            'order_invoice_id' => $orderInvoice->id,
            'cmd' => 'CreateOrder',
            'request' => $requestData
        ]);

        $response = (new Client())->request('POST', env('WMS_BASE_URL') . 'CreateOrder', [
            'json' => $requestData
        ]);

        $response = json_decode($response->getBody());

        $apiLog->response = $response;
        $apiLog->status = $response->Success ? WmsApiLog::SUCCESS : WmsApiLog::FAILED;
        $apiLog->save();

        if ($response->Success) {
            return (object)['status' => true, 'message' => $response->Remarks];
        } else {
            return (object)['status' => false, 'message' => $response->Remarks];
        }
    }

    /**
     * @param StoreSupply $storeSupply
     * @return object
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createStoreSupply(StoreSupply $storeSupply)
    {
        $mobile = $storeSupply->store->mobile;
        $customer_name = $storeSupply->store->name;
        $customer_email = $storeSupply->store->email;
        $customer_code = $storeSupply->store->tracking_id;

        $supply_number = $storeSupply->supply_number;

        $items = collect($storeSupply->details)->map(function ($item, $index) {
            return [
                'batchnumber' => '',
                'productcode' => $item->product_price->code,
                'productdiscount_percent' => 0,
                'quantity' => $item->qty,
                'rate' => $item->supply_price,
                'srno' => ($index + 1)
            ];
        })->toArray();

        $requestData = [
            'financialyear' => self::getFinancialYear(),
            'vouchers' => [
                'saleorder' => [
                    'clientcode' => env('WMS_CLIENT_CODE'),
                    'facilitycode' => env('WMS_FACILITY_CODE'),
                    'contactno' => $mobile,
                    'customer_referenceno' => $supply_number,
                    'customercode' => $customer_code,
                    'customername' => $customer_name,
                    'billing_customer_email' => $customer_email,
                    'shipping_customer_email' => $customer_email,
                    'billing_address1' => $storeSupply->store->address,
                    'billing_address2' => '',
                    'billing_address3' => '',
                    'billing_address4' => '',
                    'shipping_address1' => $storeSupply->store->address,
                    'shipping_address2' => '',
                    'shipping_address3' => '',
                    'shipping_address4' => '',
                    'city' => $storeSupply->store->city,
                    'pincode' => $storeSupply->store->pincode,
                    'state' => $storeSupply->store->state->name,
                    'client_orderno' => $supply_number,
                    'orderamount' => $storeSupply->total_amount,
                    'orderdiscount_percent' => 0,
                    'orderquantity' => $storeSupply->details->sum('qty'),
                    'ordertype' => 'Regular',
                    'products' => [
                        'product' => $items
                    ],
                    'remarks' => 'Store Order from Winzera ' . $supply_number,
                    'user' => $customer_name,
                    'transport' => 'Shiprocket',
                    'transportmode' => 'Road'
                ]
            ]
        ];

        $apiLog = WmsApiLog::create([
            'provider' => $this->provider,
            'supply_id' => $storeSupply->id,
            'cmd' => 'CreateStoreOrder',
            'request' => $requestData
        ]);

        $response = (new Client())->request('POST', env('WMS_BASE_URL') . 'CreateOrder', [
            'json' => $requestData
        ]);

        $response = json_decode($response->getBody());

        $apiLog->response = $response;
        $apiLog->status = $response->Success ? WmsApiLog::SUCCESS : WmsApiLog::FAILED;
        $apiLog->save();

        if ($response->Success) {
            return (object)['status' => true, 'message' => $response->Remarks, 'supply_number' => $supply_number];
        } else {
            return (object)['status' => false, 'message' => $response->Remarks];
        }
    }

    public static function getPackagingStatus()
    {
        $requestData = [
            'FinancialYear' => self::getFinancialYear(),
            'ClientCode' => env('WMS_CLIENT_CODE'),
        ];

        try {

            $response = (new Client())->request('POST', env('WMS_BASE_URL') . 'GetPackingSlip', [
                'json' => $requestData
            ]);

            $response = json_decode($response->getBody());

            $response = collect($response)->first();

            if ($response->Success) {
                return (object)['status' => true, 'response' => $response];
            } else {
                return (object)['status' => false, 'message' => $response->Remarks];
            }

        } catch (RequestException $e) {
            return (object)['status' => false, 'message' => 'Exception:' . $e->getMessage()];
        }
    }

    /**
     * Everytime WMS receive the inwards from Winzera get status from this method
     * That inward will be Fresh Stock Inward
     * @return object
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getInwardStatus()
    {
        $requestData = [
            'FinancialYear' => self::getFinancialYear(),
            'ClientCode' => env('WMS_CLIENT_CODE'),
            'VchType' => 0
        ];

        try {

            $response = (new Client())->request('POST', env('WMS_BASE_URL') . 'GetGoodsInward', [
                'json' => $requestData
            ]);

            $response = json_decode($response->getBody());

            $response = collect($response)->first();

            if ($response->Success) {
                return (object)['status' => true, 'response' => $response];
            } else {
                return (object)['status' => false, 'message' => $response->Remarks];
            }

        } catch (RequestException $e) {
            return (object)['status' => false, 'message' => 'Exception:' . $e->getMessage()];
        }
    }


    /**
     * After Receiving the inward status send back Acknowledgement to Aaj enterprise
     * @return object
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function sendInwardStatus()
    {
        $requestData = [
            'FinancialYear' => self::getFinancialYear(),
            'ClientCode' => env('WMS_CLIENT_CODE'),
            'VchCode' => 0
        ];

        try {

            $response = (new Client())->request('POST', env('WMS_BASE_URL') . 'InwardAcknowledgement', [
                'json' => $requestData
            ]);

            $response = json_decode($response->getBody());

            $response = collect($response)->first();

            if ($response->Success) {
                return (object)['status' => true, 'response' => $response];
            } else {
                return (object)['status' => false, 'message' => $response->Remarks];
            }

        } catch (RequestException $e) {
            return (object)['status' => false, 'message' => 'Exception:' . $e->getMessage()];
        }
    }

    /**
     * On Sales Return we can use this method
     * @return object
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getSalesReturnStatus()
    {
        $requestData = [
            'FinancialYear' => self::getFinancialYear(),
            'ClientCode' => env('WMS_CLIENT_CODE'),
            'VchType' => 0
        ];

        try {

            $response = (new Client())->request('POST', env('WMS_BASE_URL') . 'GetSaleReturn', [
                'json' => $requestData
            ]);

            $response = json_decode($response->getBody());

            $response = collect($response)->first();

            if ($response->Success) {
                return (object)['status' => true, 'response' => $response];
            } else {
                return (object)['status' => false, 'message' => $response->Remarks];
            }

        } catch (RequestException $e) {
            return (object)['status' => false, 'message' => 'Exception:' . $e->getMessage()];
        }
    }

    private static function getFinancialYear()
    {
        return str_replace('-', '_', Helper::getFinancialYear());
    }
}