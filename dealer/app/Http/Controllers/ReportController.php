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
         
        $pageTitle = '出貨統計';
        
        // 如果是系統方 , 確認權限
        if( Auth::user()->hasRole('Admin') ){
            
            if( !Auth::user()->can('reportList') ){

                return back()->with('errorMsg', '帳號無此操作權限 , 如有需要請切換帳號或聯絡管理員增加權限' );
            }
        }

        // 取出所有經銷商會員
        $dealers = Role::where('name','Dealer')->first()->users()->get();
        $dealers = $dealers->toArray();
        
        // 查詢
        $query = DB::table('order');

        // 如果有接收到指定經銷商 , 則表示要找單一會員的訂單
        $dealer_id = 0;

        if( !empty($request->dealerId) ){
            
            if( Auth::user()->hasRole('Admin') ){    
                
                $query->where( 'dealer_id' , $request->dealerId );
                $dealer_id = $request->dealerId;
            }else{

                $query->where( 'dealer_id' , Auth::id() );
                $dealer_id = Auth::id();
            }
               
        }else{

            if( Auth::user()->hasRole('Dealer') ){
                $query->where( 'dealer_id' , Auth::id() ); 
                $dealer_id = Auth::id();               
            }
        }
        $dateStart = '';
        $dateEnd   = '';

        // 如果開始跟結束時間都沒有 , 則表示抓當月報表
        if( empty($request->start) && empty($request->end) ){

            $dateStart = date('Y-m-01 00:00:00');

            $dateEnd   = date('Y-m-d 23:59:59', strtotime(date('Y-m-01') . ' +1 month -1 day'));
            
            $query->where('created_at','>=',$dateStart)->where('created_at','<=',$dateEnd);

            $dateStart = date('Y-m-01');
            $dateEnd   = date('Y-m-d', strtotime(date('Y-m-01') . ' +1 month -1 day'));
        }else{

            if( !empty($request->start) ){

                $dateStart = $request->start." 00:00:00";
                $query->where('created_at','>=',$dateStart);
                $dateStart = $request->start;
            }
            
            if( !empty($request->end) ){

                $dateEnd = $request->end." 23:59:59";
                $query->where('created_at','<=',$dateEnd);
                $dateEnd = $request->end;
            }
        }

        // 所有訂單
        $allDatas  = $query->get();
        
        // 已出貨訂單
        $shipDatas = $query->where('status',4)->get();
            
        // 全部轉換為陣列格式
        $allDatas  = json_decode($allDatas,true);
        $shipDatas = json_decode($shipDatas,true);
        
        // 時間區間內訂單數量
        $totalOrderNum = count( $allDatas );
        $totalShipOrderNum = count( $shipDatas );
        $totalUnshipOrderNum = abs($totalShipOrderNum - $totalOrderNum);

        // 計算銷售總金額
        $totalPrice   = 0;
        $totalW_price = 0;
        

        $details = [];
        
        // 迴圈加總
        foreach ($shipDatas as $key => $shipData) {
            
            $totalPrice   += $shipData['final_amount'];
            
            //
            $tmpRes = DB::table('order')
            ->select('order.id', 'goods.w_price' , 'order_goods.num' , 'goods.goods_sn' , 'goods.name')
            ->leftJoin('order_goods', 'order.id', '=', 'order_goods.oid')
            ->leftJoin('goods', 'order_goods.gid', '=', 'goods.id')
            ->where( 'order.id' ,$shipData['id'])
            ->get();

            $tmpRes = json_decode($tmpRes,True);

            foreach ($tmpRes as $tmpRe) {

                $totalW_price += $tmpRe['w_price'] * $tmpRe['num'];

                if( array_key_exists( $tmpRe['goods_sn'] , $details ) ){
                    
                    $details[$tmpRe['goods_sn']]['num'] += $tmpRe['num'];

                }else{

                    $details[$tmpRe['goods_sn']]['name'] = $tmpRe['name'];

                    $details[$tmpRe['goods_sn']]['num']  = $tmpRe['num'];
                }
            }
        }
        
        // 利潤( 只有經銷商才需要呈現 )
        $profit = ( $totalPrice - $totalW_price < 0)? 0 : ($totalPrice - $totalW_price);
        
        // 長條圖所需資料整理
        $loopDate = $dateStart;
        
        $dateLabels  = [];
        $perDateNum  = [];

        while( $loopDate <= $dateEnd ){

            $queryBar = DB::table('order');

            if( !empty($request->dealerId) ){
                
                if( Auth::user()->hasRole('Admin') ){    
                    
                    $queryBar->where( 'dealer_id' , $request->dealerId );
                    $dealer_id = $request->dealerId;
                }else{
    
                    $queryBar->where( 'dealer_id' , Auth::id() );
                    $dealer_id = Auth::id();
                }
                   
            }else{
    
                if( Auth::user()->hasRole('Dealer') ){
                    $queryBar->where( 'dealer_id' , Auth::id() );                
                }
            }
            array_push($dateLabels, "'$loopDate'");

            $queryBar->where('created_at','>=',$loopDate );
            $queryBar->where('created_at','<',date('Y-m-d', strtotime($loopDate. ' + 1 days')) );
            $tmpCount = $queryBar->where('status',3)->count();
            
            array_push($perDateNum, $tmpCount);

            $loopDate = date('Y-m-d', strtotime($loopDate. ' + 1 days'));


        }

        $dateLabelsStr   = json_encode( implode(',',$dateLabels) );//implode(',',$dateLabels);
        
        $perDateStr      = implode(',', $perDateNum);

        return view('orderReport')->with([ 'title'        => $pageTitle,
                                           'dealer_id'    => $dealer_id,
                                           'dealers'      => $dealers,
                                           'dateStart'    => $dateStart,
                                           'dateEnd'      => $dateEnd,
                                           'totalOrderNum'=> $totalOrderNum,
                                           'totalShipOrderNum'=>$totalShipOrderNum,
                                           'totalPrice' => $totalPrice,
                                           'totalW_price' => $totalW_price,
                                           'details' => $details,
                                           'profit' => $profit,
                                           'totalUnshipOrderNum' => $totalUnshipOrderNum,
                                           'dateLabelsStr'=>$dateLabelsStr,
                                           'perDateStr'=>$perDateStr,
                                       ]);        

    }




    /*----------------------------------------------------------------
     | 進貨單查詢
     |----------------------------------------------------------------
     |
     */
    public function purchase( Request $request ){
        
        $pageTitle = '進貨單統計';
        
        // 如果是系統方 , 確認權限
        if( Auth::user()->hasRole('Admin') ){
            
            if( !Auth::user()->can('reportList') ){

                return back()->with('errorMsg', '帳號無此操作權限 , 如有需要請切換帳號或聯絡管理員增加權限' );
            }
        }

        // 取出所有經銷商會員
        $dealers = Role::where('name','Dealer')->first()->users()->get();
        $dealers = $dealers->toArray();
        
        // 查詢
        $query = DB::table('purchase');

        // 如果有接收到指定經銷商 , 則表示要找單一會員的訂單
        $dealer_id = 0;

        if( !empty($request->dealerId) ){
            
            if( Auth::user()->hasRole('Admin') ){    
                
                $query->where( 'dealer_id' , $request->dealerId );
                $dealer_id = $request->dealerId;

            }else{

                $query->where( 'dealer_id' , Auth::id() );
                $dealer_id = Auth::id();
            }
               
        }else{

            if( Auth::user()->hasRole('Dealer') ){
                $query->where( 'dealer_id' , Auth::id() ); 
                $dealer_id = Auth::id();               
            }
        }

        $dateStart = '';
        $dateEnd   = '';

        // 如果開始跟結束時間都沒有 , 則表示抓當月報表
        if( empty($request->start) && empty($request->end) ){

            $dateStart = date('Y-m-01 00:00:00');

            $dateEnd   = date('Y-m-d 23:59:59', strtotime(date('Y-m-01') . ' +1 month -1 day'));
            
            $query->where('created_at','>=',$dateStart)->where('created_at','<=',$dateEnd);

            $dateStart = date('Y-m-01');
            $dateEnd   = date('Y-m-d', strtotime(date('Y-m-01') . ' +1 month -1 day'));
        }else{

            if( !empty($request->start) ){

                $dateStart = $request->start." 00:00:00";
                $query->where('created_at','>=',$dateStart);
                $dateStart = $request->start;
            }
            
            if( !empty($request->end) ){

                $dateEnd = $request->end." 23:59:59";
                $query->where('created_at','<=',$dateEnd);
                $dateEnd = $request->end;
            }
        }
        
        // 完成進貨單總數
        $purchaseNum = $query->whereIn('status', [3,5])->count();

        $datas = $query->whereIn('status', [3,5])->get();
        $datas = json_decode($datas,true);
        
        $purchaseW_price = 0;
        
        $details = [];

        foreach ($datas as $datak => $data) {
            
            $purchaseW_price += $data['amount'];
            
            $tmpRes = DB::table('purchase')
            ->select('purchase.amount','purchase_goods.goods_sn','purchase_goods.goods_name' ,'purchase_goods.num')
            ->leftJoin('purchase_goods', 'purchase.id', '=', 'purchase_goods.purchase_id')
            ->where( 'purchase.id' ,$data['id'] )
            ->get();

            $tmpRes = json_decode($tmpRes,true);

            foreach ( $tmpRes as $tmpRek => $tmpRe) {

                if( array_key_exists( $tmpRe['goods_sn'] , $details ) ){
                    
                    $details[$tmpRe['goods_sn']]['num'] += $tmpRe['num'];

                }else{

                    $details[$tmpRe['goods_sn']]['name'] = $tmpRe['goods_name'];

                    $details[$tmpRe['goods_sn']]['num']  = $tmpRe['num'];
                }            
            }
        }

        return view('purchaseReport')->with([ 'title'        => $pageTitle,
                                              'dealer_id'    => $dealer_id,
                                              'dealers'      => $dealers,
                                              'dateStart'    => $dateStart,
                                              'dateEnd'      => $dateEnd,
                                              'details'      => $details,
                                              'purchaseNum'  => $purchaseNum,
                                              'purchaseW_price'=>$purchaseW_price
                                       ]);        
    }




    /*----------------------------------------------------------------
     | 未銷售
     |----------------------------------------------------------------
     |
     */
    public function goodsSale( Request $request ){

        $pageTitle = '未銷售統計';
        
        // 如果是系統方 , 確認權限
        if( Auth::user()->hasRole('Admin') ){
            
            if( !Auth::user()->can('reportList') ){

                return back()->with('errorMsg', '帳號無此操作權限 , 如有需要請切換帳號或聯絡管理員增加權限' );
            }
        }

        // 取出所有經銷商會員
        $dealers = Role::where('name','Dealer')->first()->users()->get();
        $dealers = $dealers->toArray();
        
        // 查詢
        $query = DB::table('order')
               ->leftJoin('order_goods', 'order.id', '=', 'order_goods.oid');

        // 如果有接收到指定經銷商 , 則表示要找單一會員的訂單
        $dealer_id = 0;

        if( !empty($request->dealerId) ){
            
            if( Auth::user()->hasRole('Admin') ){    
                
                $query->where( 'order.dealer_id' , $request->dealerId );
                $dealer_id = $request->dealerId;

            }else{

                $query->where( 'order.dealer_id' , Auth::id() );
                $dealer_id = Auth::id();
            }
               
        }else{

            if( Auth::user()->hasRole('Dealer') ){
                $query->where( 'order.dealer_id' , Auth::id() ); 
                $dealer_id = Auth::id();               
            }
        }

        $dateStart = '';
        $dateEnd   = '';

        // 如果開始跟結束時間都沒有 , 則表示抓當月報表
        if( empty($request->start) && empty($request->end) ){

            $dateStart = date('Y-m-01 00:00:00');

            $dateEnd   = date('Y-m-d 23:59:59', strtotime(date('Y-m-01') . ' +1 month -1 day'));
            
            $query->where('order.created_at','>=',$dateStart)->where('order.created_at','<=',$dateEnd);

            $dateStart = date('Y-m-01');
            $dateEnd   = date('Y-m-d', strtotime(date('Y-m-01') . ' +1 month -1 day'));

        }else{

            if( !empty($request->start) ){

                $dateStart = $request->start." 00:00:00";
                $query->where('order.created_at','>=',$dateStart);
                $dateStart = $request->start;
            }
            
            if( !empty($request->end) ){

                $dateEnd = $request->end." 23:59:59";
                $query->where('order.created_at','<=',$dateEnd);
                $dateEnd = $request->end;
            }
        }

        // 取出時間內所有確定成交的訂單
        $query->select('order_goods.gid');

        $datas = $query->where('order.status',4)->groupBy('gid')->get();
        
        $datas = json_decode($datas,true);
        
        $saleArr = [];
        
        foreach ($datas as $datak => $data ) {
            
            array_push($saleArr, $data['gid']);

        }
        
       
        
        // 找出所有有庫存的商品        
        $allGoods = DB::table('goods_stock')
                  ->select( "goods.name" , 'goods.goods_sn' , 'goods.id' , 'goods.w_price' )
                  ->leftJoin('goods', 'goods_stock.goods_id', '=', 'goods.id')
                  ->where('goods_num' ,'>', '0')->groupBy('goods_id')->get();

        $allGoods = json_decode($allGoods,true);
        
        $details = [];

        foreach ( $allGoods as $allGoodk => $allGood ) {

            if( !in_array($allGood['id'], $saleArr ) ){

                array_push($details, $allGood);

            }
        }
        
        foreach ($details as $detailk => $detail ) {
            
            if( Auth::user()->hasRole('Admin') ){
                
                if($dealer_id == 0){
                    $stockquery = DB::table('goods_stock')->select(DB::raw('sum(goods_num) as stock '))->where('goods_id',$detail['id'])->groupBy('goods_id')->get();
                }else{
                	$stockquery = DB::table('goods_stock')->select(DB::raw('sum(goods_num) as stock '))->where('goods_id',$detail['id'])->where('dealer_id',$dealer_id )->groupBy('goods_id')->get();
                }
            
            }elseif( Auth::user()->hasRole('Dealer') ){
                
                $stockquery = DB::table('goods_stock')->select(DB::raw('sum(goods_num) as stock '))->where('goods_id',$detail['id'])->where('dealer_id',Auth::id())->groupBy('goods_id')->get();
            }
            $stockquery = json_decode($stockquery,true);
            
            //var_dump($stockquery);
            $details[$detailk]['stock'] = $stockquery[0]['stock'];
            
        }
        
        $totalUnSale = count($details);
        // var_dump($details);

        return view('unsaleReport')->with([ 'title'        => $pageTitle,
                                              'dealer_id'    => $dealer_id,
                                              'dealers'      => $dealers,
                                              'dateStart'    => $dateStart,
                                              'dateEnd'      => $dateEnd,
                                              'details' =>$details,
                                              'totalUnSale'=>$totalUnSale

                                       ]);  

    }
}
