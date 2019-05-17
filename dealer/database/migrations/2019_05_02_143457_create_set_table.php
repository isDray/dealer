<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        /*----------------------------------------------------------------
         | 網站設置表
         |----------------------------------------------------------------
         |
         */
        Schema::create('set', function (Blueprint $table) {

            $table->increments('id')->comment('流水號');
            $table->char('name', 100)->comment('網站名稱');
            $table->tinyInteger('show_type')->comment('呈現方式(1=表格 2列表)');
            $table->tinyInteger('sort_type')->comment('排序規則(1=上架時間 2=價格)');
            $table->tinyInteger('sort_way')->comment('排序規則(1=小到大 2=大到小)');
            $table->unsignedInteger('free_price')->comment('免運門檻');
            $table->timestamps();
        });

        /*----------------------------------------------------------------
         | 文章表
         |----------------------------------------------------------------
         |
         */        
        Schema::create('article', function (Blueprint $table) {
            $table->increments('id')->comment('流水號');
            $table->char('name', 100)->comment('文章標題');
            $table->text('content')->comment('文章內容');
            $table->tinyInteger('status')->comment('呈現方式(0=關 1=開)');
            $table->smallInteger('sort')->comment('越小越前面');
            $table->timestamps();
        });     

        /*----------------------------------------------------------------
         | 公告表
         |----------------------------------------------------------------
         |
         */        
        Schema::create('announcement', function (Blueprint $table) {

            $table->increments('id')->comment('流水號');
            $table->char('name', 100)->comment('公告標題');
            $table->text('content')->comment('公告內容');
            $table->tinyInteger('status')->comment('呈現方式(0=關 1=開)');
            $table->smallInteger('sort')->comment('越小越前面');
            $table->timestamps();

        });

        /*----------------------------------------------------------------
         | 售價級距
         |----------------------------------------------------------------
         |
         */        
        Schema::create('multiple', function (Blueprint $table) {

            $table->increments('id')->comment('流水號');
            $table->float('multiple', 8, 1);
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
        Schema::dropIfExists('multiple');
        Schema::dropIfExists('set');
        Schema::dropIfExists('article');
        Schema::dropIfExists('announcement');
    }
}
