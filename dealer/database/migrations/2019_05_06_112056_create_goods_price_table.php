<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_price', function (Blueprint $table) {
            
            $table->integer('dealer_id')->unsigned()->comment('經銷商id');
            
            $table->integer('goods_id')->unsigned()->comment('商品id');

            $table->foreign('dealer_id')->references('id')->on('users')->onDelete('cascade');

            $table->foreign('goods_id')->references('id')->on('goods')->onDelete('cascade');
            
            $table->integer('multiple_id')->unsigned()->comment('倍數id');
            
            $table->float('multiple', 8, 1)->comment('倍數');
            
            $table->integer('price')->unsigned()->comment('價格');

            $table->primary(['dealer_id', 'goods_id']);

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
        Schema::dropIfExists('goods_price');
    }
}
