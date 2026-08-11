<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateBeltCostingFittingTable extends Migration
{
    /**
     * Everything a finished belt takes besides its niwar, as a list rather than a
     * fixed set of columns.
     *
     * Bukkal, kadi and panni went in as three named columns, which was already
     * one short the moment a slider appeared - and a belt can equally take a
     * rivet, a label or packaging. A list costs nothing and stops the next
     * component needing a migration.
     *
     * The three columns are migrated in and then left alone; dropping them would
     * take the costing rates with them.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('belt_costing_fitting', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('belt_costing_id');
            $table->string('label')->nullable();
            $table->integer('product');
            $table->double('qty')->default(1);
            $table->timestamps();

            $table->index('belt_costing_id');
            $table->index('product');
        });

        $this->migrateFixedColumns();
    }

    /**
     * Whatever the three columns already name becomes the first three rows.
     */
    private function migrateFixedColumns()
    {
        if (!Schema::hasColumn('belt_costings', 'bukkal_product_id')) {
            return;
        }

        foreach (DB::table('belt_costings')->get() as $costing) {
            $lines = [
                ['label' => 'Bukkal', 'product' => $costing->bukkal_product_id, 'qty' => $costing->bukkal_qty ?? 1],
                ['label' => 'Kadi', 'product' => $costing->kadi_product_id, 'qty' => $costing->kadi_qty ?? 0],
                ['label' => 'Panni', 'product' => $costing->panni_product_id, 'qty' => $costing->panni_packing ?? 0],
            ];

            foreach ($lines as $line) {
                if (empty($line['product']) || $line['qty'] <= 0) {
                    continue;
                }

                DB::table('belt_costing_fitting')->insert([
                    'belt_costing_id' => $costing->id,
                    'label' => $line['label'],
                    'product' => $line['product'],
                    'qty' => $line['qty'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('belt_costing_fitting');
    }
}
