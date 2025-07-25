<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id')->nullable();
            $table->integer('branch_in')->nullable();
            $table->integer('branch_out')->nullable();
            $table->integer('qty')->nullable();
            $table->string('date')->nullable();
            $table->string('type')->nullable();
            $table->string('status')->nullable();
            $table->string('reason')->nullable();


            $table->string('bill_number')->nullable();
            $table->string('vendor_name')->nullable();
            $table->string('remarks')->nullable();


            $table->string('purchase_no')->nullable();
            $table->string('approve_status')->nullable();
            $table->string('transfer_no')->nullable();

            $table->integer('accepted_qty')->nullable();
            $table->string('accepted_date')->nullable();

            $table->integer('branch_user_out')->nullable();
            $table->integer('branch_user_in')->nullable();
            $table->string('branch_user_in_date')->nullable();
            $table->string('is_returned')->nullable();
            $table->string('return_reason')->nullable();





            $table->string('remark')->nullable();
            //$table->integer('branch_decline_user')->nullable();
            //$table->string('branch_decline_date')->nullable();
            //$table->string('branch_decline_remark')->nullable();
            $table->integer('branch_return_user')->nullable();
            $table->string('branch_return_date')->nullable();
            $table->integer('branch_transfered_return_user')->nullable();
            $table->string('branch_transfered_return_date')->nullable();
            $table->integer('branch_transfered_return_branch_in')->nullable();
            $table->string('is_last_purchase')->nullable();

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
        Schema::dropIfExists('stocks');
    }
}
