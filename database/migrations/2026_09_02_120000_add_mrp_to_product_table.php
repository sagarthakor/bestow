<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMrpToProductTable extends Migration
{
    public function up()
    {
        Schema::table('product', function (Blueprint $table) {
            $table->double('mrp')->nullable()->after('price');
        });
    }

    public function down()
    {
        Schema::table('product', function (Blueprint $table) {
            $table->dropColumn('mrp');
        });
    }
}
