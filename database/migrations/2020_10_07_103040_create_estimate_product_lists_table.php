<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstimateProductListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estimate_product_lists', function (Blueprint $table) {
            $table->id();
            $table->integer('estimate_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('product_type')->nullable();
            $table->string('product_name')->nullable();
            $table->string('product_code')->nullable();
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->integer('qty')->nullable();
            $table->double('mrp')->nullable();
            $table->double('amount')->nullable();
            $table->string('delivery_status')->default('NOT DELIVERED');
            $table->string('delivery_date')->nullable();

            $table->string('is_sale_returned')->nullable();
            $table->integer('sale_returned_qty')->nullable();

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
        Schema::dropIfExists('estimate_product_lists');
    }
}
