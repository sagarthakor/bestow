<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutwardStockItemsTable extends Migration
{
    /**
     * One line per product taken out on an outward stock document.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outward_stock_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('outward_stock_id');
            $table->integer('product');
            $table->double('qty');
            $table->timestamps();

            $table->index('outward_stock_id');
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('outward_stock_items');
    }
}
