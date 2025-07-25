<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('branch_name')->nullable();
            $table->longText('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('type')->nullable();
            $table->string('print_slug')->nullable();
            $table->string('purchase_permission')->nullable();
            $table->string('product_permission')->nullable();
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
        Schema::dropIfExists('branches');
    }
}
