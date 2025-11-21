<?php


namespace App\Library;


use App\Library\Calculation\Binary\Helper;
use App\Models\Binary\BinaryCalculation;
use App\Models\NestedSetUser;
use App\Models\User;

class PositionTransfer
{

    /* Stop the Cron before run this */
    public static function start(string $user_tracking_id, string $parent_tracking_id): string
    {
        if (!$user = User::whereTrackingId($user_tracking_id)->first()) {
            return 'User ' . $user_tracking_id . ' is not exists';
        }

        if (!$newParent = User::whereTrackingId($parent_tracking_id)->first()) {
            return 'Parent ' . $parent_tracking_id . ' is not exists';
        }

        if (!$nestedUser = NestedSetUser::whereUserId($user->id)->first())
            return 'User Nested not available, try after some time';

        if (!$parentNestedUser = NestedSetUser::whereUserId($newParent->id)->first())
            return 'User Nested not available, try after some time';

        if ($nestedUser->isDescendantOf($parentNestedUser))
            return "User: {$user_tracking_id} is Children of ID: {$parent_tracking_id}, Transfer Cancel";

        if ($parentNestedUser->isDescendantOf($nestedUser))
            return "Parent: {$user_tracking_id} is Children of ID: {$user_tracking_id}, Transfer Cancel";

        /* Minus Count from Parents */

        $transfer = \DB::transaction(function () use ($user, $newParent, $nestedUser, $parentNestedUser) {

            $children = BinaryCalculation::whereUserId($user->id)->sum('total_users');

            $totalUsers = ($children + 1);

            if (PositionTransfer::minusFromTree($user, $totalUsers))
            {
                $nestedUser->makeChildOf($parentNestedUser);

                $children_count = User::whereSponsorBy($newParent->id)->count();

                $newLeg = ($children_count + 1);

                User::whereId($user->id)->update([
                   'parent_id' => $newParent->id,
                   'sponsor_by' => $newParent->id,
                   'leg' => $newLeg
                ]);

                $nestedUser->leg = $newLeg;
                $nestedUser->save();

                return PositionTransfer::addToTree($user, $totalUsers);
            }
        });

        return $transfer ? 'Tree Transferred Complete' : 'Not Transferred';

    }


    private static  function addToTree(User $user, $totalUsers): bool
    {
        (new Helper())->parents($user->id, $parents);

        $leg = $user->leg;

        foreach ($parents as $parent) {

            if ($exist_in_calculation = BinaryCalculation::whereUserId($parent->id)->whereLeg($leg)->first()) {
                BinaryCalculation::whereId($exist_in_calculation->id)->increment('total_users', $totalUsers);
            }
            else {
                BinaryCalculation::create(['user_id' => $parent->id, 'leg' => $leg, 'total_users' => $totalUsers]);
            }

            $leg = $parent->leg;
        }

        return true;
    }

    private static function minusFromTree(User $user, $totalUsers): bool
    {
        (new Helper())->parents($user->id, $parents);

        $leg = $user->leg;

        foreach ($parents as $parent) {

            if ($exist_in_calculation = BinaryCalculation::whereUserId($parent->id)->whereLeg($leg)->first()) {
                BinaryCalculation::whereId($exist_in_calculation->id)->decrement('total_users', $totalUsers);
            }

            $leg = $parent->leg;
        }

        return true;
    }
}