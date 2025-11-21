<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id'); // matches product.id
            $table->unsignedBigInteger('color')->nullable(); // FK to variation.id
            $table->unsignedBigInteger('size')->nullable();  // FK to variation.id
            $table->double('price')->nullable();
            $table->string('sku')->nullable();
            $table->string('image')->nullable();
            $table->integer('quantity')->default(0);
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('product')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};

?>
