<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRollFormulaMstItemTable extends Migration
{
    /**
     * One raw material of a roll formula, in grams per meter of niwar.
     *
     * niwar_type_material_id says which category of the niwar code the row fills
     * - Mono or Roto. Every category's rows must add up to exactly that
     * category's gm_per_meter, which is what keeps the Niwar Code master the
     * single authority on how heavy a meter of niwar is.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roll_formula_mst_item', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('roll_formula_id');
            $table->unsignedBigInteger('niwar_type_material_id');
            $table->bigInteger('material');
            $table->decimal('gm_per_meter', 10, 4);
            $table->timestamps();

            $table->index('roll_formula_id');
            $table->index('niwar_type_material_id');
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roll_formula_mst_item');
    }
}
