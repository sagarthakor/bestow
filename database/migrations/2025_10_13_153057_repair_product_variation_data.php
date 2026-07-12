<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RepairProductVariationData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Clear existing variants
        DB::table('product_variants')->truncate();

// Preload variations for faster lookups
        $variations = DB::table('variation')->pluck('id', 'variation_name'); // ['Red' => 1, 'Blue' => 2, 'XL' => 10, ...]

        $products = DB::table('product')->get();

        $insertData = [];

        foreach ($products as $p) {
            $colorId = $variations[$p->value1] ?? null;
            $sizeId  = $variations[$p->value2] ?? null;

            // Generate SKU using the same logic as your example
            $sku = Str::slug($p->item_code . '-' . ($colorId ?? 'NA') . '-' . ($sizeId ?? 'NA'), '-');

            $insertData[] = [
                'product_id'  => $p->id,
                'color_id'    => $colorId,
                'size_id'     => $sizeId,
                'price'       => $p->price,
                'sku'         => $sku,
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

// Bulk insert all at once
        DB::table('product_variants')->insert($insertData);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
