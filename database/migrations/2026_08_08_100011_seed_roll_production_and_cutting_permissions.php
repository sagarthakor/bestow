<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SeedRollProductionAndCuttingPermissions extends Migration
{
    /**
     * Follows the same pattern as SeedBeltProductionAndReportPermissions:
     * create the Permission rows the menu @can() checks reference, then grant
     * them to the roles that already hold the equivalent belt permissions.
     * Idempotent.
     *
     * @return void
     */
    public function up()
    {
        $permissions = [
            'belt_roll_production_view',
            'belt_roll_production_create',
            'belt_cutting_view',
            'belt_cutting_create',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        foreach (['Super Admin', 'Admin', 'OFFICE'] as $roleName) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $role->givePermissionTo($permissions);
            }
        }
    }

    /**
     * Intentionally a no-op, same reasoning as the belt production permission
     * seed: dropping Permission rows other roles may hold is riskier than
     * leaving them.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
