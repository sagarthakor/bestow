<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApiTokenToSalesmanTable extends Migration
{
    public function up()
    {
        Schema::table('salesman', function (Blueprint $table) {
            $table->string('api_token', 80)->nullable()->unique()->after('otp');
        });
    }

    public function down()
    {
        Schema::table('salesman', function (Blueprint $table) {
            $table->dropColumn('api_token');
        });
    }
}
