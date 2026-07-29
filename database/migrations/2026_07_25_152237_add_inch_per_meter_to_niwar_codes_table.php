<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInchPerMeterToNiwarCodesTable extends Migration
{
    public function up()
    {
        Schema::table('niwar_codes', function (Blueprint $table) {
            $table->decimal('inch_per_meter', 10, 4)->default(39.37)->after('rate');
        });
    }

    public function down()
    {
        Schema::table('niwar_codes', function (Blueprint $table) {
            $table->dropColumn('inch_per_meter');
        });
    }
}
