<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductDistributionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_distributions', function (Blueprint $table) {
            $table->id();
            $table->integer('seller_id')->unsigned()->nullable();
            $table->integer('product_id')->unsigned()->nullable();
            $table->integer('stock_id')->unsigned()->nullable();
            $table->integer('branch_type_id')->unsigned()->nullable();
            $table->integer('branch_id')->unsigned()->nullable();
            $table->integer('quantity')->nullable();
            $table->date('date')->default(date('y-m-d'));
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
        Schema::dropIfExists('product_distributions');
    }
}
