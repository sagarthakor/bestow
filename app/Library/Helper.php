<?php

namespace App\Library;

use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class Helper
{
    /**
     * @param array $array
     * @return object
     */
    public static function arrayToObject($array)
    {

        foreach ($array as $key => $value) {
            if (is_array($value)) $array[$key] = self::arrayToObject($value);
        }
        return (object)$array;
    }

    /**
     * @param Paginator $collection
     * @param integer $index
     * @return float|int;
     */
    public static function tableIndex($collection, $index)
    {
        return ($collection->currentpage() - 1) * $collection->perpage() + $index + 1;
    }

    public static function getWeek($day = null)
    {
        $day = $day ?: now()->day;

        if ($day >= 1 && $day <= 7) {
            return (object)[
                'start_at' => now()->startOfMonth()->toDateString(),
                'end_at' => now()->startOfMonth()->addDays(6)->toDateString()
            ];
        }
        elseif ($day >= 8 && $day <= 14) {
            return (object)[
                'start_at' => now()->startOfMonth()->addDays(7)->toDateString(),
                'end_at' => now()->startOfMonth()->addDays(13)->toDateString()
            ];
        }
        elseif ($day >= 15 && $day <= 21) {
            return (object)[
                'start_at' => now()->startOfMonth()->addDays(14)->toDateString(),
                'end_at' => now()->startOfMonth()->addDays(20)->toDateString()
            ];
        }
        else {
            return (object)[
                'start_at' => now()->subMonthNoOverflow()->startOfMonth()->addDays(21)->toDateString(),
                'end_at' => now()->subMonthWithOverflow()->endOfMonth()->toDateString()
            ];
        }
    }



    public static function getWeekToDisplayPv($day = null)
    {
        $day = $day ?: now()->day;

        if ($day >= 1 && $day <= 7) {
            return (object)[
                'start_at' => now()->startOfMonth()->toDateString(),
                'end_at' => now()->startOfMonth()->addDays(6)->toDateString()
            ];
        }
        elseif ($day >= 8 && $day <= 14) {
            return (object)[
                'start_at' => now()->startOfMonth()->addDays(7)->toDateString(),
                'end_at' => now()->startOfMonth()->addDays(13)->toDateString()
            ];
        }
        elseif ($day >= 15 && $day <= 21) {
            return (object)[
                'start_at' => now()->startOfMonth()->addDays(14)->toDateString(),
                'end_at' => now()->startOfMonth()->addDays(20)->toDateString()
            ];
        }
        else {
            return (object)[
                'start_at' => now()->startOfMonth()->addDays(21)->toDateString(),
                'end_at' => now()->endOfMonth()->toDateString()
            ];
        }
    }



    public static function getFinancialYear($date = null): string
    {
        $date = $date ? Carbon::parse($date) : now();

        if (Carbon::parse($date)->month >= 4) {
            return Carbon::parse($date)->year . '-' . Carbon::parse($date)->addYear()->year;
        }
        else {
            return Carbon::parse($date)->subYear()->year . '-' . Carbon::parse($date)->year;
        }
    }

    /**
     * @param Carbon $start_date
     * @param Carbon $end_date
     * @return array
     */
    public static function monthRange(Carbon $start_date, Carbon $end_date): array
    {
        $start = strtotime($start_date->toDateString());
        $end = strtotime($end_date->toDateString());
        $months = [];

        while ($start <= $end) {
            $months[] = date('M Y', $start);
            $start = strtotime("+1 month", $start);
        }

        return $months;
    }

    public static function getClientIp()
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
    }

    public static function cartShippingCharge($weight)
    {
        if ($weight <= 500) {
            $shippingCharge = 49;
        }
        else if ($weight > 500 && $weight <= 1000) {
            $shippingCharge = 75;
        }
        else if ($weight > 1000 && $weight <= 2000) {
            $shippingCharge = 130;
        }
        else if ($weight > 2000 && $weight <= 3000) {
            $shippingCharge = 190;
        }
        else {
            $shippingCharge = 250; // 3Kg Above
        }

        return $shippingCharge;
    }

    public static function getNearest($arr, $var)
    {
        usort($arr, function ($a, $b) use ($var) {
            return abs($a - $var) - abs($b - $var);
        });
        return array_shift($arr);
    }

    /******* Month Helpers *******/
    public static function getMonthDetails($date = null)
    {
        $date = $date ? Carbon::parse($date) : now();

        /* Setting for March 2022 Month */
        if ((Carbon::parse($date)->gte(Carbon::parse('2022-03-03')->startOfDay()) && Carbon::parse($date)->lte(Carbon::parse('2022-03-31')->endOfDay())) || (Carbon::parse($date)->toDateString() == '2022-04-01') && Carbon::parse($date)->lte(Carbon::parse($date)->startOfDay()->addHours('6'))) {

            return (object)[
                'start_at' => '2022-03-03', 'end_at' => '2022-03-31', 'month_name' => 'Mar 2022'
            ];

        }
        else {

            /* OLD Months */
            if (Carbon::parse($date)->lt(Carbon::parse('2022-03-03'))) {
                return self::getOldMonth($date);
            }
            /* New Months (From 01 Apr 2022) */
            else {
                return self::getNewMonth($date);
            }
        }

    }

    /* Aug 2021 to Feb 2022 */
    public static function getOldMonth($date)
    {
        /* till 6:00 AM Closing */
        if (
            Carbon::parse("first thursday of {$date->format('M Y')}")->toDateString() == $date->toDateString()
            && Carbon::parse($date)->lte(Carbon::parse($date)->startOfDay()->addHours('6'))) {

            return (object)[
                'start_at' => Carbon::parse("first thursday of " . Carbon::parse($date)->subMonthNoOverflow()->format('M Y'))->toDateString(),
                'end_at' => Carbon::parse("first wednesday of " . Carbon::parse($date)->format('M Y'))->toDateString(),
                'month_name' => Carbon::parse($date)->subMonthNoOverflow()->format('M Y')
            ];

        }

        if (Carbon::parse("first thursday of {$date->format('M Y')}")->lte($date)) {

            return (object)[
                'start_at' => Carbon::parse("first thursday of " . Carbon::parse($date)->format('M Y'))->toDateString(),
                'end_at' => Carbon::parse("first wednesday of " . Carbon::parse($date)->addMonthsNoOverflow(1)->format('M Y'))->toDateString(),
                'month_name' => Carbon::parse($date)->format('M Y')
            ];

        }
        else {

            return (object)[
                'start_at' => Carbon::parse("first thursday of " . Carbon::parse($date)->subMonthNoOverflow()->format('M Y'))->toDateString(),
                'end_at' => Carbon::parse("first wednesday of " . Carbon::parse($date)->format('M Y'))->toDateString(),
                'month_name' => Carbon::parse($date)->subMonthNoOverflow()->format('M Y')
            ];
        }
    }

    public static function getNewMonth($date)
    {
        if (Carbon::parse($date)->day == 1 && Carbon::now()->lte(Carbon::parse($date)->startOfDay()->addHours('6'))) {

            return (object)[
                'start_at' => Carbon::parse($date)->subMonthNoOverflow()->startOfMonth()->toDateString(),
                'end_at' => Carbon::parse($date)->subMonthNoOverflow()->endOfMonth()->toDateString(),
                'month_name' => Carbon::parse($date)->subMonthNoOverflow()->format('M Y')
            ];

        }
        else {

            return (object)[
                'start_at' => Carbon::parse($date)->startOfMonth()->toDateString(),
                'end_at' => Carbon::parse($date)->endOfMonth()->toDateString(),
                'month_name' => Carbon::parse($date)->format('M Y')
            ];
        }
    }

    public static function getEnrollmentFinancialYear($date = null): array
    {
        $date = $date ? Carbon::parse($date) : now();

        if (Carbon::parse($date)->month >= 4) {
            return [
                'years' => [Carbon::parse($date)->year, Carbon::parse($date)->addYear()->year],
                'dates' => [
                    Carbon::parse($date)->startOfYear()->addMonths(3)->startOfMonth(), Carbon::parse($date)->addYear()->startOfYear()->addMonths(2)->endOfMonth()
                ]
            ];
        } else {

            return [
                'years' => [Carbon::parse($date)->subYear()->year, Carbon::parse($date)->year],
                'dates' => [
                    Carbon::parse($date)->subYear()->startOfYear()->addMonths(3)->startOfMonth(), Carbon::parse($date)->startOfYear()->addMonths(2)->endOfMonth()
                ]
            ];
        }
    }

    public static function get_setting($key, $type)
    {
        return Cache::remember("companies_{$type}_{$key}", 86400, function () use ($key, $type) {
            return "#d43533";
        });
    }

    public static function areActiveRoutes(array $routes, $output = "active")
    {
        foreach ($routes as $route) {
            if (Route::currentRouteName() === $route) {
                return $output;
            }
        }
        return '';
    }
}


