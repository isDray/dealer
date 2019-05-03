<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDealerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        /*----------------------------------------------------------------
         | 經銷商相關資料
         |----------------------------------------------------------------
         |
         */
        Schema::create('dealer', function (Blueprint $table) {
            
            $table->increments('id');
            
            $table->integer('dealer_id')->unsigned()->comment('經銷商id');
            $table->foreign('dealer_id')->references('id')->on('users')->onDelete('cascade'); 

            $table->char('hotel_name', 100)->nullable()->comment('旅館名稱');
            $table->text('web_url')->nullable()->comment('經銷商網站');
            $table->char('hotel_phone',20)->nullable()->comment('旅館手機');
            $table->char('hotel_tel',20)->comment('旅館電話');
            $table->text('hotel_address')->nullable()->comment('旅館地址');

            $table->char('user_name',30)->nullable()->comment('會員姓名');
            $table->char('user_phone',20)->nullable()->comment('連絡手機');
            $table->char('user_tel',20)->nullable()->comment('連絡電話');

            $table->char('ship_name',30)->nullable()->comment('收貨人');
            $table->char('ship_phone',20)->nullable()->comment('收貨人手機');
            $table->char('ship_tel',20)->nullable()->comment('收貨人電話');
            $table->text('ship_address')->nullable()->comment('收貨地址');

            $table->text('logo1')->nullable()->comment('網頁版logo');
            $table->text('logo2')->nullable()->comment('手機版logo');
            $table->float('multiple', 8, 1)->comment('預設倍數');
            
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
        Schema::dropIfExists('dealer');
    }
}
