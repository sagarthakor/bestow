<?php
/**
 * Created by PhpStorm.
 * User: tymkdeveloper
 * Date: 19/7/21
 * Time: 2:49 PM
 */

namespace App\Library;


use App\Library\Calculation\Binary\Builder;
use App\Models\NestedSetUser;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserBank;
use App\Models\UserDetail;
use App\Models\UserStatus;
use Carbon\Carbon;
use Faker\Factory;

class SetupUser
{
    /**
     * @param $parent_tracking_id
     * @param $total_users
     * @param int $type (1: Horizontal, 2: Vertical)
     */
    public function companyStructure($parent_tracking_id, $total_users, $type = 1)
    {
        $parent_user = User::whereTrackingId($parent_tracking_id)->first();

        for ($i = 0; $i < $total_users; $i++) {

            $total_children = User::whereParentId($parent_user->id)->count();

            if ($type == 1)
                $this->userCreateSkeleton($parent_user->id, $parent_user->id, ($total_children + 1));
            else
                $parent_user = $this->userCreateSkeleton($parent_user->id, $parent_user->id, ($total_children + 1));

            echo 'users:' . ($i + 1) . PHP_EOL;

        }

    }

    private function userCreateSkeleton($parent_id, $sponsor_id, $leg)
    {
        return \DB::transaction(function () use ($parent_id, $sponsor_id, $leg) {

            $faker = Factory::create('en_IN');

            $tracking_id = 'WE' . rand(1000000, 9999999);

            while (User::whereTrackingId($tracking_id)->exists()) {
                $tracking_id = 'WE' . rand(1000000, 9999999);
            }

            $user = User::create([
                'parent_id' => $parent_id,
                'sponsor_by' => $sponsor_id,
                'leg' => $leg,
                'tracking_id' => $tracking_id,
                'username' => $tracking_id,
                'password' => $tracking_id . '#' . strtoupper(str_random(4)),
                'wallet_password' => strtoupper(str_random(6)),
                'email' => $faker->email,
                'token' => strtoupper(str_random(15)),
                'mobile' => 9879879870
            ]);

            UserDetail::create([
                'user_id' => $user->id,
                'title' => 'Mr',
                'first_name' => 'Winzera',
                'middle_name' => '',
                'mother_name' => '',
                'last_name' => '',
                'gender' => 1,
                'birth_date' => Carbon::now()->subYears(18),
                'nominee_name' => $faker->name,
                'nominee_relation' => 'Father',
                'nominee_birth_date' => Carbon::now()->subYears(25)
            ]);

            UserStatus::create([
                'user_id' => $user->id,
            ]);

            UserBank::create([
                'user_id' => $user->id
            ]);

            UserAddress::create([
                'user_id' => $user->id,
            ]);

            return $user;

        });
    }

    /* Upload Users from Json File*/
    public static function uploadUser()
    {
        $users = json_decode(\File::get(public_path('data/third-party-users/spr-16-oct-2021.json')));

        $winzera_id = 'WE2072229';

        $old_users = collect($users)->map(function ($user) {
            $user->user_id = strtoupper($user->user_id);
            $user->sponsor_id = strtoupper($user->sponsor_id);
            return $user;
        })->map(function ($old_user) use ($users) {

            if (!collect($users)->where('user_id', $old_user->sponsor_id)->first()) {
                $old_user->sponsor_by = 0;
            } else {
                $old_user->sponsor_by = $old_user->sponsor_id;
            }

            return $old_user;

        })->reject(function ($old_user) {
            return in_array($old_user->user_id, self::excludeIds());
        })->values();

        $old_users->map(function ($old_user, $index) use ($winzera_id) {

            echo $index . PHP_EOL;

            \DB::transaction(function () use ($old_user, $winzera_id) {

                if (!$old_user->sponsor_by) {
                    $sponsor_user = User::whereTrackingId($winzera_id)->first()->id;
                    $children_count = User::whereSponsorBy($sponsor_user)->count();
                } else {
                    $children_count = 0;
                    $sponsor_user = null;
                }


                $user = User::create([
                    'parent_id' => $sponsor_user ?: null,
                    'sponsor_by' => $sponsor_user ?: null,
                    'tracking_id' => $old_user->user_id,
                    'username' => $old_user->user_id,
                    'password' => 'WINZERA123',
                    'wallet_password' => strtoupper(str_random(6)),
                    'leg' => $sponsor_user ? ($children_count + 1) : 0,
                    'email' => '',
                    'mobile' => $old_user->mobile_no,
                    'token' => strtoupper(str_random(15)),
                    'old_records' => [
                        'user_id' => $old_user->user_id,
                        'sponsor_id' => $old_user->sponsor_by ?: null
                    ]
                ]);

                UserDetail::create([
                    'user_id' => $user->id,
                    'title' => 'Mr.',
                    'first_name' => $old_user->name,
                    'middle_name' => '',
                    'last_name' => '',
                    'father_name' => '',
                    'mother_name' => '',
                    'gender' => 1,
                    'birth_date' => Carbon::now()->subYears(20),
                ]);

                UserStatus::create([
                    'user_id' => $user->id,
                ]);

                UserBank::create([
                    'user_id' => $user->id
                ]);

                UserAddress::create([
                    'user_id' => $user->id,
                    'address' => '',
                    'city' => 'New Delhi',
                    'district' => 'Delhi',
                    'state_id' => 9,
                    'pincode' => 110001
                ]);

                return $user;

            });

        });

        self::setLegAndParent();

//        for ($i = 0; $i < 10; $i++) {
//            (new Builder())->addToTree();
//        }
//
//        for ($i = 0; $i < 10; $i++) {
//            (new NestedSetUser())->setData();
//        }
    }

    public static function setLegAndParent()
    {
        User::whereDate('created_at', '=', '2021-10-18')
            ->whereNotNull('old_records')->whereNull('sponsor_by')->get()->map(function ($user) {

                if (!$sponsor = User::whereTrackingId($user->old_records->sponsor_id)->first()) {
                    echo 'NoSponsor: ' . $user->tracking_id . PHP_EOL;
                    return false;
                }

                $children_count = User::whereSponsorBy($sponsor->id)->count();

                $user->sponsor_by = $sponsor->id;
                $user->parent_id = $sponsor->id;
                $user->leg = ($children_count + 1);
                $user->save();

            });
    }

    public static function excludeIds(): array
    {
        return ['SPR0051117GZP', 'SPR0051118GZP', 'SPR0051121GZP', 'SPR0051140GZP', 'SPR0051141GZP', 'SPR0051165GZP', 'SPR0051166GZP', 'SPR0051167GZP', 'SPR0051168GZP', 'SPR0051169GZP', 'SPR0051142GZP', 'SPR0051162GZP', 'SPR0051163GZP', 'SPR0051164GZP', 'SPR0051143GZP', 'SPR0051158GZP', 'SPR0051159GZP', 'SPR0051160GZP', 'SPR0051161GZP', 'SPR0051119GZP', 'SPR0051144GZP', 'SPR0051145GZP', 'SPR0051146GZP', 'SPR0051147GZP', 'SPR0051148GZP', 'SPR0051151GZP', 'SPR0051150GZP', 'SPR0051149GZP', 'SPR0051153GZP', 'SPR0051154GZP', 'SPR0051155GZP', 'SPR0051156GZP', 'SPR0051152GZP', 'SPR0051191GZP', 'SPR0051192GZP', 'SPR0051195GZP', 'SPR0051197GZP', 'SPR0051198GZP', 'SPR0051237GZP', 'SPR0051238AZM', 'SPR0051199GZP', 'SPR0051200GZP', 'SPR0051239GZP', 'SPR0051240GZP', 'SPR0051243GZP', 'SPR0051244GZP', 'SPR0051245GZP', 'SPR0051249GZP', 'SPR0051251GZP', 'SPR0051253GZP', 'SPR0051250GZP', 'SPR0051252GZP', 'SPR0051255GZP', 'SPR0051256GZP', 'SPR0051257GZP', 'SPR0051241GZP', 'SPR0051242GZP', 'SPR0051246GZP', 'SPR0051247GZP', 'SPR0051258GZP', 'SPR0051259GZP', 'SPR0051269GZP', 'SPR0051260GZP', 'SPR0051261GZP', 'SPR0051262GZP', 'SPR0051263GZP', 'SPR0051265GZP', 'SPR0051266GZP', 'SPR0051264GZP', 'SPR0051267GZP', 'SPR0051268GZP', 'SPR0051248GZP', 'SPR0051270GZP', 'SPR0051271GZP', 'SPR0051201GZP', 'SPR0051202GZP', 'SPR0051274GZP', 'SPR0051275GZP', 'SPR0051276GZP', 'SPR0051307GZP', 'SPR0051308GZP', 'SPR0051313GZP', 'SPR0051314GZP', 'SPR0051315GZP', 'SPR0051316GZP', 'SPR0051317GZP', 'SPR0051309GZP', 'SPR0051310GZP', 'SPR0051311GZP', 'SPR0051312GZP', 'SPR0051306GZP', 'SPR0051318GZP', 'SPR0051328GZP', 'SPR0051335GZP', 'SPR0051336GZP', 'SPR0051342GZP', 'SPR0051343GZP', 'SPR0051344GZP', 'SPR0051345GZP', 'SPR0051507GZP', 'SPR0051346GZP', 'SPR0051347GZP', 'SPR0051348GZP', 'SPR0051349GZP', 'SPR0051350GZP', 'SPR0051337GZP', 'SPR0051338GZP', 'SPR0051341GZP', 'SPR0051339GZP', 'SPR0051340GZP', 'SPR0051547GZP', 'SPR0051578GZP', 'SPR0051579GZP', 'SPR0051329GZP', 'SPR0051330GZP', 'SPR0051331GZP', 'SPR0051332GZP', 'SPR0051333GZP', 'SPR0051334GZP', 'SPR0051319GZP', 'SPR0051320GZP', 'SPR0051322GZP', 'SPR0051321GZP', 'SPR0051323GZP', 'SPR0051324GZP', 'SPR0051273GZP', 'SPR0051120GZP', 'SPR0051128GZP', 'SPR0051283GZP', 'SPR0051129GZP', 'SPR0051190GZP', 'SPR0051281GZP', 'SPR0051284GZP', 'SPR0051285GZP', 'SPR0051286GZP', 'SPR0051292GZP', 'SPR0051296GZP', 'SPR0051298GZP', 'SPR0051299GZP', 'SPR0051300GZP', 'SPR0051301GZP', 'SPR0051302GZP', 'SPR0051303GZP', 'SPR0051304GZP', 'SPR0051351GZP', 'SPR0051352GZP', 'SPR0051353GZP', 'SPR0051354GZP', 'SPR0051355GZP', 'SPR0051356GZP', 'SPR0051360GZP', 'SPR0051361GZP', 'SPR0051357GZP', 'SPR0051358GZP', 'SPR0051359GZP', 'SPR0051362GZP', 'SPR0051363GZP', 'SPR0051375GZP', 'SPR0051376GZP', 'SPR0051392GZP', 'SPR0051393GZP', 'SPR0051394GZP', 'SPR0051395GZP', 'SPR0051364GZP', 'SPR0051365GZP', 'SPR0051366GZP', 'SPR0051367GZP', 'SPR0051368GZP', 'SPR0051369GZP', 'SPR0051371GZP', 'SPR0051373GZP', 'SPR0051374GZP', 'SPR0051686GZP', 'SPR0051370GZP', 'SPR0051372GZP', 'SPR0051305GZP', 'SPR0051396GZP', 'SPR0051397GZP', 'SPR0051429GZP', 'SPR0051430GZP', 'SPR0051432GZP', 'SPR0051433GZP', 'SPR0051434GZP', 'SPR0051435GZP', 'SPR0051431GZP', 'SPR0051436GZP', 'SPR0051438GZP', 'SPR0051439GZP', 'SPR0051440GZP', 'SPR0051437GZP', 'SPR0051400GZP', 'SPR0051442GZP', 'SPR0051443GZP', 'SPR0051445GZP', 'SPR0051444GZPT', 'SPR0051451GZP', 'SPR0051473GZP', 'SPR0051474GZP', 'SPR0051475GZP', 'SPR0051476GZP', 'SPR0051477GZP', 'SPR0051479GZP', 'SPR0051484GZP', 'SPR0051485GZP', 'SPR0051486GZP', 'SPR0051487GZP', 'SPR0051488GZP', 'SPR0051489GZP', 'SPR0051490GZP', 'SPR0051478GZP', 'SPR0051481GZP', 'SPR0051483GZP', 'SPR0051480GZP', 'SPR0051482GZP', 'SPR0051468GZP', 'SPR0051469GZP', 'SPR0051471GZP', 'SPR0051472GZP', 'SPR0051812GZP', 'SPR0051813GZP', 'SPR0051814GZP', 'SPR0051470GZP', 'SPR0051420GZP', 'SPR0051455GZP', 'SPR0051456GZP', 'SPR0051457GZP', 'SPR0051458GZP', 'SPR0051459GZP', 'SPR0051460GZP', 'SPR0051461GZP', 'SPR0051462GZP', 'SPR0051463GZP', 'SPR0051464GZP', 'SPR0051398GZP', 'SPR0051423GZP', 'SPR0051424GZP', 'SPR0051425GZP', 'SPR0051426GZP', 'SPR0051427GZP', 'SPR0051428GZP', 'SPR0051399GZP', 'SPR0051401GZP', 'SPR0051422GZP', 'SPR0051421GZP', 'SPR0051297GZP', 'SPR0051293GZP', 'SPR0051294GZP', 'SPR0051295GZP', 'SPR0051282GZP', 'SPR0051287GZP', 'SPR0051288GZP', 'SPR0051289GZP', 'SPR0051290GZP', 'SPR0051291GZP', 'SPR0051277GZP', 'SPR0051278GZP', 'SPR0051279GZP', 'SPR0051280GZP'];
    }

}