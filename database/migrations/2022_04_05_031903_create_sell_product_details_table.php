<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellProductDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sell_product_details', function (Blueprint $table) {

            $table->id();
            $table->integer('product_distribution_id')->unsigned()->nullable();
            $table->integer('sell_detail_id')->unsigned()->nullable();
            $table->integer('product_id')->unsigned()->nullable();
            $table->integer('quantity')->nullable();
            $table->double('sub_total')->nullable();
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
        Schema::dropIfExists('sell_product_details');
    }
}
