<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SeedRollFormulaPermissions extends Migration
{
    /**
     * Same pattern as the other belt permission seeds: create the rows the menu
     * @can() checks reference and grant them to the roles that already hold the
     * equivalent belt permissions. Idempotent.
     *
     * @return void
     */
    public function up()
    {
        $permissions = [
            'roll_formula_view',
            'roll_formula_create',
            'roll_formula_update',
            'roll_formula_delete',
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
