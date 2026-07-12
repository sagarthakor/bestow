<?php

namespace App\Library\Calculation\Binary;


use App\Models\User;

class Helper
{

    /**
     * @param integer $user_id
     * @param $parents
     * @return array
     */
    public function parents($user_id, &$parents)
    {
        $user = User::with(['parentBy'])->whereId($user_id)->first();

        if ($user->parentBy) {

            $parents[] = $user->parentBy;
            $this->parents($user->parent_id, $parents);
        }
        else {
            return $parents;
        }
    }

    /**
     * @param $user_id
     * @param $required_level
     * @param $parents
     * @return array
     */
    public function getSponsorParents($user_id, $required_level , &$parents)
    {
        $user = User::with(['sponsorBy', 'rank:id,level_income'])->whereId($user_id)->first();

        $parents[] = $user->sponsorBy;

        if (count($parents) == $required_level)
            return $parents;

        if ($user->parent_id)
            $this->getSponsorParents($user->sponsor_by, $required_level, $parents);
    }

    /**
     * @param User $sponsor
     * @param string $leg
     * @return mixed
     */
    public function findTheUpline($sponsor, $leg)
    {

        if (User::whereParentId($sponsor->id)->count() == 0) {
            $upline = User::whereId($sponsor->id)->first();
        }
        else {

            /* Get Sponsor Upline Direct Downline on Left or Right leg which was selected */
            $direct_downline = User::whereParentId($sponsor->id)->whereLeg($leg)->first();

            if ($direct_downline) {

                $upline = $this->getExtremeLast($direct_downline, $leg);

                /* if downline have downlines then we can retrieve the upline*/
                if ($upline) {

                    /* If Upline has already placement on that Leg then redirect to front page*/
                    if (User::whereParentId($upline->id)->whereLeg($leg)->exists())
                        return (object) ['status' => false, 'error' => 'We can not place this user to Leg: ' . $leg . ' Try Again'];
                }
                else {
                    $upline = $direct_downline;
                }

            }
            else  {
                $upline = User::whereId($sponsor->id)->first();
            }

        }

        return (object) ['status' => true, 'upline' => $upline];
    }

    /**
     * @param User $directDownline
     * @param string $leg
     * @return mixed
     */
    public function getExtremeLast($directDownline, $leg)
    {
        $child = User::whereParentId($directDownline->id)->whereLeg($leg)->first();

        if ($child == null)
            return $directDownline;
        else
            return $this->getExtremeLast($child, $leg);
    }

}