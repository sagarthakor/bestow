<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SeedSocksAndBeltMissingFormulaReportPermissions extends Migration
{
    /**
     * Idempotent, same shape as SeedBeltProductionAndReportPermissions: safe to
     * run more than once (firstOrCreate / givePermissionTo only add what's missing).
     *
     * @return void
     */
    public function up()
    {
        $permissions = [
            'socks_missing_formula_report_view',
            'belt_missing_formula_report_view',
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
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Intentionally left as a no-op, same reasoning as SeedBeltProductionAndReportPermissions.
    }
}
