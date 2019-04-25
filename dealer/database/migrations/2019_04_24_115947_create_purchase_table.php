<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        /*----------------------------------------------------------------
         | 進貨單表
         |
         */
        Schema::create('purchase', function (Blueprint $table) {
            
            $table->increments('id')->comment('進貨單id');
            $table->char('purchase_sn',100)->index()->unique()->comment('進貨單編號');
            $table->integer('dealer_id')->unsigned()->comment('經銷商id');
            $table->foreign('dealer_id')->references('id')->on('users')->onDelete('cascade');   
            $table->char('dealer_name',100)->comment('經銷商名稱');
            $table->timestamp('shipdate')->nullable()->comment('出貨時間');
            $table->integer('amount')->comment('進貨單總金額');
            $table->tinyInteger('status')->comment('狀態');
            $table->timestamps();

        });




        /*----------------------------------------------------------------
         | 進貨單明細
         |
         */
        Schema::create('purchase_goods', function (Blueprint $table) {

            $table->increments('id')->comment('進貨單明細id');
            $table->integer('goods_id')->unsigned()->comment('商品id');
            $table->foreign('goods_id')->references('id')->on('goods')->onDelete('cascade'); 
            $table->char('goods_sn', 100)->comment('商品貨號');
            $table->char('goods_name', 255)->comment('商品名稱');
            $table->integer('w_price')->comment('商品批發價');
            $table->integer('num')->comment('數量');
            $table->integer('subtotal')->comment('小計');
            $table->char('purchase_sn',100)->index()->comment('進貨單編號');
            $table->timestamps();

        });




        /*----------------------------------------------------------------
         | 商品數量表
         |
         */
        Schema::create('goods_stock', function (Blueprint $table) {
            
            $table->integer('dealer_id')->unsigned()->comment('經銷商id');
            $table->foreign('dealer_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('goods_id')->unsigned()->comment('商品id');

            $table->integer('goods_num')->comment('商品數量');
            $table->primary(['dealer_id', 'goods_id']);

        });
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {   
        Schema::dropIfExists('goods_stock');
        Schema::dropIfExists('purchase_goods');
        Schema::dropIfExists('purchase');
    }
}
