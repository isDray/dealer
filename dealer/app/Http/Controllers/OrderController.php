<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// 使用DB類
use DB;
use Auth;
use Validator;

// 使用Models
use App\Order;
use App\Goods;
use App\OrderGoods;
use App\User;
use App\Role;
use App\OrderLog;
use App\GoodsPrice;
use App\Dealer;

use \Exception;

/*----------------------------------------------------------------------------------------------------
 | 訂單管理類別
 |----------------------------------------------------------------------------------------------------
 |
 */

class OrderController extends Controller
{
    /*----------------------------------------------------------------
     | 訂單列表
     |----------------------------------------------------------------
     |
     */
     public function index( Request $request) {
        
        
        $dfInput = $request->input();
        
        $dfStatus = 0;
        
        if( isset($dfInput['status']) ){

            $dfStatus = $dfInput['status'];
        }
    
        
        // title 名稱
        $pageTitle = "訂單管理";

        if( Auth::user()->hasRole('Admin') ){

            if( !Auth::user()->can('orderList') ){
                
                return redirect('/home');
    
            }
        
            // 取出所有訂單
            $orders = Order::get();
    
            // 將物件轉換為陣列格式
            $orders = $orders->toArray();
    
            return view('orderList')->with([ 'title'  => $pageTitle,
                                             'dfStatus'=>$dfStatus,
                                            ]);

        }elseif( Auth::user()->hasRole('Dealer') ){
            
            // 取出經銷商的所有訂單
            $authId = Auth::id();
            $orders = Order::where("dealer_id" , "$authId")->get();
            
            // 將物件轉換為陣列格式
            $orders = $orders->toArray();

            return view('orderList')->with([ 'title'  => $pageTitle,
                                             'dfStatus'=>$dfStatus,
    
                                            ]);            
        }
    }



                                                                      
    /*---------------------------------------------------------------- 
     | 訂單新增頁面                                                     
     |----------------------------------------------------------------
     | 權限: orderNew 
     |
     */
    public function new(){
        
        if(  Auth::user()->hasRole('Admin') ){
            // 檢查權限
            if( !Auth::user()->can('orderNew') ){
                
                return redirect('/home');
    
            }
    
            // 產生空白訂單
            $createID = $this->createOrder();
            
            if( !empty($createID) ){
             
                //return redirect("/orderEdit/".$createID)->with('new', 'new');
                return redirect("/orderEditBasic/new/".$createID);/*->with('new', 'new')*/
            }else{
    
                return back()->with('errorMsg', '訂單新增失敗');
    
            }
            // 新增一組訂單後轉跳至編輯處理

        }elseif( Auth::user()->hasRole('Dealer')){

            // 產生空白訂單
            $createID = $this->createOrder();
            
            if( !empty($createID) ){

                return redirect("/orderEditBasic/new/".$createID);
                //return redirect("/orderEdit/".$createID)->with('new', 'new');
    
            }else{
    
                return back()->with('errorMsg', '訂單新增失敗');
    
            }

        }

        
    }
    



    /*----------------------------------------------------------------
     | 新增一組空訂單
     |----------------------------------------------------------------
     |
     */
    public function createOrder(){
        
        if(  Auth::user()->hasRole('Admin') ){  
          
            // 檢查權限
            if( !Auth::user()->can('orderNew') ){
            
                return redirect('/home');

            }

            $dealerId = '0';
        
        }elseif( Auth::user()->hasRole('Dealer') ){

            $dealerId = Auth::id();

        }

        $retrunID = '';

        $createSwitch = True; 

        while( $createSwitch ){
            
            //$orderSn =
            
            $radmonNUm = rand(0,999999);
            $radmonNUm = str_pad($radmonNUm,6,'0',STR_PAD_LEFT);
            
            try {
            

                $Order = new Order;

                $Order->dealer_id  = "$dealerId";
                $Order->order_sn   = date("Ymd").$radmonNUm;
                $Order->room       = '';
                $Order->amount     = 0;
                $Order->status     = '2';
                $Order->source     = '0'; 
                $Order->note       = '';
                $Order->is_new     = true;

                $Order->save();
            
                $retrunID = $Order->id;

                $this->orderLog( $retrunID , 2 );
                $createSwitch = False;


            } catch (Exception $e) {


                // 判斷是否是因為訂單號碼重複 
                if( $e->errorInfo[1] != 1062){

                    $createSwitch = False;

                }
            }
        }

        return $retrunID;
    }
    



    /*----------------------------------------------------------------
     | 編輯訂單
     |----------------------------------------------------------------
     | 新增或者後續修改都屬於編輯訂單的範圍
     |
     */
    public function edit( Request $request ){
        
        $isNew = False ; 

        if( $request->session()->get('new') ){
                
            $pageTitle = "新增訂單商品";

            $isNew     = True;
                
        }else{

            $pageTitle = "編輯訂單商品";
        
        }
        /* 系統方會員專屬訂單查詢
         *----------------------------------------------------------------
         *
         *
         */
        if(  Auth::user()->hasRole('Admin') ){
            

            
            // 如果是Admin身分 , 則需要確認是否有權限
            if( !Auth::user()->can('orderEdit') ){
                
                return redirect('/home');

            }
            
            $orderGoods = $this->getOrderGoods( $request->id );
            
            $dealerId = Order::find( $request->id );
            
            $dealerId = $dealerId->dealer_id;

            return view('orderEdit')->with([ 'title'      => $pageTitle,
                                             'orderId'    => $request->id,
                                             'orderGoods' => $orderGoods,
                                             'isNew'      => $isNew,
                                             'dealerId'   => $dealerId

            ]);

        }elseif( Auth::user()->hasRole('Dealer')){
        
        /* 經銷商訂單查詢
         *----------------------------------------------------------------
         *
         *
         */ 
            // 取的經銷商id
            $dealerID = Auth::id();

            // 如果是經銷商 , 則必須先確認該訂單是否屬於該經銷商
            if( !$this->orderBelong( $request->id , $dealerID ) ){
                
                return back()->with('errorMsg', '訂單非此帳號訂單 , 請勿嘗試非法修改');

            }

            $orderGoods = $this->getOrderGoods( $request->id );
            
            $dealerId = Order::find( $request->id );
            
            $dealerId = $dealerId->dealer_id;

            return view('orderEdit')->with([ 'title'      => $pageTitle,
                                             'orderId'    => $request->id,
                                             'orderGoods' => $orderGoods,
                                             'isNew'      => $isNew,
                                             'dealerId'   => $dealerId

            ]);

        }
    }




    /*----------------------------------------------------------------
     | 訂單基本資料新增 & 編輯
     |----------------------------------------------------------------
     | 提供一組介面讓操作者能夠新增編輯相關資訊
     |
     |
     */
    public function editBasic( Request $request ){
        
        $isNew = False;

        if( $request->type == 'new'){

            $isNew = True;

            $pageTitle = '新增訂單基本資料';

        }else{
            
            $pageTitle = '編輯訂單基本資料';
        }

        // 如果是系統方會員 , 只要確認有權限即進行操作
        if(  Auth::user()->hasRole('Admin') ){
            
            // 判斷是新增還是編輯
            if( $request->type == 'new'){
                
                if( !Auth::user()->can( 'orderNew' ) ){
                
                    return back()->with('errorMsg', '無新增訂單基本資料之權限 , 無法進行新增');
                }

            }else{

                if( !Auth::user()->can( 'orderEdit' ) ){
                
                    return back()->with('errorMsg', '無編輯訂單基本資料之權限 , 無法進行編輯');
                }
            }
            
            // 撈出該筆訂單基本資料
            $order = Order::where( 'id' , $request->id )->first();
            $order = $order->toArray();

            // 找出所有經銷商
            $dealers = Role::where('name','Dealer')->first()->users()->get();
            
            $dealers = $dealers->toArray();
            
            return view('orderEditBasic')->with([ 'title'   => $pageTitle,
                                                  'order'   => $order,
                                                  'dealers' => $dealers,
                                                  'isNew'   => $isNew
            ]);

        }
        elseif( Auth::user()->hasRole('Dealer') ){
            
            // 取得當下經銷商會員id
            $dealerId = Auth::id();
            
            // 檢查訂單是否屬於當下經銷商會員 , 如果不屬於則立刻返回
            if( ! $this->orderBelong( $request->id , $dealerId ) ){

                return back()->with('errorMsg', '訂單非此帳號訂單 , 請勿嘗試非法修改');

            }

            // 撈出該筆訂單基本資料
            $order = Order::where( 'id' , $request->id )->first();
            $order = $order->toArray();

            // 找出所有經銷商
            $dealers = Auth::user()->name;
            

            //$dealers = $dealers->toArray();
            

            return view('orderEditBasic')->with([ 'title'   => $pageTitle,
                                                  'order'   => $order,
                                                  'dealers' => [],
                                                  'dealerName' => $dealers,
                                                  'isNew'   => $isNew
            ]);            

        }
    }




    /*----------------------------------------------------------------
     | 修改訂單基本資料
     |----------------------------------------------------------------
     |
     |
     */
    public function editBasicDo( Request $request ){

        if(  Auth::user()->hasRole('Admin') ){
            
            if( !Auth::user()->can( 'orderNew' ) ){

                return back()->with('errorMsg', '無新增訂單基本資料之權限 , 無法進行新增');
            }
            
            // 如果選擇的經銷商代碼不為0 , 則需要驗證該經銷商是否存在
            if( $request->dealerId != 0){
                
                $user = User::where("id",$request->dealerId)->first();

                if( !$user->hasRole('Dealer') ){
                    
                    return back()->with('errorMsg', '選擇的經銷商會員有誤 , 請稍後再試');
                }
            }
            

            DB::beginTransaction();

            try {
                
                $Order = Order::find( $request->orderId );

                $Order->dealer_id = $request->dealerId;

                $Order->room      = $request->room;

                $Order->status    = 2;

                $Order->save();

                DB::commit();
                
                if( isset($request->isNew) && $request->isNew == 1){

                    return redirect("/orderEdit/".$request->orderId)->with('new', 'new');

                }else{

                    return redirect("/orderInfo/{$request->orderId}")->with('successMsg', '修改狀態成功');
                }

            }catch(\Exception $e){
                
                DB::rollback();
                //$e->getMessage();

                // 寫入錯誤代碼後轉跳
            
                logger("{$e->getMessage()} \n\n-----------------------------------------------\n\n"); 
            
                return back()->with('errorMsg', '設定基本資料失敗');
            }

        }elseif( Auth::user()->hasRole('Dealer') ){
            
            $dealerID = Auth::id();

            if( !$this->orderBelong( $request->orderId , $dealerID ) ){

                return back()->with('errorMsg', '訂單非此帳號訂單 , 請勿嘗試非法修改');

            }

            // 如果選擇的經銷商代碼不為0 , 則需要驗證該經銷商是否存在
            if( $request->dealerId != 0){
                
                $user = User::where("id",$request->dealerId)->first();

                if( !$user->hasRole('Dealer') ){
                    
                    return back()->with('errorMsg', '選擇的經銷商會員有誤 , 請稍後再試');
                }
            }


            DB::beginTransaction();

            try {
                
                $Order = Order::find( $request->orderId );

                $Order->dealer_id = $dealerID;

                $Order->room      = $request->room;

                $Order->status    = 2;

                $Order->save();

                DB::commit();

                if( isset($request->isNew) && $request->isNew == 1){

                    return redirect("/orderEdit/".$request->orderId)->with('new', 'new');

                }else{                
                    
                    return redirect("/orderInfo/{$request->orderId}")->with('successMsg', '修改狀態成功');
                }

            }catch(\Exception $e){
                
                DB::rollback();
                //$e->getMessage();

                // 寫入錯誤代碼後轉跳
            
                logger("{$e->getMessage()} \n\n-----------------------------------------------\n\n"); 
            
                return back()->with('errorMsg', '設定基本資料失敗');
            }            
        }
    }



    /*----------------------------------------------------------------
     | 訂單明細
     |----------------------------------------------------------------
     | 
     |
     |
     */    
    public function info( Request $request ){
        
        $pageTitle = "訂單資訊";

        // 如果是系統方管理員 , 只要有查看權限即可查看所有訂單明細
        if( Auth::user()->hasRole('Admin') ){
            
            // 如果沒有權限 , 直接終止程式
            if( !Auth::user()->can('orderList') ){

                return back()->with('errorMsg', '無查看訂單權限');

            }

            // 查出該筆訂單所有資料
            $order = Order::select('order.*' , 'users.name')
                     ->leftJoin('users', function($join) {
                         $join->on('order.dealer_id', '=', 'users.id');
                     })
                     ->where('order.id',$request->id)->first();

            $order = $order->toArray();
            



            $orderGoods = OrderGoods::select( 'order_goods.*' , 'goods.thumbnail')
                          ->leftJoin('goods', function($join) {
                              
                              $join->on('order_goods.gid', '=', 'goods.id');
                           })
                          ->where('oid',$request->id)->get();

            $orderGoods = $orderGoods->toArray();
            
            // 取出訂單log檔案
            $orderLogs = OrderLog::where('order_id' , $request->id )->orderBy('created_at', 'desc')->get();
            
            $orderLogs = $orderLogs->toArray();
            
            foreach ($orderLogs as $orderLogk => $orderLog) {

                $orderLogs[$orderLogk]['order_status'] = $this->statusToStr($orderLog['order_status'] );
                
            }
            return view('orderInfo')->with([ 'title'      => $pageTitle,
                                             'order'      => $order,
                                             'orderGoods' => $orderGoods,
                                             'orderLogs'  => $orderLogs
                                          ]);


        }elseif( Auth::user()->hasRole('Dealer' ) ){
            
            // 取得當下經銷商的id
            $dealerId = Auth::id();

            // 檢測此筆訂單是否屬於當下經銷商
            if( !$this->orderBelong( $request->id , $dealerId ) ){

                return back()->with('errorMsg', '訂單非此帳號訂單 , 請勿嘗試非法修改');
            }
            // 查出該筆訂單所有資料
            $order = Order::select('order.*' , 'users.name')
                     ->leftJoin('users', function($join) {
                         $join->on('order.dealer_id', '=', 'users.id');
                     })
                     ->where('order.id',$request->id)->first();

            $order = $order->toArray();
            



            $orderGoods = OrderGoods::select( 'order_goods.*' , 'goods.thumbnail')
                          ->leftJoin('goods', function($join) {
                              
                              $join->on('order_goods.gid', '=', 'goods.id');
                           })
                          ->where('oid',$request->id)->get();

            $orderGoods = $orderGoods->toArray();

            // 取出訂單log檔案
            $orderLogs = OrderLog::where('order_id' , $request->id )->orderBy('created_at', 'desc')->get();
            
            $orderLogs = $orderLogs->toArray();
            
            foreach ($orderLogs as $orderLogk => $orderLog) {

                $orderLogs[$orderLogk]['order_status'] = $this->statusToStr($orderLog['order_status'] );
                
            }

            return view('orderInfo')->with([ 'title'      => $pageTitle,
                                             'order'      => $order,
                                             'orderGoods' => $orderGoods,
                                             'orderLogs'  => $orderLogs
                                          ]);            
        }
    }




    /*----------------------------------------------------------------
     | 變更訂單狀態
     |----------------------------------------------------------------
     |
     */

    public function updateStatus( Request $request ){
        
        if( Auth::user()->hasRole('Admin') ){
            
            // 如果沒有訂單編輯權限 , 直接終止程式
            if( !Auth::user()->can('orderEdit') ){

                return back()->with('errorMsg', '無編輯訂單權限');

            }
            
            $wantStatus = '';
            // 判斷要更新成何種狀態
            if( isset( $request->pending) ){

                $wantStatus = 2;
            }
            if( isset( $request->shipped) ){
                
                $wantStatus = 3;
            }
            if( isset( $request->cancel) ){
                
                $wantStatus = 4;
            }
            
            if( !empty($wantStatus) ){
                
                if( !empty($request->orderId) ){
                    
                    $Order = Order::find($request->orderId);

                    $Order->status = $wantStatus;

                    if( $wantStatus == 3 ){
                        
                        $Order->ship_at = date("Y-m-d H:i:s");

                    }

                    if( $Order->save() ){
                        
                        $this->orderLog( $request->orderId , $wantStatus , 3 );

                        return redirect("/orderInfo/{$request->orderId}")->with('successMsg', '修改狀態成功');

                    }else{
                        
                        return back()->with('errorMsg', '更新狀態失敗 , 請稍後再試');
                    
                    }

                }else{
                    
                    return back()->with('errorMsg', '缺少訂單ID參數');
                }


            }else{

                return back()->with('errorMsg', '不合格狀態無法修改');
            }

        }elseif( Auth::user()->hasRole('Dealer' ) ){
            
            // 取得當下經銷商id
            $dealerId = Auth::id();

            // 判斷是否為當下經銷商的訂單
            if( ! $this->orderBelong( $request->orderId , $dealerId ) ){

                return back()->with('errorMsg', '訂單非此帳號訂單 , 請勿嘗試非法修改');

            }
            $wantStatus = '';
            // 判斷要更新成何種狀態
            if( isset( $request->pending) ){

                $wantStatus = 2;
            }
            if( isset( $request->shipped) ){
                
                $wantStatus = 3;
            }
            if( isset( $request->cancel) ){
                
                $wantStatus = 4;
            }
            
            if( !empty($wantStatus) ){
                
                if( !empty($request->orderId) ){
                    
                    $Order = Order::find($request->orderId);

                    $Order->status = $wantStatus;
                    
                    if( $wantStatus == 3 ){
                        
                        $Order->ship_at = date("Y-m-d H:i:s");

                    }
                    
                    if( $Order->save() ){

                        $this->orderLog( $request->orderId , $wantStatus , 3 );

                        return redirect("/orderInfo/{$request->orderId}")->with('successMsg', '修改狀態成功');

                    }else{
                        
                        return back()->with('errorMsg', '更新狀態失敗 , 請稍後再試');
                    
                    }

                }else{
                    
                    return back()->with('errorMsg', '缺少訂單ID參數');
                }


            }else{

                return back()->with('errorMsg', '不合格狀態無法修改');
            }

        }
    }




    /*----------------------------------------------------------------
     | 刪除訂單
     |----------------------------------------------------------------
     | 
     |
     */
    public function deleteDo( Request $request ){
        
        // 如果是系統方管理者則 , 只需要確認有刪除權限後即可進行刪除訂單之動作
        if( Auth::user()->hasRole('Admin') ){
            
            // 如果操作者不具有刪除的權限 , 就直接終止程式
            if( !Auth::user()->can('orderDelete') ){

                return back()->with('errorMsg', '此帳號不具有刪除訂單之權限');

            }

            // 檢驗訂單ID是否真的存在於資料庫 

            $validator = Validator::make($request->all(), [
                
                'id' => 'required|exists:order,id',

            ],
            [
                'id.required' => '缺少要刪除的訂單id , 無法進行刪除',
                'id.exists'   => '此訂單編號不存在 , 無法進行刪除'
            ]);

            if ($validator->fails()) {
                
                $errText = '';

                $errors = $validator->errors();
                
                foreach( $errors->all() as $message ){
                    
                    $errText .= "$message<br>";
                }

                return back()->with('errorMsg', $errText );
            }

            $Order = Order::find( $request->id );

            if( $Order->delete() ){

                return redirect("/order")->with('successMsg', '訂單刪除成功');

            }else{

                return back()->with('errorMsg', '訂單刪除失敗');
            }
        
        }
        // 如果為經銷商會員 , 需要確定該筆訂單為其訂單後才可以進行刪除動作
        elseif( Auth::user()->hasRole('Dealer') ){
            
            // 取出當下經銷商id
            $dealerId = Auth::id();
            
            // 檢查訂單是否屬於該經銷商
            if( ! $this->orderBelong( $request->id , $dealerId ) ){

                return back()->with('errorMsg', '訂單非此帳號訂單 , 請勿嘗試非法修改');

            }

            // 檢驗訂單ID是否真的存在於資料庫 

            $validator = Validator::make($request->all(), [
                
                'id' => 'required|exists:order,id',

            ],
            [
                'id.required' => '缺少要刪除的訂單id , 無法進行刪除',
                'id.exists'   => '此訂單編號不存在 , 無法進行刪除'
            ]);

            if ($validator->fails()) {
                
                $errText = '';

                $errors = $validator->errors();
                
                foreach( $errors->all() as $message ){
                    
                    $errText .= "$message<br>";
                }

                return back()->with('errorMsg', $errText );
            }

            $Order = Order::find( $request->id );

            if( $Order->delete() ){

                return redirect("/order")->with('successMsg', '訂單刪除成功');

            }else{

                return back()->with('errorMsg', '訂單刪除失敗');
            }            
        }

    }

    

    /*----------------------------------------------------------------
     | 訂單查詢
     |----------------------------------------------------------------
     |
     */
    public function query( Request $request ){
        
       
        /* 系統方會員專屬訂單查詢
         *----------------------------------------------------------------
         *
         */

        if( Auth::user()->hasRole('Admin') ){

            $recordsTotal = Order::count();
    
            $query = DB::table('order');
          

            $query->leftJoin('users', 'order.dealer_id', '=', 'users.id');
            // 如果最小值有填寫
            if( !empty($request->min_price ) ){
    
                $query->where('amount', '>=', $request->min_price);
    
            }
            
            // 如果最大值有填寫
            if( !empty($request->max_price ) ){
                
                $query->where('amount', '<=', $request->max_price);
    
            }
    
            // 如果有接收到訂單狀態
            if( !empty($request->status) ){
                
                $query->where('status', $request->status);
            }
    
            // 如果有接收到訂單開始時間
            if( !empty($request->orderSatrt) ){

                $request->orderSatrt = $request->orderSatrt." 00:00:00";

                $query->where( 'updated_at' , '>=' , $request->orderSatrt );
            }

            // 如果有收到訂單結束時間
            if( !empty($request->orderEnd) ){

                $request->orderEnd = $request->orderEnd." 23:59:59";

                $query->where( 'updated_at' , '<=' , $request->orderEnd );
            }

            // 表示剛進入查詢需要以訂單時間作為排序依據
            if( $request->order['0']['column'] == 5){
    
                $query->orderBy('updated_at', $request->order['0']['dir']);
    
            }
            

    

            $orders = $query->select('order.*', 'users.name')->get();

            $orders = $orders->toArray();

            $returnData = [];

            foreach ($orders as $key => $value) {
        
                array_push($returnData, [
                $value->order_sn,
                $value->name,
                $value->room,
                $value->final_amount,
                $value->status,
                $value->payway,
                $value->ship_at,
                $value->updated_at,
                $value->source,
                $value->note,
                $value->id,
                
                                    ]);
                
            }        

            echo json_encode( ['data'=>$returnData , 'recordsTotal'=>$recordsTotal, 'recordsFiltered'=>count($returnData)] );
        } elseif( Auth::user()->hasRole('Dealer') ){

        /* 經銷商專用的查詢
         *----------------------------------------------------------------
         *
         *
         */  
            $authId = Auth::id();
        
            $recordsTotal = Order::where("dealer_id" , "$authId")->count();
            
            $query = DB::table('order');

            // 如果最小值有填寫
            if( !empty($request->min_price ) ){
    
                $query->where('amount', '>=', $request->min_price);
    
            }
            
            // 如果最大值有填寫
            if( !empty($request->max_price ) ){
                
                $query->where('amount', '<=', $request->max_price);
    
            }
    
            // 如果有接收到訂單狀態
            if( !empty($request->status) ){
                
                $query->where('status', $request->status);
            }
    
            // 如果有接收到訂單開始時間
            if( !empty($request->orderSatrt) ){

                $request->orderSatrt = $request->orderSatrt." 00:00:00";

                $query->where( 'updated_at' , '>=' , $request->orderSatrt );
            }

            // 如果有收到訂單結束時間
            if( !empty($request->orderEnd) ){

                $request->orderEnd = $request->orderEnd." 23:59:59";

                $query->where( 'updated_at' , '<=' , $request->orderEnd );
            }

            // 表示剛進入查詢需要以訂單時間作為排序依據
            if( $request->order['0']['column'] == 5){
    
                $query->orderBy('updated_at', $request->order['0']['dir']);
    
            }
            

            $query->where( "dealer_id" , "$authId" );

            $orders = $query->get();

            $orders = $orders->toArray();

            $returnData = [];

            foreach ($orders as $key => $value) {
        
                array_push($returnData, [
                $value->order_sn,
                $value->dealer_id,
                $value->room,
                $value->amount,
                $value->status,
                $value->payway,
                $value->ship_at,
                $value->updated_at,
                $value->source,
                $value->note,
                $value->id,

                                    ]);
                
            }        

            echo json_encode( ['data'=>$returnData , 'recordsTotal'=>$recordsTotal, 'recordsFiltered'=>count($returnData)] );            
        }
    }



    /*----------------------------------------------------------------
     | 訂單內查詢商品資訊
     |----------------------------------------------------------------
     | 根據ajax所接收到之關鍵字與資料庫中所有商品做對照
     |
     */
    public function searchGoods( Request $request ){
        
        /* 系統方會員區塊
         *----------------------------------------------------------------
         *
         */ 

        if( Auth::user()->hasRole('Admin') ){
            
            // 
            if( !empty( $request->goodsKeyWord )){
                
                //echo json_encode( $request->goodsKeyWord );

                $datas = Goods::where('name','like','%'.$request->goodsKeyWord.'%')
                              ->orWhere('goods_sn','like','%'.$request->goodsKeyWord.'%')
                              ->get();

                echo json_encode($datas);
            }

            
        }elseif( Auth::user()->hasRole('Dealer')){

        /* 經銷商方會員區塊
         *----------------------------------------------------------------
         *
         */    
            if( !empty( $request->goodsKeyWord )){
                
                //echo json_encode( $request->goodsKeyWord );

                $datas = Goods::where('name','like','%'.$request->goodsKeyWord.'%')
                              ->orWhere('goods_sn','like','%'.$request->goodsKeyWord.'%')
                              ->get();

                echo json_encode($datas);
            }              
        }

    }



    /*----------------------------------------------------------------
     | 查詢指定商品
     |----------------------------------------------------------------
     |
     |
     */
    public function getGoods( Request $request ){

        /* 系統方會員區塊
         *----------------------------------------------------------------
         * 如果是系統管理會員 , 可以抓取全部商品資料
         * 
         */ 

        if( Auth::user()->hasRole('Admin') ){
            
            if( !empty( $request->chooseId ) ){
                
                $datas = Goods::select('goods.*','category.name as cname')
                ->leftJoin('category', function($join) {
                    $join->on('goods.cid', '=', 'category.id');
                })
                ->where('goods.id',$request->chooseId)
                ->first();
                
                $datas = $datas->toArray();
                
                if( !empty($datas) ){
                    // 判斷有沒有給特定價格
                    $chkPrice = GoodsPrice::where('dealer_id',$request->dealerId)->where('goods_id',$request->chooseId)->exists();
                    
                    if( $chkPrice ){

                        $price = GoodsPrice::where('dealer_id',$request->dealerId)->where('goods_id',$request->chooseId)->first();
                        $datas['price'] = $price->price;
                
                    }else{
                    
                        $dealer = Dealer::where('dealer_id',$request->dealerId)->first();
                         
                        //$dealer = $dealer->toArray();
                        $datas['price'] = round( $datas['w_price'] * $dealer->multiple ); 
                    }                    
                    echo json_encode( $datas );

                }else{
                    
                    echo json_encode( FALSE );
                }
                
            }

        }elseif( Auth::user()->hasRole('Dealer')){

        /* 經銷商方會員區塊
         *----------------------------------------------------------------
         *
         */     
            if( !empty( $request->chooseId ) ){
                
                $datas = Goods::select('goods.*','category.name as cname')
                ->leftJoin('category', function($join) {
                    $join->on('goods.cid', '=', 'category.id');
                })
                ->where('goods.id',$request->chooseId)
                ->first();
                
                if( !empty($datas) ){
                    // 判斷有沒有給特定價格
                    $chkPrice = GoodsPrice::where('dealer_id',$request->dealerId)->where('goods_id',$request->chooseId)->exists();
                    
                    if( $chkPrice ){

                        $price = GoodsPrice::where('dealer_id',$request->dealerId)->where('goods_id',$request->chooseId)->first();
                        $datas['price'] = $price->price;
                
                    }else{
                    
                        $dealer = Dealer::where('dealer_id',$request->dealerId)->first();
                         
                        
                        //$dealer = $dealer->toArray();
                        $datas['price'] = round( $datas['w_price'] * $dealer->multiple ); 
                    }                     
                    echo json_encode( $datas );

                }else{
                    
                    echo json_encode( FALSE );
                }
                
            }             
        }
    }



    /*----------------------------------------------------------------
     | 新增產品至訂單中
     |----------------------------------------------------------------
     | 
     |
     */
    public function addGoods( Request $request ){
        
        /* 驗證相關資料
         *----------------------------------------------------------------
         * 驗證要寫入的資料是否符合需求 , 如果不符合則直接中斷
         *
         */
        $validator = Validator::make($request->all(), [
            
            'goodsId'     => 'required|exists:goods,id',
            'goodsPrice'  => 'required|numeric',
            'goodsNumber' => 'required|numeric|min:1'

        ],
        [ 
            'goodsId.required'     => '商品Id為必填',
            'goodsId.exists'       => '商品不存在',
            'goodsPrice.required'  => '商品價格為必填',
            'goodsPrice.numeric'   => '商品價格必須為數字',
            'goodsNumber.required' => '商品數量為必填',
            'goodsNumber.numeric'  => '商品數量必須為數字',
            'goodsNumber.min'      => '商品數量至少要1個',
        ]);
        

        if ($validator->fails()) {

            $res = ['status' => false ];

            foreach ($validator->errors()->all() as  $errText) {

                array_push($res, $errText);
            }
           
            echo json_encode( $res );
        
        }else{
            
            /* 寫入明細表
             *----------------------------------------------------------------
             * 如果操作者身分為經銷商會員 , 在其新增時需要限制可控制商品及訂單
             * , 避免不同經銷商之間互相衝突
             *
             */
            if( Auth::user()->hasRole('Admin') ){
                
                if( !$this->goodsInOrder( $request->orderId , $request->goodsId ) ){
                    
                    if( $this->addGoodsToOrder( $request->orderId , $request->goodsId , $request->goodsPrice , $request->goodsNumber ) ){
                        
                        $orderGoods = $this->getOrderGoods( $request->orderId ); 

                        echo json_encode( $orderGoods );

                    }else{
                        
                    $res = ['status' => false , 0=>'新增商品至訂單失敗 , 請稍後再試'];
                    echo json_encode( $res );

                    }
                }else{

                    $res = ['status' => false , 0=>'該商品已存在 , 不需要另外新增'];
                    echo json_encode( $res );
                }

            }elseif( Auth::user()->hasRole('Dealer') ){
                
                $dealerId = Auth::id();
                // 檢查訂單是否屬於當下的經銷商會元
                if( !$this->orderBelong( $request->orderId , $dealerId ) ){

                    $res = ['status' => false , 0=>'訂單非此帳號訂單 , 請勿嘗試非法修改'];
                    echo json_encode( $res );
                    exit;
                }
                
                if( !$this->goodsInOrder( $request->orderId , $request->goodsId ) ){
                    
                    if( $this->addGoodsToOrder( $request->orderId , $request->goodsId , $request->goodsPrice , $request->goodsNumber ) ){
                        
                        $orderGoods = $this->getOrderGoods( $request->orderId ); 

                        echo json_encode( $orderGoods );

                    }else{
                        
                    $res = ['status' => false , 0=>'新增商品至訂單失敗 , 請稍後再試'];
                    echo json_encode( $res );

                    }
                }else{

                    $res = ['status' => false , 0=>'該商品已存在 , 不需要另外新增'];
                    echo json_encode( $res );
                }
                                
            }

        }

        
    }



    /*----------------------------------------------------------------
     | 編輯訂單中商品數量及價格
     |----------------------------------------------------------------
     |
     */
    public function editGoods( Request $request ){
        
        // 如果是系統方操作 , 則可以編輯所有訂單
        if( Auth::user()->hasRole('Admin') ){
            
            // 如果是Admin身分 , 則需要確認是否有權限
            if( !Auth::user()->can('orderEdit') ){
                
                return redirect('/home');

            }
            
            //var_dump($request->all());
            // 計算總共有多少細項
            $itemNum = count( $request->id );
            
            DB::beginTransaction();

            try {

                // 迴圈檢測是否有變動
                for ($i=0; $i < $itemNum ; $i++) { 
    
                    //$request->id[$i];
    
                    if( !$this->chkIfUpdate( $request->orderid , $request->id[$i] , $request->num[$i] , $request->price[$i] ) ){
                        /*
                        $OrderGoods = OrderGoods::where( 'oid' , $request->orderid)
                                                ->where( 'gid' , $request->id[$i])
                                                ->first();

                        $OrderGoods->price     = $request->price[$i];
                        $OrderGoods->num       = $request->num[$i];
                        $OrderGoods->subtotal  = ($request->price[$i] * $request->num[$i]);

                        $OrderGoods->save();
                        */
                        DB::table('order_goods')
                        ->where('oid', $request->orderid)
                        ->where( 'gid' , $request->id[$i])
                        ->update(['price' => $request->price[$i],
                                  'num'   => $request->num[$i], 
                                  'subtotal' => ($request->price[$i] * $request->num[$i]) ]);
                    }
    
                }
                

                // 重新計算訂單總價
                $OrderGoods = OrderGoods::where( 'oid' , $request->orderid)->get();
                $OrderGoods = $OrderGoods->toArray();
                
                $orderAmount = 0;

                foreach ( $OrderGoods as $OrderGood ) {
                    
                    $orderAmount += $OrderGood['subtotal'];

                }
                
                $Order = Order::find( $request->orderid );
                $Order->amount = $orderAmount;
                $Order->final_amount = $orderAmount - $Order->discount;

                $Order->save();

                DB::commit();
                
                return redirect('/orderEdit/'.$request->orderid);


            }catch(\Exception $e){
                             DB::rollback();

            //$e->getMessage();

            // 寫入錯誤代碼後轉跳
            
            logger("{$e->getMessage()} \n\n-----------------------------------------------\n\n"); 
            
            //return back()->with('errorMsg', '訂單細項編輯失敗');
            }


        }elseif( Auth::user()->hasRole('Dealer') ){
            
            $dealerId = Auth::id();

            if( !$this->orderBelong( $request->orderid , $dealerId ) ){

                return back()->with('errorMsg', '訂單非此帳號訂單 , 請勿嘗試非法修改');

            }

            // 計算總共有多少細項
            $itemNum = count( $request->id );
            
            DB::beginTransaction();

            try {

                // 迴圈檢測是否有變動
                for ($i=0; $i < $itemNum ; $i++) { 
    
                    //$request->id[$i];
    
                    if( !$this->chkIfUpdate( $request->orderid , $request->id[$i] , $request->num[$i] , $request->price[$i] ) ){
                        /*
                        $OrderGoods = OrderGoods::where( 'oid' , $request->orderid)
                                                ->where( 'gid' , $request->id[$i])
                                                ->first();

                        $OrderGoods->price     = $request->price[$i];
                        $OrderGoods->num       = $request->num[$i];
                        $OrderGoods->subtotal  = ($request->price[$i] * $request->num[$i]);

                        $OrderGoods->save();
                        */
                        DB::table('order_goods')
                        ->where('oid', $request->orderid)
                        ->where( 'gid' , $request->id[$i])
                        ->update(['price' => $request->price[$i],
                                  'num'   => $request->num[$i], 
                                  'subtotal' => ($request->price[$i] * $request->num[$i]) ]);
                    }
    
                }
                

                // 重新計算訂單總價
                $OrderGoods = OrderGoods::where( 'oid' , $request->orderid)->get();
                $OrderGoods = $OrderGoods->toArray();
                
                $orderAmount = 0;

                foreach ( $OrderGoods as $OrderGood ) {
                    
                    $orderAmount += $OrderGood['subtotal'];

                }
                
                $Order = Order::find( $request->orderid );
                $Order->amount = $orderAmount;
                $Order->final_amount = $orderAmount - $Order->discount;

                $Order->save();

                DB::commit();
                
                return redirect('/orderEdit/'.$request->orderid);


            }catch(\Exception $e){
                             DB::rollback();
            //$e->getMessage();

            // 寫入錯誤代碼後轉跳
            
            logger("{$e->getMessage()} \n\n-----------------------------------------------\n\n"); 
            
            return back()->with('errorMsg', '訂單細項編輯失敗');
            }            

        }
    }
    




    /*----------------------------------------------------------------
     | 編輯訂單費用
     |----------------------------------------------------------------
     |
     */
    public function feeEdit( Request $request ){
        
        $pageTitle = "編輯費用";

        // 確認訂單是否存在
        if( !$this->chkOrderExist( $request->id ) ){

            return redirect('/home');

        }
        if( Auth::user()->hasRole('Admin') ){

            // 如果是Admin身分 , 則需要確認是否有權限
            if( !Auth::user()->can('orderEdit') ){
                
                return redirect('/home');

            }
          


        }elseif( Auth::user()->hasRole('Dealer') ){
            
            if( !$this->orderBelong($request->id,Auth::id() ) ){

                return back()->with('errorMsg', '訂單非此帳號訂單 , 請勿嘗試非法修改');
            }
        }

        // 取出訂單相關資料
        $order = Order::find( $request->id );
        $order->toArray();
        
        return view('orderFeeEdit')->with([ 'title'      => $pageTitle,
                                            'order'      => $order
             
                                          ]);   

    } 




    /*----------------------------------------------------------------
     | 編輯訂單費用 實作
     |----------------------------------------------------------------
     |
     */
    public function feeEditDo( Request $request ){

        $validator = Validator::make($request->all(), [
            'orderId'      => 'required|exists:order,id'
        ],
        [ 
           'orderId.required' => '遺失訂單編號遺',
           'orderId.exists'   => '訂單不存在'
        ]);
        
        $errText = '';

        if ($validator->fails()) {
                
            $errors = $validator->errors();
            
            foreach( $errors->all() as $message ){
                
                $errText .= "$message<br>";
            }
               
        }
        
        if( !empty($errText) ){

            return back()->with('errorMsg', $errText );
        }

        if( Auth::user()->hasRole('Admin') ){
            // 如果是Admin身分 , 則需要確認是否有權限
            if( !Auth::user()->can('orderEdit') ){
                
                return redirect('/home');

            }            

        }elseif( Auth::user()->hasRole('Dealer') ){
            
            if( !$this->orderBelong($request->orderId,Auth::id() ) ){

                return back()->with('errorMsg', '訂單非此帳號訂單 , 請勿嘗試非法修改');
            }
        }

        if( !isset( $request->discount ) || empty( $request->discount) ){

            $discount = 0;

        }else{

            $discount = $request->discount;
        } 
        
        $Order = Order::find( $request->orderId );
        $Order->discount = $discount;
        $Order->final_amount = $Order->amount - $discount;

        if( $Order->save() ){
            return redirect("/orderInfo/{$request->orderId}")->with('successMsg', '訂單費用編輯成功');

        }else{
            return back()->with('errorMsg', '訂單費用編輯失敗');
        }

    }




    /*----------------------------------------------------------------
     | 移除訂單中商品細項
     |----------------------------------------------------------------
     |
     */
     public function deleteGoods( Request $request ){
        
        // 如果為系統方會員 , 只要有權限就可以讓其移除商品細項 
        if( Auth::user()->hasRole('Admin') ){

            // 如果沒有訂單編輯權限 , 直接終止程式
            if( !Auth::user()->can('orderEdit') ){

                return back()->with('errorMsg', '無編輯訂單權限');

            }

            if( !isset($request->oid) || !isset($request->gid) ){

                return back()->with('errorMsg', '缺少必要參數,請重整頁面後再試一次');
            }
            

            if( !$this->goodsInOrder( $request->oid , $request->gid ) ){
                
                return back()->with('errorMsg', '無對應訂單商品,請重整頁面後再試一次');
            }
            
            $res = DB::table('order_goods')
            ->where('oid', $request->oid)
            ->where('gid', $request->gid)
            ->delete();

            DB::beginTransaction();

            try {
                
                $res = DB::table('order_goods')
                ->where('oid', $request->oid)
                ->where('gid', $request->gid)
                ->delete();   

                // 重新計算訂單總價
                $OrderGoods = OrderGoods::where( 'oid' , $request->oid)->get();
                $OrderGoods = $OrderGoods->toArray();
                
                $orderAmount = 0;

                foreach ( $OrderGoods as $OrderGood ) {
                    
                    $orderAmount += $OrderGood['subtotal'];

                }
                
                $Order = Order::find( $request->oid );
                $Order->amount = $orderAmount;
                $Order->final_amount = $orderAmount - $Order->discount;

                $Order->save();

                DB::commit();

                return redirect("/orderEdit/{$request->oid}")->with('successMsg', '訂單商品移除成功');

            }catch(\Exception $e){
                
                DB::rollback();
                //$e->getMessage();

                // 寫入錯誤代碼後轉跳
            
                logger("{$e->getMessage()} \n\n-----------------------------------------------\n\n"); 
            
                return back()->with('errorMsg', '訂單商品移除失敗');
            }


        }elseif( Auth::user()->hasRole('Dealer') ){
            
            // 取得當下經銷商會員id
            $dealerId = Auth::id();

            // 檢驗是否為當下經銷商的訂單 , 如果不是就直接終止
            if( !$this->orderBelong( $request->oid , $dealerId ) ){

                return back()->with('errorMsg', '訂單非此帳號訂單 , 請勿嘗試非法修改');
            }
            if( !isset($request->oid) || !isset($request->gid) ){

                return back()->with('errorMsg', '缺少必要參數,請重整頁面後再試一次');
            }
            

            if( !$this->goodsInOrder( $request->oid , $request->gid ) ){
                
                return back()->with('errorMsg', '無對應訂單商品,請重整頁面後再試一次');
            }
            
            $res = DB::table('order_goods')
            ->where('oid', $request->oid)
            ->where('gid', $request->gid)
            ->delete();

            DB::beginTransaction();

            try {
                
                $res = DB::table('order_goods')
                ->where('oid', $request->oid)
                ->where('gid', $request->gid)
                ->delete();   

                // 重新計算訂單總價
                $OrderGoods = OrderGoods::where( 'oid' , $request->oid)->get();
                $OrderGoods = $OrderGoods->toArray();
                
                $orderAmount = 0;

                foreach ( $OrderGoods as $OrderGood ) {
                    
                    $orderAmount += $OrderGood['subtotal'];

                }
                
                $Order = Order::find( $request->oid );
                $Order->amount = $orderAmount;
                $Order->final_amount = $orderAmount - $Order->discount;

                $Order->save();

                DB::commit();

                return redirect("/orderEdit/{$request->oid}")->with('successMsg', '訂單商品移除成功');

            }catch(\Exception $e){
                
                DB::rollback();
                //$e->getMessage();

                // 寫入錯誤代碼後轉跳
            
                logger("{$e->getMessage()} \n\n-----------------------------------------------\n\n"); 
            
                return back()->with('errorMsg', '訂單商品移除失敗');
            }

        }
     }




    /*----------------------------------------------------------------
     |
     |----------------------------------------------------------------
     |
     */
    public function orderCheck( Request $request ){
        
        if( Auth::user()->hasRole('Dealer') ){
            

            $orderNum = Order::where('dealer_id',Auth::id())->where('status',2)->count();
            
            if( $orderNum > 0){
                return json_encode(['res'=>true , 'num'=>$orderNum]);
            }else{
                return json_encode(['res'=>false  , 'num'=>0]);
            }
        }else{

            return json_encode(['res'=>false  , 'num'=>0]);
        }
    }
    
    


    /*----------------------------------------------------------------
     | 確認該商品是否已經存在於訂單內
     |----------------------------------------------------------------
     | 參數:
     |     $_orderID -> 訂單ID
     |     $_goodsID -> 商品ID
     |
     | 回傳值:
     |     True  -> 已存在    
     |     False -> 不存在
     */
    public function goodsInOrder( $_orderID , $_goodsID ){
        
        return OrderGoods::where('oid',$_orderID)
                         ->where('gid',$_goodsID)
                         ->exists();

    }




    /*----------------------------------------------------------------
     | 新增商品至訂單明細中
     |----------------------------------------------------------------
     | 參數:
     |     $_orderID -> 訂單ID
     |     $_goodsID -> 商品ID 
     |
     |
     */
    public function addGoodsToOrder( $_orderID , $_goodsID , $_price , $_number ){
        
        $datas = Goods::where( 'id' , $_goodsID )->first();
        
        $datas = $datas->toArray();
        
        if( $datas ){
            
            DB::beginTransaction();

            try {
                
                $OrderGoods           = new OrderGoods;

                $OrderGoods->oid      = $_orderID;
    
                $OrderGoods->gid      = $_goodsID;
            
                $OrderGoods->goods_sn = $datas['goods_sn'];
            
                $OrderGoods->name     = $datas['name'];
                
                $OrderGoods->price    = $_price;
                  
                $OrderGoods->num      = $_number;
                
                $OrderGoods->subtotal = ($_price * $_number);

                $OrderGoods->save();

                // 重新計算訂單總價
                $OrderGoods = OrderGoods::where( 'oid' , $_orderID )->get();
                $OrderGoods = $OrderGoods->toArray();
                
                $orderAmount = 0;

                foreach ( $OrderGoods as $OrderGood ) {
                    
                    $orderAmount += $OrderGood['subtotal'];

                }
                
                $Order = Order::find( $_orderID );
                $Order->amount = $orderAmount;
                $Order->final_amount = $orderAmount - $Order->discount;

                $Order->save();

                DB::commit();

                return True;

            }catch(\Exception $e){
                
                logger("{$e->getMessage()} \n\n-----------------------------------------------\n\n"); 

                return False;

            }
            
        }else{
            
            return False;
        }
        

    }




    /*----------------------------------------------------------------
     | 取出訂單所有明細
     |----------------------------------------------------------------
     |
     */
    public function getOrderGoods( $_orderID ){
        
        $orderGoods = orderGoods::select('order_goods.*' , 'goods.thumbnail')->leftJoin('goods', function($join) {
                          $join->on('order_goods.gid', '=', 'goods.id');
                      })
                      ->where('order_goods.oid' , $_orderID)
                      ->get();
        
        return $orderGoods = $orderGoods->toArray();     

    }



    /*----------------------------------------------------------------
     | 檢測是否有變動
     |----------------------------------------------------------------
     | 將 訂單id + 商品id + 數量 + 售價 + 總價 混合為查詢條件 , 如果
     | 找不到紀錄 , 則表示表單有變動過需要做修改
     | 
     */
    public function chkIfUpdate( $_orderID , $_goodsID , $_goodNum , $_goodsPrice ){
        
        return OrderGoods::where( 'oid'   , $_orderID )
               ->where( 'gid'   , $_goodsID )
               ->where( 'num'   , $_goodNum )
               ->where( 'price' , $_goodsPrice )
               ->exists();
                  
    }




    /*----------------------------------------------------------------
     | 檢查經銷商會員是否存在
     |----------------------------------------------------------------
     |
     */
    public function dealerExist( $_id ){
         
        $user = User::where('id',$_id)->first();
        
        if( $user->hasRole('Dealer') ){
            
            return True;

        }else{
            
            return False;
        }
     }




    /*----------------------------------------------------------------
     | 檢查訂單是否屬於指定經銷商
     |----------------------------------------------------------------
     |
     */
    public function orderBelong( $_orderID , $_dealerId ){
         
        return Order::where('id' , $_orderID)
                ->where('dealer_id' , $_dealerId)
                ->exists();
    }




    /*----------------------------------------------------------------
     | 寫入訂單相關log
     |----------------------------------------------------------------
     | 參數:
     |
     |     [1]$_orderId     = 訂單id
     |
     |     [2]$_orderStatus = 訂單狀態
     |         
     |         1.尚未新增完
     |         2.待處理
     |         3.已出貨
     |         4.取消 
     |
     |     [3]$_operation   = 操作選項描述
     |         
     |         0.新增訂單
     |
     */
    public function orderLog( $_orderId , $_orderStatus ,$_operation = 0 ){
  
        $operationDesc = '';

        // 將操作代碼轉換為文字描述
        switch ( $_operation ) {
            case '1':
                $operationDesc = '編輯訂單基本資料';
            break;
            
            case '2':
                $operationDesc = '編輯訂單商品';
            break;   
            
            case '3':
                $operationDesc = '修改訂單狀態';
            break;                   

            default:
                $operationDesc = '新增訂單';
            break;
        }


        if( Auth::user()->hasRole('Admin') ){

            $roleName = 'Admin';

        }elseif( Auth::user()->hasRole('Dealer') ){

            $roleName = 'Dealer';

        }else{

            $roleName = 'General';
        }

        $OrderLog = new OrderLog;

        $OrderLog->user_id      = Auth::id();
        $OrderLog->user_name    =  Auth::user()->name;
        $OrderLog->user_role    = $roleName;
        $OrderLog->order_id     = $_orderId;
        $OrderLog->order_status = $_orderStatus;
        $OrderLog->desc         = $operationDesc;

        $OrderLog->save();
        
    }




    /*----------------------------------------------------------------
     | 狀態對照
     |----------------------------------------------------------------
     |
    */
    public function statusToStr( $_orderStatus ){
        
        switch ( $_orderStatus ) {
            case 1:
                return '尚未新增完成';
            break;
            case 2:
                return '待處理';
            break;
            case 3:
                return '已出貨';
            break;
            case 4:
                return '取消';
            break;                                    
          
        }
    }
    


    /*----------------------------------------------------------------
     | 確認訂單存在
     |----------------------------------------------------------------
     |
     */
    public function chkOrderExist( $_orderId ){

        return Order::where('id',$_orderId)->exists();
    }


}
