<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SeedBeltReportAndReversalPermissions extends Migration
{
    /**
     * Same pattern as the other belt permission seeds: create the rows the menu
     * @can() checks reference and grant them to the roles that already hold the
     * equivalent belt permissions. Idempotent.
     *
     * Cancelling reverses stock, so it is deliberately a separate permission from
     * creating - a floor operator entering batches should not be able to unwind
     * them.
     *
     * @return void
     */
    public function up()
    {
        $permissions = [
            'belt_roll_production_cancel',
            'belt_cutting_cancel',
            'roll_production_report_view',
            'roll_material_consumption_report_view',
            'roll_stock_report_view',
            'belt_cutting_report_view',
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
     * No-op, same reasoning as the other belt permission seeds.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
