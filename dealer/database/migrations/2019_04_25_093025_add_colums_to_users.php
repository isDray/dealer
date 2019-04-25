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
            
            $table->char('user_name',30)->comment('會員姓名');
            $table->char('user_phone',20)->comment('連絡手機');
            $table->char('user_tel',20)->comment('連絡電話');

            $table->char('ship_name',30)->comment('收貨人');
            $table->char('ship_phone',20)->comment('收貨人手機');
            $table->char('ship_tel',20)->comment('收貨人電話');
            $table->text('ship_address')->comment('收貨地址');

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

            $table->dropColumn('user_name'); 
            $table->dropColumn('user_phone'); 
            $table->dropColumn('user_tel');

            $table->dropColumn('ship_name'); 
            $table->dropColumn('ship_phone'); 
            $table->dropColumn('ship_tel');
            $table->dropColumn('ship_address');  
        });         
    }
}
