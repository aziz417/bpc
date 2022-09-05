<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVatScOhToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'vat_sc_oh')){
                $table->double('vat_sc_oh')->default(0)->after('unit_price');
            };
        });
    }

//$table->id();
//$table->integer('product_id')->unsigned()->nullable();
//$table->integer('sell_product_detail_id')->unsigned()->nullable();
//$table->integer('branch_id')->unsigned()->nullable();
//$table->integer('quantity')->nullable();
//$table->double('sub_total')->nullable();
//$table->double('in_total_bill')->nullable();
//$table->timestamps();
//
//
//
//$table->id();
//$table->integer('product_distribution_id')->unsigned()->nullable();
//$table->integer('seller_id')->unsigned()->nullable();
//$table->double('total_bill')->nullable();
//$table->double('total_vat_sc_oh_amount')->nullable();
//$table->double('in_total_bill')->nullable();
//$table->timestamps();

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['vat_sc_oh']);
        });
    }
}
