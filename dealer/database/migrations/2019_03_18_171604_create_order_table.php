<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        /*----------------------------------------------------------------
         | 訂單表
         |
         |
         */
        Schema::create('order', function (Blueprint $table) {
            
            $table->increments('id')->comment('訂單id');
            $table->integer('dealer_id')->index()->comment('經銷商ID');
            $table->char('order_sn',100)->index()->comment('訂單編號');
            $table->char('room',100)->nullable()->comment('購買房號');
            $table->integer('amount')->comment('總價');
            $table->tinyInteger('status')->comment('狀態');
            $table->timestamp('ship_at')->nullable();
            $table->tinyInteger('source')->comment('來源');
            $table->text('note')->nullable()->comment('備註');
            $table->boolean('is_new')->comment('是否為新增階段');
            $table->timestamps();
        });        

        


        /*----------------------------------------------------------------
         | 訂單詳細
         |
         */
         
        Schema::create('order_goods', function (Blueprint $table) {
            
            $table->primary(['oid', 'gid']);
            
            $table->integer('oid')->unsigned()->comment('訂單id');
            $table->foreign('oid')->references('id')->on('order')->onDelete('cascade');   
            
            $table->integer('gid')->unsigned()->comment('商品id');
            //$table->foreign('gid')->references('id')->on('goods')->onDelete('cascade');           

            $table->char('goods_sn', 100)->comment('貨號');

            $table->char('name', 255)->comment('商品名稱');

            $table->integer('price')->comment('當下售價');
            
            $table->integer('num')->comment('購買數量');
            
            $table->integer('subtotal')->comment('小計');
            
        });




        /*----------------------------------------------------------------
         | 訂單操作log
         |
         */
        Schema::create('order_log', function (Blueprint $table) {

            $table->increments('id')->comment('logId');
            $table->integer('user_id')->index()->comment('操作人員id');
            $table->char('user_name', 30)->comment('操作人員姓名');
            $table->char('user_role', 30)->comment('操作人員腳色');
            $table->integer('order_id')->index()->comment('操作訂單id');
            $table->tinyInteger('order_status')->comment('訂單狀態紀錄');
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
        Schema::dropIfExists('order_goods');
        Schema::dropIfExists('order');
        Schema::dropIfExists('order_log');
    }
}
