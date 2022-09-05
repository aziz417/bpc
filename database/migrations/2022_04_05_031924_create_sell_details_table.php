<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sell_details', function (Blueprint $table) {
            $table->id();
            $table->integer('branch_id')->unsigned()->nullable();
            $table->integer('seller_id')->unsigned()->nullable();
            $table->double('total_bill')->nullable();
            $table->double('total_vat_sc_oh_amount')->nullable();
            $table->double('in_total_bill')->nullable();
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
        Schema::dropIfExists('sell_details');
    }
}
