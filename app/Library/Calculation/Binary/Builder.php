<?php

namespace App\Library\Calculation\Binary;


use App\Models\Binary\BinaryAchievement;
use App\Models\Binary\BinaryCalculation;
use App\Models\Binary\BinaryCalculationPvLog;
use App\Models\Customer;
use App\Models\MonthlyBvRecord;
use App\Models\Order;
use App\Models\OrderBvLog;
use App\Models\PackageOrder;
use App\Models\StoreManager\Store;
use App\Models\StoreManager\StoreSupply;
use App\Models\User;
use App\Models\UserStatus;
use App\Models\WeeklyBvRecord;
use Carbon\Carbon;

class Builder
{
    /**
     * Add Member / User to Binary Tree
     * Generally used while registration completed, in cron
     */
    public function addToTree()
    {
        UserStatus::with(['user'])->pendingTreeCalculation()->chunk(35, function ($user_statuses) {

            \DB::transaction(function () use ($user_statuses) {

                collect($user_statuses)->map(function ($user_status) {

                    $index = 0;
                    $next_leg = '';

                    (new Helper())->parents($user_status->user_id, $parents);

                    foreach ($parents as $parent) {

                        if ($index == 0) {
                            $leg = $user_status->user->leg;
                            $next_leg = $parent->leg;
                        } else {
                            $leg = $next_leg;
                            $next_leg = $parent->leg;
                        }

                        if ($exist_in_calculation = BinaryCalculation::whereUserId($parent->id)->whereLeg($leg)->first()) {
                            BinaryCalculation::whereId($exist_in_calculation->id)->increment('total_users', 1);
                        } else {
                            BinaryCalculation::create(['user_id' => $parent->id, 'leg' => $leg, 'total_users' => 1]);
                        }

                        $index++;
                    }

                    $user_status->tree_calculation = UserStatus::TREE_CALC_YES;
                    $user_status->save();

                });
            });

        });
    }

    public function makeUserActivate()
    {
        Customer::groupBy('user_id')->havingRaw('count(user_id) >= 2')->whereHas('user', function ($q) {
            $q->whereNull('paid_at');
        })->selectRaw('group_concat(id) as customer_ids, user_id')->get()->map(function ($record) {

            $customer_ids = explode(',', $record->customer_ids);

            $orders = Order::whereNotNull('approved_at')->whereIn('customer_id', $customer_ids)
                ->selectRaw('COALESCE(SUM(total_bv), 0) as total_bvs, customer_id')->groupBy(['customer_id'])->get();

            if (count($orders) < 2)
                return false;

            $total_bvs = collect($orders)->sum('total_bvs');

            if ($total_bvs < 3000)
                return false;

            User::whereId($record->user_id)->update(['paid_at' => now()]);

        });
    }

    public function addBvToTree()
    {
        Order::whereNotNull('approved_at')
            ->select(['user_id', 'customer_id', 'id', 'total_bv', 'amount', 'approved_at'])->whereBvCalculated(0)
            ->with(['user:id,leg,tracking_id', 'customer.user:id,leg,tracking_id'])
            ->chunk(30, function ($orders) {

                collect($orders)->map(function ($order) {

                    $order_user_id = $order->customer_id ? $order->customer->user_id : $order->user_id;

                    /*************** Self *************/
                    \DB::transaction(function () use ($order, $order_user_id) {
                        WeeklyBvRecord::setRecord($order, $order_user_id);
                        OrderBvLog::setRecord($order, $order_user_id);
                        MonthlyBvRecord::setRecord($order, $order_user_id);
                    });

                    if ($order_user_id == 1) {

                        Order::whereId($order->id)->update([
                            'bv_calculated' => UserStatus::YES
                        ]);

                        return false;
                    }

                    /*************** TEAM *************/
                    (new Helper())->parents($order_user_id, $parents);

                    $leg = $order->customer_id ? $order->customer->user->leg : $order->user->leg;

                    foreach ($parents as $parent) {

                        \DB::transaction(function () use ($order, $parent, $leg) {
                            WeeklyBvRecord::setRecord($order, $parent->id, $leg);
                            OrderBvLog::setRecord($order, $parent->id, $leg);
                            MonthlyBvRecord::setRecord($order, $parent->id, $leg);
                        });

                        $leg = $parent->leg;
                    }

                    Order::whereId($order->id)->update([
                        'bv_calculated' => UserStatus::YES
                    ]);

                });

            });
    }

    public function addBvToTreeFromCRM()
    {
        StoreSupply::whereHas('store', function ($q) {
            $q->whereType(Store::RETAILER_TYPE);
        })->whereBvCalculated(0)->with(['store:id,user_id', 'store.user:id,tracking_id'])
            ->chunk(30, function ($supplies) {
                collect($supplies)->map(function ($supply) {

                    if (!$supply->store->user) {
                        return false;
                    }

                    $user_id = $supply->store->user->id;

                    /*************** Self *************/
                    \DB::transaction(function () use ($supply, $user_id) {
                        WeeklyBvRecord::setRecordFromCrm($supply, $user_id);
                        OrderBvLog::setRecordFromCrm($supply, $user_id); // TODO: DONE
                        MonthlyBvRecord::setRecordFromCrm($supply, $user_id);
                    });

                    /*************** TEAM *************/
                    (new Helper())->parents($user_id, $parents);

                    $leg = $supply->store->user->leg;

                    foreach ($parents as $parent) {

                        \DB::transaction(function () use ($supply, $parent, $leg) {
                            WeeklyBvRecord::setRecordFromCrm($supply, $parent->id, $leg);
                            OrderBvLog::setRecordFromCrm($supply, $parent->id, $leg); // TODO: add Supply_id column & update the record same, OrderId will not update DONE
                            MonthlyBvRecord::setRecordFromCrm($supply, $parent->id, $leg);
                        });

                        $leg = $parent->leg;
                    }

                    StoreSupply::whereId($supply->id)->update([
                        'bv_calculated' => UserStatus::YES
                    ]);
                });
            });
    }

    /* For Developer level Check no any usage of this method for calculation */
    public static function checkMonthlyMatching($userId, $month_name): array
    {
        $user = User::whereId($userId)->with(['monthly_bvs' => function ($q) use ($month_name) {
            $q->where('month_name', $month_name);
        }])->first();

        $self_purchase_bv = $user->monthly_bvs->where('leg', '=', '')->sum('bv');
        $team_bv_records = $user->monthly_bvs->where('leg', '!=', '');

        /* Director Matched 30,0000 Start */
        $records = $team_bv_records->sortBy('bv')->values()->toArray();

        $pairs = [];
        for ($i = 0; $i < (count($team_bv_records) - 1); $i++) {
            $pairs[] = ['lft' => $records[($i)], 'rgt' => $records[($i) + 1]];
        }

        $pairs = collect(\App\Library\Helper::arrayToObject($pairs))->map(function ($pair, $index) {
            return [
                'id' => ($index + 1),
                'lft_leg' => $pair->lft->leg, 'rgt_leg' => $pair->rgt->leg,
                'lft_bv' => $pair->lft->bv, 'rgt_bv' => $pair->rgt->bv,
                'matched' => min($pair->lft->bv, $pair->rgt->bv)
            ];
        });

        $matched_points = $pairs->sum('matched') + $self_purchase_bv;

        return [
            'total_matched_points' => $matched_points,
            'selfPurchase' => $self_purchase_bv,
            'teamMatched' => $pairs->sum('matched'),
            'pairs' => $pairs,
        ];
    }

}