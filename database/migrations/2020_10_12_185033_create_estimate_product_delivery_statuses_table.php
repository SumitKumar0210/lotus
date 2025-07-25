<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstimateProductDeliveryStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estimate_product_delivery_statuses', function (Blueprint $table) {
            $table->id();
            $table->integer('estimate_product_list_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('qty')->nullable();
            $table->string('delivery_status')->nullable();
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
        Schema::dropIfExists('estimate_product_delivery_statuses');
    }
}
