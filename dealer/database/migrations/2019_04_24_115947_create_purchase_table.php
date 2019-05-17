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
            $table->unsignedInteger('ship_fee')->comment('運費');
            $table->unsignedInteger('final_amount')->comment('應付金額');
            $table->tinyInteger('status')->comment('狀態');
            $table->char('consignee',100)->comment('收件人名稱');
            $table->char('tel',20)->nullable()->comment('連絡電話');
            $table->char('phone',20)->comment('連絡手機');
            $table->text('address')->comment('地址');
            $table->text('admin_note')->nullable()->comment('系統方備註');
            $table->text('dealer_note')->nullable()->comment('經銷商備註');            
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
            $table->char('purchase_id',100)->index()->comment('進貨單編號');
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

            $table->timestamps();
            $table->primary(['dealer_id', 'goods_id']);

        });



        /*----------------------------------------------------------------
         | 進貨單操作紀錄
         |
         */
        Schema::create('purchase_log', function (Blueprint $table) {

            $table->increments('id')->comment('logId');
            $table->integer('user_id')->index()->comment('操作人員id');
            $table->char('user_name', 30)->comment('操作人員姓名');
            $table->char('user_role', 30)->comment('操作人員腳色');
            $table->integer('purchase_id')->index()->comment('操作訂單id');
            $table->tinyInteger('purchase_status')->comment('狀態代碼紀錄');
            $table->char('purchase_status_text')->comment('狀態紀錄');
            $table->text('desc')->nullable()->comment('操作描述');
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
        Schema::dropIfExists('purchase_log');
        Schema::dropIfExists('goods_stock');
        Schema::dropIfExists('purchase_goods');
        Schema::dropIfExists('purchase');
    }
}
