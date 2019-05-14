<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        // 建構分類表( 由於有關聯鍵 , 所以分類表一定要先產生 )
        Schema::create('category', function (Blueprint $table) {
            
            $table->increments('id');
            $table->char('name', 100)->comment('分類名稱');
            $table->integer('parent')->unsigned()->comment('父類別');
            $table->char('keyword', 255)->comment('關鍵字');
            $table->char('desc', 255)->comment('分類描述');
            $table->boolean('status')->comment('是否啟用');
            $table->smallInteger('sort')->comment('排序');

            $table->timestamps();
        });
        
        
        // 商品表
        Schema::create('goods', function (Blueprint $table) {

            $table->increments('id');
            $table->char('goods_sn', 100)->comment('貨號');
            $table->char('name', 255)->comment('商品名稱');
            // 分類 ( 關聯鍵 , 參考分類)
            $table->integer('cid')->unsigned();
            /*$table->foreign('cid')->references('id')->on('category');*/

            $table->char('thumbnail', 191)->comment('縮圖');
            $table->char('main_pic', 191)->comment('主圖');

            $table->integer('price')->comment('售價');
            $table->integer('w_price')->comment('批發價');
            $table->boolean('status')->comment('上下架');
            $table->text('desc')->comment('商品描述');
            $table->timestamp('on_date')->nullable()->comment('上架時間');
            $table->timestamp('off_date')->nullable()->comment('下架時間');
            $table->boolean('recommend')->default(0)->comment('推薦商品');
            $table->timestamps();

        });
        
        // 商品圖擴張表
        Schema::create('goods_pic', function (Blueprint $table) {

            $table->integer('gid')->unsigned()->comment('商品id');
            $table->foreign('gid')->references('id')->on('goods')->onDelete('cascade');

            $table->char('pic', 191)->comment('圖片');
            $table->integer('sort')->comment('排序');
            $table->primary(['gid', 'pic']);

        });     

        // 擴展分類表( 為了讓商品可以跨類別所以需要多一張擴張表 )
        Schema::create('goods_cat', function (Blueprint $table) {

            $table->integer('gid')->unsigned()->comment('商品id');
            $table->foreign('gid')->references('id')->on('goods')->onDelete('cascade');
            
            $table->integer('cid')->unsigned()->comment('分類id');
            /*$table->foreign('cid')->references('id')->on('category')->onDelete('cascade');*/
            
            $table->primary(['gid', 'cid']);

        });        

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {   Schema::dropIfExists('goods_cat');
        Schema::dropIfExists('goods_pic');
        Schema::dropIfExists('goods');
        Schema::dropIfExists('category');
    }
}

// goods.php