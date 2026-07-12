<?php

namespace App\Library;


use App\Models\Customer;
use App\Models\SmsTransaction;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Sms
{
    protected $user;
    protected $customer;
    protected $message;
    protected $template_id;
    protected $mobile_number;
    protected $template_name;

    /**
     * @param User $user
     * @param Customer $customer
     * @param string $mobile
     * @return $this
     */
    public function to($user = null, $mobile = null, $customer = null)
    {
        $this->user = $user ? $user : $customer;
        $this->mobile_number = $this->user ? $this->user->mobile : $this->customer->mobile;

        return $this;
    }

    /**
     * @param $name
     * @param array $parameter
     * @return $this
     * @throws \Exception
     */
    public function template($name, $parameter = [])
    {
        if (!is_array($parameter))
            throw new \Exception;

        $template = config('sms_templates.' . $name);

        $this->template_id = $template['id'];

        $this->template_name = $name;

        $this->message = preg_replace_callback('/:\w+/', function () use (&$parameter) {
            return array_shift($parameter);
        }, $template['message']);

        return $this;
    }

    /**
     * @return boolean
     */
    public function sendOne()
    {

        if (env('APP_ENV') != 'local') {

            try {

                (new Client())->request('GET', 'https://portal.mobtexting.com/api/v2/sms/send', [
                    'query' => [
                        'method' => 'sms.normal',
                        'access_token' => env('SMS_API_KEY'),
                        'to' => $this->mobile_number,
                        'sender' => env('SMS_SENDER_ID'),
                        'message' => $this->message,
                        'service' => 'T',
                        'template_id' => $this->template_id
                    ]
                ]);

                SmsTransaction::create([
                    'category' => $this->template_name,
                    'user_id' => $this->user ? $this->user->id : null,
                    'mobile' => $this->mobile_number,
                    'message' => $this->message
                ]);

                return true;

            } catch (RequestException $e) {
                \Log::error('SMS EXCEPTION', [
                    $e->getMessage(), $e->getCode()
                ]);
                return false;
            }

        }
        return false;
    }

}