<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumsToPurchase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('purchase', function($table) {

            $table->char('phone',20)->comment('連絡手機');

            $table->text('address')->comment('地址');

            $table->text('admin_note')->comment('系統方備註');

            $table->text('dealer_note')->comment('經銷商備註');

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
        Schema::table('purchase', function($table) {

            $table->dropColumn('phone');

            $table->dropColumn('address');

            $table->dropColumn('admin_note');

            $table->dropColumn('dealer_note');
            
        });        

    }
}
