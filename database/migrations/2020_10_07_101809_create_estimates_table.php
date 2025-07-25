<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstimatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estimates', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('branch_id')->nullable();
            $table->string('client_mobile')->nullable();
            $table->string('client_name')->nullable();
            $table->string('client_address')->nullable();
            $table->string('client_email')->nullable();
            $table->string('estimate_no')->nullable();
            $table->string('estimate_date')->nullable();
            $table->string('expected_delivery_date')->nullable();
            $table->string('delivered_date')->nullable();
            $table->longText('remarks')->nullable();
            $table->double('sub_total')->nullable();
            $table->double('discount_percent')->nullable();
            $table->double('discount_value')->nullable();
            $table->double('freight_charge')->nullable();
            $table->double('misc_charge')->nullable();
            $table->double('grand_total')->nullable();
            $table->double('dues_amount')->nullable();
            $table->string('delivered_by')->nullable();
            $table->string('is_admin_approved')->nullable();
            $table->string('estimate_status')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('delivery_status')->nullable();
            $table->string('delivery_status_ready_product')->nullable();
            $table->string('delivery_status_order_to_make')->nullable();
            $table->string('sale_by')->nullable();
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
        Schema::dropIfExists('estimates');
    }
}
