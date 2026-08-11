<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddWastageAndReversalToBeltCutting extends Migration
{
    /**
     * Cutting only ever accounted for the meters that became belts, so trim loss
     * had to hide inside the roll balance and reappear later as "scrap" when the
     * roll was closed. It also assumed every piece cut was a good piece.
     *
     * wastage_mtr records the trim lost on this cut, and rejected_pieces the
     * pieces that failed inspection - they still eat roll meters and fitting
     * material, but they never reach finished goods stock.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('belt_cutting', function (Blueprint $table) {
            $table->double('wastage_mtr')->default(0)->after('total_meter_used');
            $table->integer('total_rejected_pieces')->default(0)->after('total_pieces');
            $table->bigInteger('cancelled_by')->nullable()->after('status');
            $table->dateTime('cancelled_at')->nullable()->after('cancelled_by');
            $table->string('cancel_reason')->nullable()->after('cancelled_at');
        });

        Schema::table('belt_cutting_item', function (Blueprint $table) {
            $table->integer('rejected_pieces')->default(0)->after('pieces');
        });

        Schema::table('belt_cutting_material', function (Blueprint $table) {
            $table->double('stock_factor')->default(1)->after('required_qty');
        });

        $this->backfill();

        Schema::table('belt_cutting', function (Blueprint $table) {
            $table->unique('cutting_no');
        });
    }

    private function backfill()
    {
        $kgProducts = DB::table('product')
            ->join('uom', 'uom.id', '=', 'product.uom')
            ->whereRaw('UPPER(uom.uom_name) = ?', ['KG'])
            ->pluck('product.id');

        if ($kgProducts->isNotEmpty()) {
            DB::table('belt_cutting_material')
                ->whereIn('material', $kgProducts)
                ->update(['stock_factor' => 1000]);
        }
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::table('belt_cutting', function (Blueprint $table) {
            $table->dropUnique(['cutting_no']);
            $table->dropColumn(['wastage_mtr', 'total_rejected_pieces', 'cancelled_by', 'cancelled_at', 'cancel_reason']);
        });

        Schema::table('belt_cutting_item', function (Blueprint $table) {
            $table->dropColumn('rejected_pieces');
        });

        Schema::table('belt_cutting_material', function (Blueprint $table) {
            $table->dropColumn('stock_factor');
        });
    }
}
