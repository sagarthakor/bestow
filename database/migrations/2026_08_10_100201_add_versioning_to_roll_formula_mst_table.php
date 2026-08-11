<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddVersioningToRollFormulaMstTable extends Migration
{
    /**
     * A roll formula gets edited - a thread colour changes, a supplier's dhaga is
     * swapped - and until now that edit rewrote the only copy, so a batch woven
     * six months ago silently claimed to have used today's recipe.
     *
     * roll_formula_mst stays the one current recipe per semi product (Roll
     * Production only ever wants the current one), and every saved state of it is
     * additionally frozen into roll_formula_revision. A batch records the version
     * it was started on, so its recipe can always be shown exactly as it stood.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roll_formula_mst', function (Blueprint $table) {
            $table->unsignedInteger('version')->default(1)->after('niwar_code_id');
            $table->string('status')->default('active')->after('version');
            $table->date('effective_date')->nullable()->after('status');
            $table->text('notes')->nullable()->after('effective_date');
            $table->bigInteger('user_id')->nullable()->after('notes');
        });

        Schema::create('roll_formula_revision', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('roll_formula_id');
            $table->unsignedInteger('version');
            $table->unsignedBigInteger('niwar_code_id');
            // The whole item list as it stood, so the revision reads correctly even
            // if a raw material or a niwar category is deleted later.
            $table->text('items');
            $table->date('effective_date')->nullable();
            $table->text('notes')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->timestamps();

            $table->unique(['roll_formula_id', 'version']);
        });

        $this->freezeCurrentAsVersionOne();
    }

    /**
     * Every formula that already exists becomes its own version 1, so nothing is
     * left without a revision to point at.
     */
    private function freezeCurrentAsVersionOne()
    {
        $formulas = DB::table('roll_formula_mst')->get();

        foreach ($formulas as $formula) {
            $items = DB::table('roll_formula_mst_item')
                ->where('roll_formula_id', $formula->id)
                ->get()
                ->map(fn ($i) => [
                    'niwar_type_material_id' => (int) $i->niwar_type_material_id,
                    'material' => (int) $i->material,
                    'gm_per_meter' => (float) $i->gm_per_meter,
                ])
                ->all();

            DB::table('roll_formula_revision')->insert([
                'roll_formula_id' => $formula->id,
                'version' => 1,
                'niwar_code_id' => $formula->niwar_code_id,
                'items' => json_encode($items),
                'effective_date' => null,
                'notes' => 'Version 1 recorded when formula versioning was introduced.',
                'user_id' => null,
                'created_at' => $formula->created_at ?? now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('roll_formula_mst')->update(['version' => 1, 'status' => 'active']);
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roll_formula_revision');

        Schema::table('roll_formula_mst', function (Blueprint $table) {
            $table->dropColumn(['version', 'status', 'effective_date', 'notes', 'user_id']);
        });
    }
}
