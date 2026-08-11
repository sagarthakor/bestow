<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddActualConsumptionToBeltRollProduction extends Migration
{
    /**
     * Three things a roll batch could not record until now:
     *
     *  - which version of the roll formula it was actually woven on;
     *  - what the floor really consumed, as opposed to what the formula planned;
     *  - who completed or cancelled it, and when.
     *
     * The material rows also freeze the gm/meter rate and the stock-unit factor
     * they were issued at, so a reversal puts back exactly what was taken and a
     * later edit to the formula or to a product's UOM cannot distort history.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('belt_roll_production', function (Blueprint $table) {
            $table->unsignedInteger('roll_formula_version')->nullable()->after('roll_formula_id');
            $table->bigInteger('completed_by')->nullable()->after('status');
            $table->dateTime('completed_at')->nullable()->after('completed_by');
            $table->bigInteger('cancelled_by')->nullable()->after('completed_at');
            $table->dateTime('cancelled_at')->nullable()->after('cancelled_by');
            $table->string('cancel_reason')->nullable()->after('cancelled_at');
        });

        Schema::table('belt_roll_production_material', function (Blueprint $table) {
            $table->decimal('gm_per_meter', 12, 4)->nullable()->after('material');
            $table->string('category_name')->nullable()->after('gm_per_meter');
            $table->double('actual_qty')->nullable()->after('required_qty');
            // grams per stock unit at issue time (1000 for a KG-tracked dhaga).
            $table->double('stock_factor')->default(1)->after('actual_qty');
        });

        $this->backfill();

        // Two batches sharing a batch number would make the stock ledger
        // ambiguous, and max(id)+1 numbering can produce exactly that.
        Schema::table('belt_roll_production', function (Blueprint $table) {
            $table->unique('batch_no');
        });
    }

    /**
     * Existing batches were all woven on version 1 of their formula, at the
     * planned quantity, and were completed by whoever created them.
     */
    private function backfill()
    {
        DB::table('belt_roll_production')->whereNotNull('roll_formula_id')->update(['roll_formula_version' => 1]);

        DB::table('belt_roll_production')
            ->where('status', 'Y')
            ->update([
                'completed_by' => DB::raw('user_id'),
                'completed_at' => DB::raw('updated_at'),
            ]);

        // Planned is the only consumption figure old batches ever had.
        DB::table('belt_roll_production_material')->update(['actual_qty' => DB::raw('required_qty')]);

        $factors = DB::table('product')
            ->join('uom', 'uom.id', '=', 'product.uom')
            ->whereRaw('UPPER(uom.uom_name) = ?', ['KG'])
            ->pluck('product.id');

        if ($factors->isNotEmpty()) {
            DB::table('belt_roll_production_material')
                ->whereIn('material', $factors)
                ->update(['stock_factor' => 1000]);
        }

        // The gm/meter each row was issued at, recovered from the quantity and
        // the meters it was planned against.
        DB::statement(
            'UPDATE belt_roll_production_material m
                JOIN belt_roll_production b ON b.id = m.belt_roll_production_id
                SET m.gm_per_meter = ROUND(m.required_qty / b.planned_mtr, 4)
                WHERE b.planned_mtr > 0'
        );
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::table('belt_roll_production', function (Blueprint $table) {
            $table->dropUnique(['batch_no']);
            $table->dropColumn([
                'roll_formula_version', 'completed_by', 'completed_at',
                'cancelled_by', 'cancelled_at', 'cancel_reason',
            ]);
        });

        Schema::table('belt_roll_production_material', function (Blueprint $table) {
            $table->dropColumn(['gm_per_meter', 'category_name', 'actual_qty', 'stock_factor']);
        });
    }
}
