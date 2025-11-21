<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\product;
class AddSlugColumnToProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1️⃣ Add 'slug' column first if it doesn’t exist
        Schema::table('product', function (Blueprint $table) {
            if (!Schema::hasColumn('product', 'slug')) {
                $table->string('slug')->nullable()->after('product_name');
            }
        });

        // 2️⃣ Generate and update slugs for existing records
            product::all()->each(function ($product) {
            $slug = Str::slug($product->product_name);

            // Ensure slug is unique
            $originalSlug = $slug;
            $counter = 1;
            while (product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }

            $product->slug = $slug;
            $product->saveQuietly(); // avoids triggering events
        });

        // 3️⃣ Make slug unique after data is populated
        Schema::table('product', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    public function down()
    {
        Schema::table('product', function (Blueprint $table) {
            $table->dropUnique(['slug']);
        });
    }
}
