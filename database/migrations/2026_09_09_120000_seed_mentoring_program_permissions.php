<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SeedMentoringProgramPermissions extends Migration
{
    /**
     * Creates the mentoring_program_* permission rows referenced by the
     * "Mentoring Program" entry in config/rolePermissions.php so they show up
     * as checkboxes on the role create/edit screens. Idempotent: safe to run
     * more than once (firstOrCreate / givePermissionTo only add what's missing).
     *
     * @return void
     */
    public function up()
    {
        $permissions = [
            'mentoring_program_create',
            'mentoring_program_update',
            'mentoring_program_view',
            'mentoring_program_delete',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        $role = Role::where('name', 'Super Admin')->first();
        if ($role) {
            $role->givePermissionTo($permissions);
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
