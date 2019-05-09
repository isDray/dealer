<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use File;
use DB;
use Auth;
use Validator;
use App\User;
use App\Goods;
use App\Role;
use App\Order;
use App\OrderGoods;
use App\Article;
use App\Announcement;
use App\Multiple;
use App\Dealer;
use App\Purchase;
use App\PurchaseGoods;
use App\GoodsStock;
use App\GoodsPrice;
use App\PurchaseLog;
use App\Category;
use \Exception;

class CartController extends Controller
{
    public static $categorySelect = [];
    /*----------------------------------------------------------------
     | 購物車首頁
     |----------------------------------------------------------------
     |
     */
    public function index( Request $request){

        
        if( $request->session()->has('cartUser') ){

        	$cartUser =  $request->session()->pull('cartUser');

        }else{

        	exit;
        }
        
        // 取出經銷商資料
        $dealerDatas = $this->getDealer( $cartUser );
        if(!$dealerDatas){ exit; }

        // 取出所有分類
        $categorys = $this->getCategory();
        
        //var_dump($categorys);

        return view('cartIndex')->with([ 
        	                             'cartUser' =>$cartUser,
        	                             'dealerDatas' => $dealerDatas,
        	                             'categorys' => $categorys
                                        ]);     	
    }
    



    /*----------------------------------------------------------------
     | 商品內頁
     |----------------------------------------------------------------
     |
     */
     public function viewGoods( Request $request ){

        if( $request->session()->has('cartUser') ){

            $cartUser =  $request->session()->pull('cartUser');

        }else{

            exit;
        }
        
        // 取出經銷商資料
        $dealerDatas = $this->getDealer( $cartUser );
        if(!$dealerDatas){ exit; }

        // 取出所有分類
        $categorys = $this->getCategory();
        
        //判斷經銷商有無此商品 , 如果沒有直接跳回上一頁
        if( !$this->chkStock( $cartUser , $request->goodsId ) ){

            return back();

        }else{

            $stock = GoodsStock::where( 'dealer_id',$cartUser )->where( 'goods_id' , $request->goodsId )->first();
            $stock = $stock->toArray();
        }


        // 取出商品資訊
        $goods = Goods::find( $request->goodsId );
        $goods = $goods->toArray();
        
        // 確認經銷商有無設定價格
        $goodsPrice = GoodsPrice::where('dealer_id',$cartUser)->where('goods_id',$request->goodsId)->first();
        
        if( $goodsPrice == NULL){

            $goodsPrice = round($dealerDatas['multiple'] * $goods['w_price']);

        }else{

            $goodsPrice ->toArray();
            $goodsPrice = $goodsPrice->price;
        }
        
        $goods['dealerPrice'] = $goodsPrice;

        $goods['stock']       = $stock['goods_num'];

        return view('cartGoods')->with([ 
                                         'cartUser' =>$cartUser,
                                         'dealerDatas' => $dealerDatas,
                                         'categorys' => $categorys,
                                         'goods' => $goods
                                        ]); 
     }



    /*----------------------------------------------------------------
     | 添加至購物車
     |----------------------------------------------------------------
     |
     */
     public function addToCart( Request $request ){
        
     }
    
    /*----------------------------------------------------------------
     | 取出經銷商資料
     |----------------------------------------------------------------
     |
     */
    public function getDealer( $_dealerId ){

    	$dealerDatas = Dealer::where('dealer_id',$_dealerId)->first();

        if( $dealerDatas == NULL ){

        	return false;
        }else{

        	return $dealerDatas = $dealerDatas->toArray();
        }

        
    }



    /*----------------------------------------------------------------
     | 取出所有分類
     |----------------------------------------------------------------
     |
     */
    public function getCategory(){

        $allParents = Category::where('parent',0)->orderBy('sort', 'asc')->get();
        $allParents = $allParents->toArray();
        
        // 將categorySelect重置一次
        self::$categorySelect = [];
        
        // 取得子類別
        foreach ($allParents as $allParentk => $allParent) {
            
            $tmpChild = self::getCategoryChild( $allParent['id'] , 1 );
            
            $tmp = [ 'id'   => $allParent['id'],
                     'name' => $allParent['name'],
                     'desc' => $allParent['desc'],
                     'status' => $allParent['status'],
                     'updated_at' => $allParent['updated_at'],
                     'level' => '',
                     'levelIcon' => '',
                     'child' => $tmpChild 
                   ];

            

            
            array_push(self::$categorySelect, $tmp );

        }

        return self::$categorySelect;
    }



    public static function getCategoryChild( $_id , $_level ){
        
        // 計算各階層箭頭數
        $level = '';

        for ($i=0; $i < $_level ; $i++) { 

            $level .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

        }

        // 確認有無類別以此類別作為父類別
        if( Category::where('parent', '=', $_id)->exists() ){

            $allCategorys = Category::where('parent', '=', $_id)->orderBy('sort', 'asc')->get();
            $allCategorys = $allCategorys->toArray();
            
            $tmpArr = [];
            foreach ($allCategorys as $allCategory) {
                
                $tmpChild = self::getCategoryChild( $allCategory['id'] , $_level+1 );

                $tmp = [  'id'   => $allCategory['id'],
                          'name' => $allCategory['name'],
                          'desc' => $allCategory['desc'],
                          'status' => $allCategory['status'],
                          'updated_at' => $allCategory['updated_at'],
                          'level'=>$level,
                          'levelIcon' => '<i class="material-icons">subdirectory_arrow_right</i>',
                          'child' =>  $tmpChild
                       ];

                //array_push(self::$categorySelect, $tmp );
                array_push($tmpArr, $tmp);
                // 如果有類別以當下類別為父類別則繼續向下做遞迴
                

                

            }

            return $tmpArr;
        }
    }  




    /*----------------------------------------------------------------
     | 確認經銷商有無指定商品庫存
     |----------------------------------------------------------------
     |
     */
    public function chkStock( $_dealerId ,$_goodsId ){
        
        return GoodsStock::where( 'dealer_id',$_dealerId )->where( 'goods_id' , $_goodsId)->exists();
    }  
}
