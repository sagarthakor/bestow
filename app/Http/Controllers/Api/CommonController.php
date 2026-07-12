<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Library\Helper;
use App\city;
use App\country;
use App\state;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    public function getAppVersion(Request $request): JsonResponse
    {
        try {
            $version = $request->device == 'android' ? env('ANDROID_APP_VERSION') : env('IOS_APP_VERSION');

            return Helper::responseHandler(true, 'App Versions', ['version' => $version]);

        } catch (\Exception $exception) {
            \Log::error('Error during Get App Version: ' . $exception->getMessage());
            return Helper::responseHandler(false, 'Internal Server Error', [], 500);
        }
    }

    public function countries(): JsonResponse
    {
        try {
            $countries = Country::whereStatus(Country::ACTIVE)->selectRaw('id , name ')
            ->active()->get()->toArray();
            if (count($countries) == 0) {
                return Helper::responseHandler(false, 'Countries not exists', [], 401);
            }

            return Helper::responseHandler(true, '', ['countries' => $countries]);
        } catch (\Exception $exception) {

            \Log::error('Get Countries : ' . $exception->getMessage());
            return Helper::responseHandler(false, 'Internal Server Error', [], 500);
        }
    }

    public function getStates(Request $request): JsonResponse
    {
        try {
            if (!State::with(['country:id,name'])->whereCountryId($request->country_id)->exists()){
                return Helper::responseHandler(true, 'States not exists', [],200);
            }

            $states = State::whereCountryId($request->country_id)
            ->active()->get()->map(function ($state) {
                return (object)[
                    'id' => $state->id,
                    'name' => $state->name,
                    'country_id' => $state->country->id,
                    'country_name' => $state->country->name,
                ];
            })->toArray();

            return Helper::responseHandler(true, '', ['states' => $states]);

        } catch (\Exception $exception) {
            \Log::error('Get States : ' . $exception->getMessage());
            return Helper::responseHandler(false, 'Internal Server Error', [], 500);
        }
    }

    public function getCities(Request $request): JsonResponse
    {
        try {
            if (!City::with(['country:id,name', 'state:id,name'])->whereStateId($request->state_id)->exists())
                return Helper::responseHandler(true, 'Cities not exists', []);

            $cities = City::selectRaw('id , name, country_id, state_id')->whereStateId($request->state_id)->active()
            ->get()->map(function ($city) {
                return (object)[
                    'id' => $city->id,
                    'name' => $city->name,
                    'state_id' => $city->state->id,
                    'state_name' => $city->state->name,
                    'country_id' => $city->country->id,
                    'country_name' => $city->country->name,
                ];
            })->toArray();

            return Helper::responseHandler(true, '', ['cities' => $cities]);
        } catch (\Exception $exception) {
            \Log::error('Get Cities : ' . $exception->getMessage());
            return Helper::responseHandler(false, 'Internal Server Error', [], 500);
        }
    }

    public function ajaxGetStates(Request $request)
    {
        $country_id = $request->get('country_id');
        $option = '<option value="">Choose State</option>';
        $selected_id = 0;

        if ($request->has('selected_id') && !empty($request->get('selected_id'))) {
            $selected_id = $request->get('selected_id');
        }

        if (!empty($country_id)) {
            $states = state::where('country', $country_id)->pluck('state_name', 'id')->toArray();
            if (count($states) > 0) {
                foreach ($states as $id => $title) {
                    $option .= '<option value="' . $id . '" ' . (($selected_id == $id) ? 'selected' : '') . ' >' . $title . '</option>';
                }
            }
        }

        $child_url = route('ajax.cities') . '?html=state&country_id=' . $country_id;
        echo json_encode([
            'options' => $option, 'parent_id' => $country_id, 'child_class' => 'state-url', 'sub_child_class' => 'city1-url', 'child_url' => $child_url
        ]);
        die();
    }

    public function ajaxGetCities(Request $request)
    {
        $state_id = $request->get('state_id');

        $option = '<option value="">Choose City</option>';

        $selected_id = 0;

        if ($request->has('selected_id') && !empty($request->get('selected_id'))) {
            $selected_id = $request->get('selected_id');
        }

        if (!empty($state_id)) {

            $cities = city::where('state', $state_id)->pluck('city_name', 'id')->toArray();

            if (count($cities) > 0) {
                foreach ($cities as $id => $title) {
                    $option .= '<option value="' . $id . '" ' . (($selected_id == $id) ? 'selected' : '') . '>' . $title . '</option>';
                }
            }
        }

        echo json_encode(array('options' => $option, 'parent_id' => $state_id, 'child_class' => 'city-url', 'sub_child_class' => 'state1-url'));
        die();
    }
}
