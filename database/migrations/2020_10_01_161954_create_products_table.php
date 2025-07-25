<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('brand_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->string('product_type')->nullable();
            $table->string('product_name')->nullable();
            $table->string('product_code')->nullable();
            $table->string('product_name_search')->nullable();
            $table->string('product_code_search')->nullable();
            $table->string('color_code')->nullable();
            $table->string('size')->nullable();
            $table->longText('description')->nullable();
            $table->double('maximum_retail_price')->nullable();
            $table->double('minimum_stock_quantity')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('products');
    }
}
