<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->double('total',8,2)->nullable()->default(0.00);
            $table->double('discount',8,2)->nullable()->default(0.00);
            $table->double('delivery_fee',8,2)->nullable()->default(0.00);
            $table->double('payable',8,2)->nullable()->comment('Total-discount=total+deliveryfee')->default(0.00);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('restaurant_id');
            $table->integer('owner_id');
            $table->string('status')->nullable();
            $table->string('method')->comment('1=>Cash')->default();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
