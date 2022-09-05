<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSellerTypeAdminSaleApproveSellDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sell_details', function (Blueprint $table) {
            $table->string('seller_type')->default('seller');
            $table->tinyInteger('admin_sale_approve')->default(0);
            $table->integer('admin_sale_get_amount')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sell_details', function (Blueprint $table) {
            $table->dropColumn(['seller_type', 'admin_sale_approve', 'admin_sale_get_amount']);
        });
    }
}
