<?php


namespace App\Library;


use App\Models\Customer;
use App\Models\Order;
use App\Models\RallywareApiLog;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Rallyware
{

    protected $access_token;

    public function getAccessToken(): Rallyware
    {
        try {

            $response = (new Client())->request('GET', env('RALLYWARE_BASE_URL') . 'oauth/v2/token', [
                'query' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => env('RALLYWARE_CLIENT_ID'),
                    'client_secret' => env('RALLYWARE_CLIENT_SECRET'),
                    'scope' => 'manager',
                ]
            ]);

            $response = json_decode($response->getBody());

            $this->access_token = $response->access_token;


        } catch (RequestException $e) {
            $this->access_token = null;
        }

        return $this;
    }

    /**
     * @param int $externalUserId
     * @return object
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSsoLink(int $externalUserId = 7256096)
    {
        try {

            $response = (new Client())->request('GET', env('RALLYWARE_BASE_URL') . "api/users/{$externalUserId}/sso_link", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->access_token
                ],
            ]);

            $response = json_decode($response->getBody(), true);

            return (object)[
                'status' => true,
                'sso_id' => $response['@id']
            ];

        } catch (RequestException $e) {

            \Log::error('RallyWare Sso Exception', [$e->getResponse()->getBody()]);

            return (object)[
                'status' => false,
                'message' => 'Exception - User Not Found or not able to redirect'
            ];

        }
    }

    public function createUser(User $user)
    {

        $order = Order::whereUserId($user->id)->whereNotNull('approved_at')->first();
        $orderAvg = Order::whereUserId($user->id)->avg('amount');
        $lifetime_personal_sales = Order::whereUserId($user->id)->sum('amount');
        $first_sponsor = User::whereSponsorBy($user->id)->first();

        $user_attributes = [
            'team' => $user->team_id ? $user->team->name : null,
            'license_paid' => '0',
            'mobile_number' => $user->mobile,
            'enrollment_date' => $user->created_at->format('d-m-Y'),
            'birthday_dmy' => $user->detail->birth_date ? Carbon::parse($user->detail->birth_date)->format('d-m-Y') : '',
            'number_of_customers' => Customer::whereUserId($user->id)->count(),
            'sponsor_first_name' => $user->sponsor_by ? $user->sponsorBy->detail->first_name : '',
            'sponsor_last_name' => $user->sponsor_by ? $user->sponsorBy->detail->last_name : '',
            'sponsor_id_level_1' => $user->sponsor_by ? $user->sponsorBy->tracking_id : '',
            'title_rank' => $user->director_id ? $user->director->name : 'Distributor',
            /* Orders */
            'first_order_placed' => $order ? Carbon::parse($order->created_at)->format('d-m-Y') : null,
            'total_orders_count' => Order::whereUserId($user->id)->count(),
            'average_order_amount' => $orderAvg ? round($orderAvg, 2) : 0,
            'lifetime_personal_sales' => $lifetime_personal_sales ? round($lifetime_personal_sales, 2) : 0,
            'average_days_between_orders' => 0,
            'last_sale' => 0,
            'ytd_personal_sales' => 0,
            'personal_sales' => 0,
            /* Team */
            'first_recruit' => $first_sponsor ? $first_sponsor->created_at->format('d-m-Y') : null,
            'new_recruits_by_year' => User::whereSponsorBy($user->id)->count(),
//            'point_bank_1' => 0,
//            'point_bank_2' => 0,
//            'mtd_volume_p' => 0,
//            'mtd_volume_g' => 0,
            'mtd_personal_sales' => 0,
            'downline_sales' => 0
        ];

        $user_attributes = collect($user_attributes)->map(function ($user_attribute_value, $key) {
            return [
                'name' => $key, 'value' => $user_attribute_value
            ];
        })->values()->toArray();

        $requestData = [
            'first_name' => $user->detail->first_name,
            'last_name' => $user->detail->last_name,
            'email' => $user->email,
            'external_id' => $user->tracking_id,
            'approved' => true,
            'is_email_verified' => true,
            'user_data_attributes' => $user_attributes,
            'city' => $user->address->city,
            'state_code' => substr($user->address->state->code, 0, 8),
            'country_code' => 'IN'
        ];

        try {

            $response = (new Client())->request('POST', env('RALLYWARE_BASE_URL') . 'api/users', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->access_token
                ],
                'json' => $requestData
            ]);

            $response = json_decode($response->getBody());

            return (object)[
                'status' => true,
                'rallyware_id' => $response->id
            ];

        } catch (RequestException $e) {

            \Log::error('Exception - RallyWare User Create - ' . $user->tracking_id, [$e->getResponse()->getBody()]);

            return (object)[
                'status' => false,
                'message' => 'Exception - Not able to create User',
                'requestData' => $requestData,
                'rallyware_response' => json_decode($e->getResponse()->getBody())
            ];

        }
    }

    public function updateUser(User $user)
    {
        if (!$user->rallyware_id)
            return false;

        $order = Order::whereUserId($user->id)->whereNotNull('approved_at')->first();
        $orderAvg = Order::whereUserId($user->id)->avg('amount');
        $lifetime_personal_sales = Order::whereUserId($user->id)->sum('amount');
        $first_sponsor = User::whereSponsorBy($user->id)->first();

        $user_attributes = [
            'team' => $user->team_id ? $user->team->name : null,
            'license_paid' => '0',
            'mobile_number' => $user->mobile,
            'enrollment_date' => $user->created_at->format('d-m-Y'),
            'birthday_dmy' => $user->detail->birth_date ? Carbon::parse($user->detail->birth_date)->format('d-m-Y') : '',
            'number_of_customers' => Customer::whereUserId($user->id)->count(),
            'sponsor_first_name' => $user->sponsor_by ? $user->sponsorBy->detail->first_name : '',
            'sponsor_last_name' => $user->sponsor_by ? $user->sponsorBy->detail->last_name : '',
            'sponsor_id_level_1' => $user->sponsor_by ? $user->sponsorBy->tracking_id : '',
            'title_rank' => $user->director_id ? $user->director->name : 'Distributor',
            /* Orders */
            'first_order_placed' => $order ? Carbon::parse($order->created_at)->format('d-m-Y') : null,
            'total_orders_count' => Order::whereUserId($user->id)->count(),
            'average_order_amount' => $orderAvg ? round($orderAvg, 2) : 0,
            'lifetime_personal_sales' => $lifetime_personal_sales ? round($lifetime_personal_sales, 2) : 0,
            'average_days_between_orders' => 0,
            'last_sale' => 0,
            'ytd_personal_sales' => 0,
            'personal_sales' => 0,
            /* Team */
            'first_recruit' => $first_sponsor ? $first_sponsor->created_at->format('d-m-Y') : null,
            'new_recruits_by_year' => User::whereSponsorBy($user->id)->count(),
            'mtd_personal_sales' => 0,
            'downline_sales' => 0
        ];

        $user_attributes = collect($user_attributes)->map(function ($user_attribute_value, $key) {
            return [
                'name' => $key, 'value' => $user_attribute_value
            ];
        })->values()->toArray();

        $requestData = [
            'first_name' => $user->detail->first_name,
            'last_name' => $user->detail->last_name,
            'email' => $user->email,
            'external_id' => $user->tracking_id,
            'approved' => true,
            'is_email_verified' => true,
            'user_data_attributes' => $user_attributes,
            'city' => $user->address->city,
            'state_code' => substr($user->address->state->code, 0, 8),
            'country_code' => 'IN'
        ];

        try {

            $response = (new Client())->request('PUT', env('RALLYWARE_BASE_URL') . 'api/users/external/' . $user->tracking_id, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->access_token,
                    'Content-Type' => 'application/json'
                ],
                'json' => $requestData
            ]);

            $response = json_decode($response->getBody());

            return (object)[
                'status' => true,
                'rallyware_id' => $response->id
            ];

        } catch (RequestException $e) {

            \Log::error('Exception - RallyWare User Update - ' . $user->tracking_id, [$e->getResponse()->getBody()]);

            $response = json_decode($e->getResponse()->getBody());

            RallywareApiLog::create([
                'user_id' => $user->id,
                'method' => 'user_update',
                'request' => collect($requestData)->toArray(),
                'response' => collect($response)->toArray()
            ]);

            return (object)[
                'status' => false,
                'message' => 'Exception - Not able to Update User',
                'requestData' => $requestData,
                'rallyware_response' => $response
            ];

        }

    }

    public function getUser($rallyware_id)
    {
        try {

            $response = (new Client())->request('GET', env('RALLYWARE_BASE_URL') . 'api/users/' . $rallyware_id, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->access_token
                ],
            ]);

            return json_decode($response->getBody());

        } catch (RequestException $e) {

            \Log::error('RallyWare Get ID Exception', [$e->getResponse()->getBody()]);

            return (object)[
                'status' => false,
                'message' => 'Exception - User Not Found'
            ];

        }
    }

}