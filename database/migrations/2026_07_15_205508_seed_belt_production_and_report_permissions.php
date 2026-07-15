<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SeedBeltProductionAndReportPermissions extends Migration
{
    /**
     * These permission names are already referenced by menu.blade.php's @can()
     * checks and are already listed as checkboxes in config/rolePermissions.php,
     * but the Permission rows themselves were never created - so the menu items
     * never showed for any role, including Super Admin. Idempotent: safe to run
     * more than once (firstOrCreate / givePermissionTo only add what's missing).
     *
     * @return void
     */
    public function up()
    {
        $permissions = [
            'buckle_formula_create',
            'buckle_formula_update',
            'buckle_formula_view',
            'buckle_formula_delete',
            'belt_production_view',
            'sales_summary_report_view',
            'product_wise_sales_report_view',
            'salesman_wise_sales_report_view',
            'stock_available_report_view',
            'raw_material_pending_report_view',
            'production_pending_report_view',
            'stitching_pending_report_view',
            'pressing_pending_report_view',
            'packaging_pending_report_view',
            'belt_production_report_view',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        // Admin and OFFICE mirror Super Admin's production/report permission set
        // (same formula_*, production_*, *_report_view grants) but were missing
        // these new ones, so Belt Production/Reports stayed hidden for them too.
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
        // Intentionally left as a no-op: removing these permissions would only
        // matter if this feature set were being rolled back entirely, and
        // deleting Permission rows that other roles may have since been
        // granted is riskier than leaving them in place.
    }
}
