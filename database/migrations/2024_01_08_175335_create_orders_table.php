<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
             $table->bigIncrements('id');
            // other order fields...
			$table->unsignedBigInteger('user_id');
            $table->text('cart');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->integer('restaurant_id');
            $table->integer('owner_id');
            $table->unsignedBigInteger('order_status_id')->index();
            $table->foreign('order_status_id')->references('id')->on('order_statuses')->onDelete('cascade');
            $table->double('tax',8,2)->nullable()->default(0.00);
            $table->double('total',8,2)->nullable()->default(0.00);
            $table->double('discount',8,2)->nullable()->default(0.00);
            $table->double('delivery_fee',8,2)->nullable()->default(0.00);
            $table->double('payable',8,2)->nullable()->comment('Total-discount=total+deliveryfee')->default(0.00);
            $table->text('hint')->nullable()->default('');
            $table->tinyInteger('active')->default(1);
            $table->unsignedBigInteger('driver_id')->index()->nullable();
            $table->foreign('driver_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('delivery_address_id')->index()->nullable();
            $table->foreign('delivery_address_id')->references('id')->on('delivery_addresses')->onDelete('cascade');
            // Foreign key relationship with payments table
            $table->unsignedBigInteger('payment_id')->index()->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
            
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
        Schema::dropIfExists('orders');
    }
}
