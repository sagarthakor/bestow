<?php


namespace App\Library\Calculation;


use App\Models\Binary\BinaryAchievement;
use App\Models\Binary\BinaryCalculation;
use App\Models\MonthlyMatchingAchiever;
use App\Models\MonthlyMatchingQualification;
use App\Models\Order;
use App\Models\StoreManager\Store;
use App\Models\User;
use App\Models\UserStatus;
use App\Models\Wallet;
use App\Models\WeeklyMatching;
use Carbon\Carbon;

class WalletCalculation
{
    public function cpIncome()
    {
        Order::
        whereRaw("(store_id is null or exists(select * from stores where stores.id = orders.store_id and stores.type <> " . Store::RETAILER_TYPE . "))")
            ->whereNotNull('customer_id')
            ->whereNotNull('approved_at')
            ->with(['customer.user', 'details'])
            ->where('consistency_purchase', UserStatus::NO)
            ->whereNull('wallet_id')->get()->map(function ($order) {

                $total_distributor_price = $order->details->where('selling_price', '>', 1)->sum(function ($detail) {
                    return ($detail->customer_price - $detail->distributor_price) * $detail->qty;
                });

                $amount = $total_distributor_price;

                if ($amount <= 0) {
                    return false;
                }

                $wallet = $order->customer->user->credit(collect([
                    'amount' => $amount,
                    'income_type' => Wallet::PREFERRED_CUSTOMER,
                    'remarks' => 'CP Income from Order: ' . $order->customer_order_id
                ]));

                $order->wallet_id = $wallet->id;
                $order->save();

            });
    }

    public function weeklyMatching()
    {
        WeeklyMatching::whereNull('wallet_id')->with(['user'])->get()->map(function ($weekly_matching) {

            $wallet = $weekly_matching->user->credit(collect([
                'amount' => $weekly_matching->amount,
                'income_type' => Wallet::MATCHING_CLUB_BONUS,
                'remarks' => 'Matching Club Bonus from Week: ' . $weekly_matching->start_at->format('d M Y') . ' - ' . $weekly_matching->end_at->format('d M Y')
            ]));

            $weekly_matching->wallet_id = $wallet->id;
            $weekly_matching->save();

        });
    }

    public static function achieverBonus($month_name)
    {
        $date = Carbon::parse($month_name)->endOfMonth()->toDateString();

        $UNIT_VALUE = 45000;

        /* Achievers till Previous Month Only */
        MonthlyMatchingAchiever::whereType(MonthlyMatchingAchiever::ACHIEVER_BONUS)
            ->whereDate('created_at', '<=', $date)->get()->map(function ($achiever) use ($month_name, $UNIT_VALUE) {

                \DB::transaction(function () use ($month_name, $achiever, $UNIT_VALUE) {

                    $qualified = MonthlyMatchingQualification::whereMonthName($month_name)->with(['user'])->whereUserId($achiever->user_id)->first();

                    if (!$qualified)
                        return false;

                    $earned_units = floor($qualified->matched_points / $UNIT_VALUE);

                    $income = round($earned_units * ($UNIT_VALUE * 0.05), 2);

                    $qualified->units = $earned_units;
                    $qualified->amount = $income;

                    $wallet = $qualified->user->credit(collect([
                        'amount' => $qualified->amount,
                        'income_type' => Wallet::ACHIEVER_BONUS,
                        'remarks' => 'Achiever Bonus from Month: ' . $month_name . ' against units: ' . $earned_units,
                        'month_name' => $month_name
                    ]));

                    $qualified->wallet_id = $wallet->id;
                    $qualified->save();

                });

            });

        /* Achievers till Previous Month Only */
        MonthlyMatchingAchiever::whereType(MonthlyMatchingAchiever::SUPER_ACHIEVER_BONUS)
            ->whereDate('created_at', '<=', $date)->get()->map(function ($achiever) use ($month_name, $UNIT_VALUE) {

                \DB::transaction(function () use ($month_name, $achiever, $UNIT_VALUE) {

                    $qualified = MonthlyMatchingQualification::whereMonthName($month_name)->with(['user'])->whereUserId($achiever->user_id)->first();

                    if (!$qualified)
                        return false;

                    $earned_units = floor($qualified->matched_points / $UNIT_VALUE);

                    $income = round($earned_units * ($UNIT_VALUE * 0.05), 2);

                    $qualified->units = $earned_units;
                    $qualified->amount = $income;

                    $wallet = $qualified->user->credit(collect([
                        'amount' => $qualified->amount,
                        'income_type' => Wallet::SUPER_ACHIEVER_BONUS,
                        'remarks' => 'Super Achiever Bonus from Month: ' . $month_name . ' against units: ' . $earned_units,
                        'month_name' => $month_name
                    ]));

                    $qualified->sab_wallet_id = $wallet->id;
                    $qualified->save();

                });

            });
    }
}