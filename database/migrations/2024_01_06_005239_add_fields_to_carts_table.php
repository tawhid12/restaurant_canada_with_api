<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->integer('order_id')->after('id');
            /*$table->integer('price')->after('quantity');
            $table->integer('discount')->after('price');*/
            $table->integer('restaurant_id')->after('quantity');
            $table->integer('owner_id')->after('restaurant_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            /*$table->dropColumn('price');
            $table->dropColumn('discount');*/
            $table->dropColumn('restaurant_id');
            $table->dropColumn('owner_id');
        });
    }
}
