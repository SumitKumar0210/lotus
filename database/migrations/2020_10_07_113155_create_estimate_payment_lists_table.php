<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstimatePaymentListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estimate_payment_lists', function (Blueprint $table) {
            $table->id();
            $table->integer('estimate_id')->nullable();
            $table->double('paid_in_cash')->nullable();
            $table->double('paid_in_bank')->nullable();
            $table->double('total_paid')->nullable();
            $table->string('date_time')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estimate_payment_lists');
    }
}
