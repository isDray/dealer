<?php
namespace App\freeHelper;

use App\Goods;
use App\GoodsPic;
/*----------------------------------------------------------------
 | 商品輔助工具
 |----------------------------------------------------------------
 | 1. 取出所有商品 - getAllGoods()
 | 2. 確認商品存在 - goodsExist()
 | 2. 取出指定商品 - getGoods()
 |
 */
Class goodsTool{
    

    // 取出所有商品
    public static function getAllGoods(){

        return Goods::get();

    }

    // 確認商品存在 : $_id => 商品id
    public static function goodsExist( $_id ){
        
        return Goods::where('id', '=', $_id)->exists();

    }

    // 取出指定商品 : $_id => 商品id 
    public static function getGoods( $_id ){

        return Goods::find( $_id );
    }

    // 取出商品相關圖片( 不包含主圖 & 縮圖 )
    public static function getGoodsPic( $_id ){

        return GoodsPic::where("gid",$_id)
                       ->orderBy('sort', 'asc')
                       ->get();

    }

    // 取出最後排序
    public static function getMaxSort( $_id ){

        return GoodsPic::where("gid",$_id)
                       ->max('sort');
    }

    // 確認商品圖片排序存在
    public static function goodsPicExist( $_gid , $_path ){

        return GoodsPic::where('gid', '=', $_gid)
                       ->where('pic', '=', $_path)
                       ->exists();
    }
}
?>