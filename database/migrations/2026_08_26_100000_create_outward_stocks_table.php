<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutwardStocksTable extends Migration
{
    /**
     * Manual stock-out document (adjustment, damage, sample, etc.) - the
     * counterpart to Inward. The actual balance change still goes through
     * stock_status/stock_book via BeltStockMovement; this table only keeps
     * the document trail (who took what out, and why).
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outward_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('doc_no')->nullable();
            $table->date('date')->nullable();
            $table->string('reason')->nullable();
            $table->text('remark')->nullable();
            $table->string('status')->default('Y');
            $table->bigInteger('cancelled_by')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancel_reason')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('outward_stocks');
    }
}
