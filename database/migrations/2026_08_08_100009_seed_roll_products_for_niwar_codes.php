<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SeedRollProductsForNiwarCodes extends Migration
{
    /**
     * Creates the size-less semi-finished roll product behind every Niwar Code.
     * Roll stock lives in the ordinary product/stock_status tables (in meters) so
     * every existing stock screen and report picks it up for free - the only thing
     * that makes it "size-less" is that value2 stays empty.
     *
     * Idempotent: re-running only fills in what is missing.
     *
     * @return void
     */
    public function up()
    {
        $uomId = $this->meterUomId();
        $categoryId = $this->rollCategoryId();

        $codes = DB::table('niwar_codes')->whereNull('roll_product_id')->get();

        foreach ($codes as $code) {
            $label = trim($code->type . '/' . $code->code);
            $name = 'NIWAR ROLL ' . $label;

            // A roll product may already exist from a half-finished earlier run.
            $productId = DB::table('product')
                ->where('product_name', $name)
                ->where('status', 'semi finished')
                ->value('id');

            if (!$productId) {
                $productId = DB::table('product')->insertGetId([
                    'product_name' => $name,
                    'slug' => Str::slug($name),
                    'item_code' => 'ROLL-' . preg_replace('/[^A-Z0-9]/i', '', $label),
                    'uom' => $uomId,
                    'category' => $categoryId,
                    'category_name' => 'Niwar Roll',
                    'status' => 'semi finished',
                    'website_id' => 1,
                    'show_hide' => 'hide',
                    'price_show_hide' => 'hide',
                    'created_time' => date('d-m-Y h:i:s a'),
                ]);
            }

            DB::table('niwar_codes')->where('id', $code->id)->update(['roll_product_id' => $productId]);
        }
    }

    private function meterUomId()
    {
        $id = DB::table('uom')->whereRaw('LOWER(uom_name) IN (?, ?)', ['meter', 'mtr'])->value('id');

        return $id ?: DB::table('uom')->insertGetId([
            'uom_name' => 'Meter',
            'website_id' => 1,
            'user_id' => 1,
        ]);
    }

    private function rollCategoryId()
    {
        $id = DB::table('category')->where('category_name', 'Niwar Roll')->value('id');

        return $id ?: DB::table('category')->insertGetId([
            'category_name' => 'Niwar Roll',
            'slug' => 'niwar-roll',
            'website_id' => 1,
            'user_id' => 1,
        ]);
    }

    /**
     * Only unlinks the codes - the roll products themselves are left alone since
     * by then they may already carry stock and ledger history.
     *
     * @return void
     */
    public function down()
    {
        DB::table('niwar_codes')->update(['roll_product_id' => null]);
    }
}
