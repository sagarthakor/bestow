<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBeltCuttingItemTable extends Migration
{
    /**
     * One line per size cut out of the roll. meter_per_piece is derived from the
     * niwar size chart at entry time but stored here, so a later size-chart edit
     * does not retroactively change what an old cutting entry consumed.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('belt_cutting_item', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('belt_cutting_id');
            $table->integer('belt_product');
            $table->string('size')->nullable();
            $table->integer('pieces');
            $table->double('meter_per_piece');
            $table->double('total_meter');
            $table->timestamps();

            $table->index('belt_cutting_id');
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('belt_cutting_item');
    }
}
