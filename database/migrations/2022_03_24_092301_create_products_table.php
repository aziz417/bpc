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
            $table->integer('user_id')->unsigned();
            $table->integer('category_id')->unsigned();
            $table->string('name');
            $table->string('title');
            $table->string('brand');
            $table->text('image')->nullable();
            $table->text('description')->nullable();
            $table->text('specification')->nullable();
            $table->double('unit_price');
            $table->tinyInteger('publish');
            $table->tinyInteger('feature');
            $table->tinyInteger('status');
            $table->date('date');
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
        Schema::dropIfExists('products');
    }
}
