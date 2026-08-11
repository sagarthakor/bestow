<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddModuleToPurchaseRequirementTable extends Migration
{
    /**
     * Roll production raises purchase requests the same way sock production does,
     * so they need to be tellable apart on the purchase side. Everything that
     * exists today came from sock production.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_requirement', function (Blueprint $table) {
            $table->string('module')->default('socks')->after('order_no');
        });

        DB::table('purchase_requirement')->update(['module' => 'socks']);
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_requirement', function (Blueprint $table) {
            $table->dropColumn('module');
        });
    }
}
