<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Auth;
use Validator;

use App\User;
use App\Goods;
use App\Role;
use App\Order;
use App\OrderGoods;

use App\Purchase;
use App\PurchaseGoods;
use App\GoodsStock;

use \Exception;
class PurchaseController extends Controller
{
    



    /*----------------------------------------------------------------
     | 進貨單管理首頁
     |----------------------------------------------------------------
     |
     */
    public function index( Request $request){
        
        $pageTitle = '進貨單管理';

        //判斷當下使用者是系統方會員還是經銷商會員
        if( Auth::user()->hasRole('Admin') ){
            
            // 判斷權限
            if( !Auth::user()->can('purchaseList') ){

                return redirect('/home')->with('errorMsg', '帳號無此操作權限 , 如有需要請切換帳號或聯絡管理員增加權限');
            }
            
            // 取出所有經銷商
            $dealers = Role::where('name','Dealer')->first()->users()->get();
            $dealers = $dealers->toArray();

            return view('purchaseList')->with([ 'title'   => $pageTitle,
                                                'dealers' => $dealers
                                               ]); 


        }elseif( Auth::user()->hasRole('Dealer') ){

        }

    }
    



    /*----------------------------------------------------------------
     | 查詢
     |----------------------------------------------------------------
     |
     */
    public function query( Request $request ){
        
        // 判斷是否為系統方管理者 
        if( Auth::user()->hasRole('Admin') ){
        
        }elseif( Auth::user()->hasRole('Dealer') ){
        
        }

        $query = DB::table('purchase');
        $querynum = DB::table('purchase');
        
        // 過濾經銷商
        if( !empty($request->dealer ) ){
    
            $query->where( 'dealer_id', $request->dealer );
            $querynum->where( 'dealer_id', $request->dealer );
        } 
        
        // 過濾狀態
        if( !empty($request->status ) ){
    
            $query->where( 'status', $request->status );
            $querynum->where( 'status', $request->status );
        } 
        
        // 進貨單金額
        if( !empty($request->min_price ) ){
    
            $query->where( 'amount','>=',$request->min_price );
            $querynum->where( 'amount','>=',$request->min_price );
    
        }        

        if( !empty($request->max_price ) ){
    
            $query->where( 'amount','<=',$request->max_price );
            $querynum->where( 'amount','<=',$request->max_price );
        }

        // 進貨單時間
        if( !empty($request->orderSatrt ) ){

            $request->orderSatrt = $request->orderSatrt." 00:00:00";

            $query->where( 'updated_at' , '>=' , $request->orderSatrt );
            $querynum->where( 'updated_at' , '>=' , $request->orderSatrt );
        }

        if( !empty($request->orderEnd) ){

            $request->orderEnd = $request->orderEnd." 23:59:59";

            $query->where( 'updated_at' , '<=' , $request->orderEnd );
            $querynum->where( 'updated_at' , '<=' , $request->orderEnd );

        }        

        // 如果有收到排序項目
        //var_dump($request->all());
        if( !empty($request->order[0]['column']) ){
            
            $orderType = $request->order[0]['dir'];

            if( $request->order[0]['column'] == 8){
                
                $query->orderBy('shipdate', "$orderType");
            }
            if( $request->order[0]['column'] == 9){
                
                $query->orderBy('updated_at', "$orderType");
            }
        }
        
        if( !empty($request->search['value']) ){

            $query->where('dealer_name','like', "%{$request->search['value']}%");
            $querynum->where('dealer_name','like', "%{$request->search['value']}%");

        }

        if( !empty( $request->start ) ){

            $query->offset($request->start);
        }
        if( !empty( $request->length ) ){

            $query->limit($request->length);
        }

        $allFilter = $querynum->count();

        $purchase = $query->select('purchase.*')->get();

        $purchase = $purchase->toArray();

        $returnData = [];

        foreach ($purchase as $key => $value) {
        
            array_push($returnData, [
            
            $value->id,
            $value->purchase_sn,
            $value->dealer_id,
            $value->dealer_name,
            $value->phone,
            $value->address,            
            $value->amount, 
            $value->status,                       
            $value->shipdate,
            $value->created_at,
            $value->updated_at,
            ]);
                
        }
        
        $recordsTotal = Purchase::count();
        
        echo json_encode( ['data'=>$returnData , 'recordsTotal'=>$recordsTotal, 'recordsFiltered'=>$allFilter] );

    }
    




    /*----------------------------------------------------------------
     |  進貨單詳細資訊
     |----------------------------------------------------------------
     |
     */
    public function info( Request $request ){
        
        $pageTitle = '進貨單管理';

        // 判斷權限
        if( Auth::user()->hasRole('Admin') ){

            if( !Auth::user()->can('purchaseEdit')){
                
                return back()->with('errorMsg', '帳號無此操作權限 , 如有需要請切換帳號或聯絡管理員增加權限' );
            }

        }elseif( Auth::user()->hasRole('Dealer') ){
            
            if( !$this->chkPurchase( $request->id ) ){

                return back()->with('errorMsg', '進貨單不屬於目前帳號 , 請勿嘗試非法操作<br>' );
            }
        }
        
        return view('purchaseInfo')->with(['title' => $pageTitle]);
    }




    /*----------------------------------------------------------------
     | 進貨量估算
     |----------------------------------------------------------------
     |
     */
    public function estimate(){

        $pageTitle = '進貨單預估';
        
        $isAdmin    = False;
        $allDealers = [];
        $DealerId   = '';

        //判斷當下使用者是系統方會員還是經銷商會員
        if( Auth::user()->hasRole('Admin') ){
            
            // 如果是系統方管理者則將變數改為True    
            $isAdmin = True;

            // 系統方管理員需要多取出經銷商資料
            $allDealers = Role::where('name','Dealer')->first()->users()->get();
            $allDealers = $allDealers->toArray();


        }elseif( Auth::user()->hasRole('Dealer') ){

        	$DealerId = Auth::id();


        }

        $reference = (empty( $request->dayNum )  || $request->dayNum < 0 )?  30 : $request->dayNum;
        $safeDays  = (empty( $request->average ) || $request->average < 0 )? 30 : $request->average;

        return view('purchaseEstimate')->with([ 'title'      => $pageTitle,
        	                                    'isAdmin'    => $isAdmin,
        	                                    'allDealers' => $allDealers,
        	                                    'DealerId'   => $DealerId,
                                                'reference'  => $reference,
                                                'safeDays'   => $safeDays

        ]);        
    }




    /*----------------------------------------------------------------
     | 進貨量估算實作
     |----------------------------------------------------------------
     |
     */
    public function estimateDo( Request $request ){


        // 表單驗證
        $errText = '';

        $validator = Validator::make($request->all(), [

            'dealerId' => 'required|min:0|exists:users,id',

        ],[
            'dealerId.required'=> '缺少經銷商編號',
            'dealerId.min'     => '尚未選擇經銷商',
            'dealerId.exists'  => '經銷商不存在',
            
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors();
                
            foreach( $errors->all() as $message ){
                    
                $errText .= "$message<br>";
            
            }
            //return back()->with('errorMsg', $errText );
        }
        $pageTitle = '進貨單預估';
        
        $isAdmin    = False;
        $allDealers = [];
        $DealerId   = '';

        //判斷當下使用者是系統方會員還是經銷商會員
        if( Auth::user()->hasRole('Admin') ){
            
            if( !empty($errText) ){

            	return back()->with('errorMsg', $errText );
            }

            // 如果是系統方管理者則將變數改為True    
            $isAdmin = True;

            // 系統方管理員需要多取出經銷商資料
            $allDealers = Role::where('name','Dealer')->first()->users()->get();
            $allDealers = $allDealers->toArray();

            // 取出所選經銷商資料
            $tmpDealer = User::find( $request->dealerId );

            $dealerPhone   = $tmpDealer->phone;

            $dealerAddress = $tmpDealer->address;            

        }elseif( Auth::user()->hasRole('Dealer') ){
            
            // 如果當下操作者是經銷商身分 , 則需要判斷要產生的是否為自己的進貨單
            if( !$this->chkDealer( $request->dealerId ) ){

                $errText .= "要產生進貨單的帳戶與目前使用者不同 , 請勿嘗試非法操作<br>";
            }
            if( !empty($errText) ){

            	return back()->with('errorMsg', $errText );
            }
            
            $DealerId = Auth::id();

            $dealerPhone   = Auth::user()->phone;

            $dealerAddress = Auth::user()->address;

        }

        $dealerId = $request->dealerId;
        
        $reference = (empty( $request->dayNum )  || $request->dayNum < 0 )?  30 : $request->dayNum;
        $safeDays  = (empty( $request->average ) || $request->average < 0 )? 30 : $request->average;
        
        $limitDate = date('Y-m-d ',strtotime("-$reference days"));

        // 取出時間內所有經銷商會員的訂單
        $saleDatas  =   Order::select('order_goods.gid',DB::raw('SUM(order_goods.num) as total_sales') , 'goods.name','goods.goods_sn')
                    ->leftJoin('order_goods', function($join) {
                        
                        $join->on('order.id', '=', 'order_goods.oid');
                    })
                    ->leftJoin('goods', function($join) {
                        
                        $join->on('order_goods.gid', '=', 'goods.id');
                    })
                    ->where('dealer_id',$dealerId)
                    ->where('order.status','3')
                    ->where('order.created_at','>=',$limitDate)
                    ->groupBy('order_goods.gid')
                    ->get();
        

        $saleDatas  = $saleDatas->toArray();


        $goodsId    = [];
        $goodsName  = [];
        $goodsSn    = [];
        $salesNum   = [];
        $needNum    = [];

        foreach ($saleDatas as $saleDatak => $saleData ) {
        
            $goodsId[]     = $saleData['gid'];
            $goodsName[]   = $saleData['name'];
            $goodsSn[]     = $saleData['goods_sn'];
            $salesNum[]    = $saleData['total_sales'];
            $tmpNeed       = round($saleData['total_sales'] / $reference * $safeDays  );
            $needNum[]     = $tmpNeed;
        }


        return view('purchaseEstimate')->with([ 'title'      => $pageTitle,
        	                                    'isAdmin'    => $isAdmin,
        	                                    'allDealers' => $allDealers,
        	                                    'DealerId'   => $dealerId,
        	                                    'goodsId'    => $goodsId,
        	                                    'goodsName'  => $goodsName,
        	                                    'goodsSn'    => $goodsSn,
        	                                    'salesNum'   => $salesNum,
        	                                    'needNum'    => $needNum,
        	                                    'reference'  => $reference,
        	                                    'safeDays'   => $safeDays,
                                                'dealerPhone' => $dealerPhone,
                                                'dealerAddress'=> $dealerAddress

        ]); 
        
    
    }




    /*----------------------------------------------------------------
     | 產生進貨單
     |----------------------------------------------------------------
     |
     */
    public function newPurchaseOrder( Request $request ){
        
        
        $totalGoods = count($request->goodsId);
        $moreMsg    = [];
        
        for ($i=0; $i < $totalGoods ; $i++) { 

            $goodssort = $i+1;
            $moreMsg["goodsId.$i.exists"] = "第{$goodssort}個商品不存在";

        }
        
        $validator = Validator::make($request->all(), [

            'dealerId'   => 'required|min:0|exists:users,id',
            'goodsId.*'  => 'exists:goods,id',
            'phone'      => 'required|regex:/^09[0-9]{8}$/',
            'address'    => 'required'
  
        ],[
            'dealerId.required'=> '缺少經銷商編號',
            'dealerId.min'     => '尚未選擇經銷商',
            'dealerId.exists'  => '經銷商不存在',
            'phone.required'   => '連絡手機為必填',
            'phone.regex'      => '連絡手機格式錯誤',
            'address.required' => '收件地址為必填'
            
        ]+$moreMsg  );

        if ($validator->fails()) {
                
            $errText = '';

            $errors = $validator->errors();
                
            foreach( $errors->all() as $message ){
                    
                $errText .= "$message<br>";
            }

            return redirect('/purchaseEstimate')->with('errorMsg', $errText );
        }

        // 根據是否為系統方管理員進行不同流程
        if( Auth::user()->hasRole('Admin') ){
            
            // 判斷權限
            if( !Auth::user()->can('purchaseNew') ){

                return redirect('/purchaseEstimate')->with('errorMsg', '帳號無此操作權限 , 如有需要請切換帳號或聯絡管理員增加權限');
            }
            
            // 新增至進貨單
            DB::beginTransaction();

            try {
                
                $loopTime = count( $request->goodsId );
                
                // 進貨單總金額
                $purchaseAmount = 0;

                // 迴圈找出商品資料
                for ($i=0; $i <$loopTime ; $i++) { 

                    // 如果數量大於0 , 就開始取出商品相關資料
                    if( $request->needNum[$i] > 0 ){
                        
                        $tmpGoods = Goods::find( $request->goodsId[$i]);
                        
                        $purchaseAmount += intval($tmpGoods->w_price * $request->needNum[$i]);

                    }
                    
                }

                $dealerData = User::find( $request->dealerId );

                $radmonNUm = rand(0,999999);
                $radmonNUm = str_pad($radmonNUm,6,'0',STR_PAD_LEFT);
                $Purchase  = new Purchase;

                $Purchase->purchase_sn  = date("Ymd").$radmonNUm;
                $Purchase->dealer_id    = $request->dealerId;
                $Purchase->dealer_name  = $dealerData->name;
                $Purchase->amount       = $purchaseAmount;
                $Purchase->status       = 1; // 1.待處理 2.已確認 3.已出貨 4.取消
                $Purchase->phone        = $request->phone;
                $Purchase->address      = $request->address;
                $Purchase->save();

                for ($i=0; $i <$loopTime ; $i++) { 

                    // 如果數量大於0 , 就開始取出商品相關資料
                    if( $request->needNum[$i] > 0 ){
                        
                        $tmpGoods = Goods::find( $request->goodsId[$i]);
                        
                        $purchaseAmount += intval($tmpGoods->w_price * $request->needNum[$i]);

                        $PurchaseGoods = new PurchaseGoods;

                        $PurchaseGoods->goods_id    = $tmpGoods->id;
                        $PurchaseGoods->goods_sn    = $tmpGoods->goods_sn;
                        $PurchaseGoods->goods_name  = $tmpGoods->name;
                        $PurchaseGoods->w_price     = $tmpGoods->w_price;
                        $PurchaseGoods->num         = intval( $request->needNum[$i] );
                        $PurchaseGoods->subtotal    = intval( $tmpGoods->w_price * $request->needNum[$i]);
                        $PurchaseGoods->purchase_sn = $Purchase->id;
                        $PurchaseGoods->save();

                    }
                    
                }                
            
                DB::commit();
                
                return redirect('/purchaseEstimate')->with('successMsg', '進貨單新增成功');

            } catch (Exception $e) {

                DB::rollback();
                //$e->getMessage();

                // 寫入錯誤代碼後轉跳
            
                logger("{$e->getMessage()} \n\n-----------------------------------------------\n\n"); 
            
                return redirect('/purchaseEstimate')->with('errorMsg', '進貨單新增失敗 , 請稍後再嘗試');          
            }  


        }elseif( Auth::user()->hasRole('Dealer') ){
            
            // 確認是否為自己的進貨單
            if( !$this->chkDealer( $request->dealerId ) ){
                return redirect('/purchaseEstimate')->with('errorMsg', '要產生進貨單的帳戶與目前使用者不同 , 請勿嘗試非法操作');
            }

            // 新增至進貨單
            DB::beginTransaction();

            try {
                
                $loopTime = count( $request->goodsId );
                
                // 進貨單總金額
                $purchaseAmount = 0;

                // 迴圈找出商品資料
                for ($i=0; $i <$loopTime ; $i++) { 

                    // 如果數量大於0 , 就開始取出商品相關資料
                    if( $request->needNum[$i] > 0 ){
                        
                        $tmpGoods = Goods::find( $request->goodsId[$i]);
                        
                        $purchaseAmount += intval($tmpGoods->w_price * $request->needNum[$i]);

                    }
                    
                }

                $dealerData = User::find( $request->dealerId );

                $radmonNUm = rand(0,999999);
                $radmonNUm = str_pad($radmonNUm,6,'0',STR_PAD_LEFT);
                $Purchase  = new Purchase;

                $Purchase->purchase_sn  = date("Ymd").$radmonNUm;
                $Purchase->dealer_id    = $request->dealerId;
                $Purchase->dealer_name  = $dealerData->name;
                $Purchase->amount       = $purchaseAmount;
                $Purchase->status       = 1; // 1.待處理 2.已確認 3.已出貨 4.取消
                $Purchase->phone        = $request->phone;
                $Purchase->address      = $request->address;                
                $Purchase->save();

                for ($i=0; $i <$loopTime ; $i++) { 

                    // 如果數量大於0 , 就開始取出商品相關資料
                    if( $request->needNum[$i] > 0 ){
                        
                        $tmpGoods = Goods::find( $request->goodsId[$i]);
                        
                        $purchaseAmount += intval($tmpGoods->w_price * $request->needNum[$i]);

                        $PurchaseGoods = new PurchaseGoods;

                        $PurchaseGoods->goods_id    = $tmpGoods->id;
                        $PurchaseGoods->goods_sn    = $tmpGoods->goods_sn;
                        $PurchaseGoods->goods_name  = $tmpGoods->name;
                        $PurchaseGoods->w_price     = $tmpGoods->w_price;
                        $PurchaseGoods->num         = intval( $request->needNum[$i] );
                        $PurchaseGoods->subtotal    = intval( $tmpGoods->w_price * $request->needNum[$i]);
                        $PurchaseGoods->purchase_sn = $Purchase->id;
                        $PurchaseGoods->save();

                    }
                    
                }                
            
                DB::commit();
                
                return redirect('/purchaseEstimate')->with('successMsg', '進貨單新增成功');

            } catch (Exception $e) {

                DB::rollback();
                //$e->getMessage();

                // 寫入錯誤代碼後轉跳
            
                logger("{$e->getMessage()} \n\n-----------------------------------------------\n\n"); 
            
                return redirect('/purchaseEstimate')->with('errorMsg', '進貨單新增失敗 , 請稍後再嘗試');          
            }            
            
        }
    }
    


    /*----------------------------------------------------------------
     | 確認要經銷商是否與當下的操作者相同
     |----------------------------------------------------------------
     |
     */
    public function chkDealer( $_dealer ){

    	if( $_dealer == Auth::id() ){

            return True;

    	}else{

            return False;
    	}
    }




    /*----------------------------------------------------------------
     | 確認是否為進貨單擁有者
     |----------------------------------------------------------------
     |
     */
    public function chkPurchase( $_purchaseId ){
        
        $currentPurchase = Purchase::find( $_purchaseId );

        if( Auth::id() == $currentPurchase->dealer_id ){

            return true;

        }else{
            
            return false;
        }
    }
}
