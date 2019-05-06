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
use App\PurchaseLog;
use App\GoodsPrice;
use App\GoodsCat;
use \Exception;
class ReportController extends Controller
{
    
    /*----------------------------------------------------------------
     | 銷售報表
     |----------------------------------------------------------------
     |
     */
     public function order( Request $request ){
         
        $pageTitle = '銷售報表';
        
        $dealers = Role::where('name','Dealer')->first()->users()->get();
        $dealers = $dealers->toArray();
        
        // 如果沒有接收到經銷商 , 表示要查混合報表
        if( empty($request->dealer) ){

        }
        
        // 如果開始跟結束時間都沒有 , 則表示抓當月報表
        if( empty($request->start) && empty($request->end)){

        }
        
        return view('orderReport')->with([ 'title'   => $pageTitle,
                                           'dealers' => $dealers
                                       ]);        

     }
}
