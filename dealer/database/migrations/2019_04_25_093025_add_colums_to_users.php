<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumsToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('users', function($table) {
            
            $table->char('user_name',30)->comment('註冊人姓名');
            $table->char('user_tel',20)->comment('連絡電話');
            $table->char('user_phone',20)->comment('連絡手機');
            $table->text('ship_address')->comment('預設收貨地址');
            $table->char('hostel_tel',20)->comment('旅館電話');
            $table->char('hostel_phone',20)->comment('旅館手機');
            $table->text('hostel_address')->comment('旅館地址');
            $table->text('logo1')->comment('旅館地址');
            $table->text('logo2')->comment('旅館地址');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('users', function($table) {

            $table->dropColumn('phone');

            $table->dropColumn('address');

            $table->dropColumn('user_name');

            $table->dropColumn('tel');
            
        });         
    }
}
