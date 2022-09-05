<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductDistributionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_distribution_details', function (Blueprint $table) {
            $table->id();
            $table->integer('product_distribution_id')->unsigned();
            $table->string('user_name')->nullable();
            $table->string('category')->nullable();
            $table->string('product_name')->nullable();
            $table->string('brand')->nullable();
            $table->text('image')->nullable();
            $table->double('unit_price');
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
        Schema::dropIfExists('product_distribution_details');
    }
}
