<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;

use Illuminate\Support\Facades\Hash;
use File;
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
use App\OrderLog;
use \Exception;
use Excel;
use App\GoodsPic;
use App\GoodsCat;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        if( Auth::user()->hasRole('Admin') ){
            
            // 取出待處理裡訂單
            $pendingNum = Order::where('status',2)->count();
            // 取出已完成訂單數量
            $doneNum = Order::where('status',4)->count();    
            // 已確認數量
            $checkedNum = Order::where('status',3)->count(); 

            // 可用商品數
            $useGoodsNum =  Goods::where('status',1)->count();
            
            // 停用商品數
            $stopGoodsNum =  Goods::where('status',0)->count();

            // 低庫存商品
            $lowStaocks = GoodsStock::selectRaw(" sum(goods_num) AS goodsTotal ")->groupBy('goods_id')->get();
            $lowStaockNum = 0;
            if( count($lowStaocks) != 0 ){
                foreach ($lowStaocks as $lowStaock) {
                    if($lowStaock['goodsTotal'] <= 1){
                        $lowStaockNum += 1;
                    }
                }
            }
            

            return view('home')->with(['title'=>'管理者儀表',
                                       'pendingNum'=> $pendingNum,
                                       'doneNum'   => $doneNum,
                                       'useGoodsNum'=> $useGoodsNum,
                                       'stopGoodsNum'=>$stopGoodsNum,
                                       'lowStaockNum'=>$lowStaockNum,
                                       'checkedNum'=>$checkedNum
                                      ]);

        }elseif( Auth::user()->hasRole('Dealer') ){

            // 取出待處理裡訂單
            $pendingNum = Order::where('dealer_id',Auth::id())->where('status',2)->count();

            // 取出已完成訂單數量
            $doneNum = Order::where('dealer_id',Auth::id())->where('status',4)->count();

            $checkedNum = Order::where('dealer_id',Auth::id())->where('status',3)->count();
            // 可用商品數
            $useGoodsNum =  Goods::leftJoin('goods_stock', function($join) {
                                $join->on('goods.id', '=', 'goods_stock.goods_id');
                            })->where("goods.status",1)->where('goods_stock.goods_num','>',0)->where('goods_stock.dealer_id',Auth::id())->count();

            
            // 無庫存商品數

            // 全部商品數
            $allgoods = Goods::where("goods.status",1)->count();
            
            // 經銷商有紀錄庫存的數目
            $allDealerGoods = GoodsStock::where('dealer_id',Auth::id())->count();
            
            // 經銷商有庫存但是庫存為零的數目
            $zeroDealerGoods = GoodsStock::where('dealer_id',Auth::id())->where('goods_num' , '<' , 1)->count();

            $noStockGoodsNum = ( $allgoods - $allDealerGoods ) + $zeroDealerGoods;


            // 低庫存商品
            $lowStaockNum = GoodsStock::where('dealer_id',Auth::id())->where('goods_num', '<=' , 1)->count();

            return view('home')->with(['title'=>'經銷商儀表',
                                       'pendingNum'=> $pendingNum,
                                       'doneNum'   => $doneNum,
                                       'useGoodsNum'=> $useGoodsNum,
                                       'noStockGoodsNum'=>$noStockGoodsNum,
                                       'lowStaockNum'=>$lowStaockNum,
                                       'checkedNum'=>$checkedNum

                                      ]);
        }
        
    }
}
