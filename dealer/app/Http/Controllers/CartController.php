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
use App\OrderLog;
use \Exception;
use Excel;
use App\GoodsPic;
use App\GoodsCat;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;

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
        
        // 取出最新商品
        // 取出代理商有庫存之商品
        $haveStockGoods = GoodsStock::where('dealer_id',$cartUser)->where('goods_num','>',0)->get();

        if( count($haveStockGoods) > 0 ){

            $haveStockGoods = json_decode($haveStockGoods,true);
        }

        $goodsCanShow = [];

        foreach ($haveStockGoods as $haveStockGood) {

            array_push( $goodsCanShow , $haveStockGood['goods_id'] );

        }
        
        // 最新商品
        $newGoods = Goods::whereIn('id',$goodsCanShow)->orderBy('created_at', 'desc')->limit(8)->get();

        if( count($newGoods) > 0){

            $newGoods = json_decode($newGoods,true);

            foreach ($newGoods as $newGoodk=> $newGood) {

                // 確認經銷商有無設定價格
                $goodsPrice = GoodsPrice::where('dealer_id',$cartUser)->where('goods_id',$newGood['id'])->first();
                
                if( $goodsPrice == NULL){

                    $goodsPrice = round($dealerDatas['multiple'] * $newGood['w_price']);

                }else{
        
                    $goodsPrice ->toArray();
                    $goodsPrice = $goodsPrice->price;
                }
                
                $newGoods[$newGoodk]['goodsPrice'] = $goodsPrice;
            }

        }else{
            $newGoods = [];
        }
        
        // 熱銷商品
        $allOrders = Order::where('dealer_id',$cartUser)->get();
        $allOrderArr = [];
        $hots = [];
        if( count($allOrders) > 0 ){

            foreach ($allOrders as $allOrder ) {

                array_push($allOrderArr, $allOrder['id']);

            }
 
             //
            $hots = OrderGoods::whereIn('oid',$allOrderArr)->groupBy('gid')->orderBy('total','DESC')->limit(8)->selectRaw('gid, SUM(num) as total')->get();
            
            if( count($hots) > 0){

                $hots = json_decode($hots,true);
                $hotArr = [];
                foreach ($hots as $hot) {

                    array_push($hotArr, $hot['gid']);
                }
                
                $hots = Goods::whereIn('id',$hotArr)->limit(8)->get();

                foreach ($hots as $hotk=> $hot) {
    
                    // 確認經銷商有無設定價格
                    $goodsPrice = GoodsPrice::where('dealer_id',$cartUser)->where('goods_id',$hot['id'])->first();
                    
                    if( $goodsPrice == NULL){
    
                        $goodsPrice = round($dealerDatas['multiple'] * $hot['w_price']);
    
                    }else{
            
                        $goodsPrice ->toArray();
                        $goodsPrice = $goodsPrice->price;
                    }
                    
                    $hots[$hotk]['goodsPrice'] = $goodsPrice;
                }                

            }else{
                $hots = [];
            }
            
        }

        // 熱銷商品結束

        // 推薦商品
        $recommendGoods = Goods::whereIn('id',$goodsCanShow)->where('recommend','1')->orderBy('created_at', 'desc')->limit(8)->get();
        
        if( count($recommendGoods) > 0){

            $recommendGoods = json_decode($recommendGoods,true);

            foreach ($recommendGoods as $recommendGoodk=> $recommendGood) {

                // 確認經銷商有無設定價格
                $goodsPrice = GoodsPrice::where('dealer_id',$cartUser)->where('goods_id',$recommendGood['id'])->first();
                
                if( $goodsPrice == NULL){

                    $goodsPrice = round($dealerDatas['multiple'] * $recommendGood['w_price']);

                }else{
        
                    $goodsPrice ->toArray();
                    $goodsPrice = $goodsPrice->price;
                }
                
                $recommendGoods[$recommendGoodk]['goodsPrice'] = $goodsPrice;
            }

        }else{

            $recommendGoods = [];
        }

        return view('cartIndex')->with([ 'dealerDetect' => $request->name,
        	                             'cartUser' =>$cartUser,
        	                             'dealerDatas' => $dealerDatas,
        	                             'categorys' => $categorys,
                                         'newGoods'=>$newGoods,
                                         'recommendGoods'=>$recommendGoods,
                                         'hots'=>$hots,
                                         'color'=>'yellow'
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

        //取出所有附圖
        $goodPics = GoodsPic::where('gid',$request->goodsId) ->orderBy('sort', 'asc')->get();
        if( count($goodPics) > 0){
            $goodPics = json_decode($goodPics ,true);
        }

        return view('cartGoods')->with([ 'dealerDetect' => $request->name,
                                         'cartUser'     => $cartUser, 
                                         'dealerDatas'  => $dealerDatas,
                                         'categorys'    => $categorys,
                                         'goods'        => $goods,
                                         'goodPics'     => $goodPics,
                                        ]); 
     }



    /*----------------------------------------------------------------
     | 添加至購物車
     |----------------------------------------------------------------
     |
     */
    public function addToCart( Request $request ){
        
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

        // 取出商品相關資訊
        $goodsDetail = Goods::find( $request->goodsId);
        
        if( $goodsDetail == NUll){

            echo json_encode( ['res'=>False , 'msg'=>'查無此商品' , ] );
            exit;
        }
        


        // 檢查是否有數量
        $goodsStock = GoodsStock::where('dealer_id',$cartUser)->where('goods_id',$request->goodsId)->first();

        if( $goodsStock == NUll){

            echo json_encode( ['res'=>False , 'msg'=>'此商品目前無庫存' , ] );
            exit;

        }else{
            
            if( ($goodsStock->goods_num - $request->goodsNum ) < 0 ){

                echo json_encode( ['res'=>False , 'msg'=>'目前此商品庫存只剩'.$goodsStock->goods_num.'個 , 請調整訂購數量' , ] );
                exit;

            }
        }

        if( $request->goodsNum <= 0 ){

            echo json_encode( ['res'=>False , 'msg'=>'尚未選取數量無法加入購物車' , ] );
            exit;            
        }

        // 確認經銷商有無設定價格
        $goodsPrice = GoodsPrice::where('dealer_id',$cartUser)->where('goods_id',$request->goodsId)->first();
        
        if( $goodsPrice == NULL){

            $goodsPrice = round($dealerDatas['multiple'] * $goodsDetail['w_price']);

        }else{

            $goodsPrice ->toArray();
            $goodsPrice = $goodsPrice->price;
        }


        // 先判斷SESSION 存不存在 , 如果存在就直接更改 , 如果不存在就新增一組
        if( $request->session()->has('carts') ){ 
 
            $tmpcart = $request->session()->get('carts');
            
            // 判斷是否已經存在購物車
            
            if( array_key_exists("$request->goodsId", $tmpcart) ) {
                
                // 如果有接收到完成數則直接用完成數做最後訂單數即可
                if( isset($request->complete) && $request->complete == True ){
                    
                    $totalNum =$request->goodsNum;
                    
                }else{

                    $totalNum = $tmpcart[$request->goodsId]['num'] + $request->goodsNum;
                }
                
                
                if( ($goodsStock->goods_num - $totalNum ) < 0 ){
                
                    echo json_encode( ['res'=>False , 'msg'=>'目前此商品庫存只剩'.$goodsStock->goods_num.'個 , 請調整訂購數量' , ] );
                    exit;

                }
                
                $tmpcart[$request->goodsId]['num'] = $totalNum;
                $tmpcart[$request->goodsId]['goodsPrice'] = $goodsPrice;
                $tmpcart[$request->goodsId]['subTotal'] = round($goodsPrice * $totalNum);
                $tmpcart[$request->goodsId]['stock'] = $goodsStock->goods_num;
            }else{

                $tmpcart[$request->goodsId] = [ 'name' => $goodsDetail->name,
                                                'thumbnail' =>$goodsDetail->thumbnail,
                                                'num' =>$request->goodsNum,
                                                'goodsSn'=>$goodsDetail->goods_sn,
                                                'goodsPrice'=>$goodsPrice,
                                                'subTotal'=> round($request->goodsNum * $goodsPrice),
                                                'id'=>$goodsDetail['id'],
                                                'stock'=>$goodsStock->goods_num,
                                              ];
            }

            $request->session()->put('carts', $tmpcart);
            
            if( isset($request->complete) && $request->complete == True ){
                
                return json_encode( ['res'=>True , 'msg'=>'編輯購物車成功' , 'cartDatas'=> $tmpcart ] );

            }else{

                return json_encode( ['res'=>True , 'msg'=>'添加至購物車成功' , 'cartDatas'=> $tmpcart ] );
            }
        }else{

            $tmpcart = [];

            $tmpcart[$request->goodsId] = [ 'name' => $goodsDetail->name,
                                            'thumbnail' =>$goodsDetail->thumbnail,
                                            'num' =>$request->goodsNum,
                                            'goodsSn'=>$goodsDetail->goods_sn,
                                            'goodsPrice'=>$goodsPrice,
                                            'subTotal'=> round($request->goodsNum * $goodsPrice),
                                            'id'=>$goodsDetail['id'],
                                            'stock'=>$goodsStock->goods_num,
                                          ];

            $request->session()->put('carts', $tmpcart);

            return json_encode( ['res'=>True , 'msg'=>'添加至購物車成功' , 'cartDatas'=> $tmpcart ] );
        }
    }




    /*----------------------------------------------------------------
     | 移除購物車商品
     |----------------------------------------------------------------
     |
     */
    public function deleteItem( Request $request){
        
        if( $request->session()->has('cartUser') ){

            $cartUser =  $request->session()->pull('cartUser');

        }else{

            exit;
        } 


        if( $request->session()->has('carts') ){
            
            $tmpcart = $request->session()->get('carts');
            

            if( array_key_exists($request->goodsId, $tmpcart) ){
                

                unset( $tmpcart[ $request->goodsId ] );

                $request->session()->put('carts', $tmpcart);
            
            }

            return json_encode( ['res'=>True , 'msg'=>'購物車項目刪除成功' , 'cartDatas'=> $tmpcart ] );

        }else{
            
            return json_encode( ['res'=>False , 'msg'=>'購物車目前無商品' , ] );
        }

    }




    /*----------------------------------------------------------------
     | 結帳頁面 
     |----------------------------------------------------------------
     |
     */
    public function checkout( Request $request ){

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

        // 購物車
        $carts = [];

        if( $request->session()->has('carts') ){
            
            $carts = $request->session()->get('carts');
            
            /*foreach ($carts as $cartk => $cart) {

                // 確認經銷商有無設定價格
                $goodsPrice = GoodsPrice::where('dealer_id',$cartUser)->where('goods_id',$cart['id'])->first();
                
                if( $goodsPrice == NULL){
        
                    $goodsPrice = round($dealerDatas['multiple'] * $goods['w_price']);
        
                }else{
        
                    $goodsPrice ->toArray();
                    $goodsPrice = $goodsPrice->price;
                }

                $carts[$cartk]['goodsPrice'] = $goodsPrice;
            }*/

            // 取出經銷商目前庫存
            foreach ($carts as $cartk => $cart) {

                $tmpStock = GoodsStock::where('dealer_id',$cartUser)->where('goods_id',$cart['id'])->first();

                if( $tmpStock != NULL){

                    $tmpStock = $tmpStock->goods_num;

                }else{

                    $tmpStock = 0;
                }

                $carts[$cartk]['stock'] = $tmpStock;
            }
            
        }
        
        return view('cartCheckout')->with([ 'dealerDetect' => $request->name,
                                            'cartUser'     => $cartUser, 
                                            'dealerDatas'  => $dealerDatas,
                                            'categorys'    => $categorys,
                                            'carts'=> $carts
                                        ]); 

    }
 


    /*----------------------------------------------------------------
     | 新增訂單
     |----------------------------------------------------------------
     |
     */
    public function newOrder( Request $request ){
        

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
        
        // 檢驗資料
        $validator = Validator::make($request->all(), [
            'room'    => 'required',
            'payway'    => 'required',
        ],[
            'room.required' => '房號為必填',
            'payway.required' => '付款方式為必填',
        ]  );
        
        $errText = '';
        
        if ($validator->fails()) {
                
            $errors = $validator->errors();
                
            foreach( $errors->all() as $message ){
                    
                $errText .= "$message<br>";
            }

        }        
        
        // 檢查是否有購物車
        if( $request->session()->has('carts') ){ 
 
            $tmpcarts = $request->session()->get('carts');

            if( count($tmpcarts) == 0){

                $errText .= "購物車為空<br>";
            }
        
        }else{

            $errText .= "購物車為空<br>";
        }

        // 檢查是否每個商品庫存都足夠
        foreach ($tmpcarts as $tmpcartK => $tmpcart) {

            // 檢查是否有數量
            $goodsStock = GoodsStock::where('dealer_id',$cartUser)->where('goods_id',$tmpcart['id'])->first();
            
            if( $goodsStock == NULL){

                $errText .= "商品:{$tmpcart['name']} 目前庫存為 0 , 請修改購買數量";

            }else{

                if( $tmpcart['num'] > $goodsStock->goods_num){

                    $errText .= "商品:{$tmpcart['name']} 目前庫存為 {$$goodsStock->goods_num} , 請修改購買數量";
                }

            }
        }

        if( !empty( $errText ) ){

            return redirect("/{$request->name}/checkout")->with('errorMsg', $errText );

        }    
        
        // 開始新增訂單
        DB::beginTransaction();
        
        $orderId = $this->createOrder( $cartUser , $request->room , $request->payway ,$request->note );

        try {
            
            // 迴圈寫入商品
            foreach ($tmpcarts as $tmpcartK => $tmpcart) {
                
                // 如果商品數量小於等於0 , 商品直接跳過
                if( $tmpcart['num'] <= 0){

                    continue;

                }

                $OrderGoods           = new OrderGoods;

                $OrderGoods->oid      = $orderId;
    
                $OrderGoods->gid      = $tmpcart['id'];
            
                $OrderGoods->goods_sn = $tmpcart['goodsSn'];
            
                $OrderGoods->name     = $tmpcart['name'];
                
                $OrderGoods->price    = $tmpcart['goodsPrice'];
                  
                $OrderGoods->num      = $tmpcart['num'];
                
                $OrderGoods->subtotal = round( $tmpcart['goodsPrice'] * $tmpcart['num'] );

                $OrderGoods->save();
                
            }
            
            // 重新計算訂單總價
            $OrderGoods = OrderGoods::where( 'oid' , $orderId)->get();
            $OrderGoods = $OrderGoods->toArray();
                
            $orderAmount = 0;

            foreach ( $OrderGoods as $OrderGood ) {
                    
                $orderAmount += $OrderGood['subtotal'];

            }
                
            $Order = Order::find( $orderId );
            $Order->amount = $orderAmount;
            $Order->final_amount = $orderAmount - $Order->discount;

            $Order->save();

            DB::commit();

            $request->session()->flash('orderSn', $Order->order_sn);
            $request->session()->flash('orderAmount', $Order->amount);
            $request->session()->forget('carts');
            
            // 如果有旅館信箱 , 則發信通知
            if( !empty($dealerDatas['hotel_email']) ){
                $to      = $dealerDatas['hotel_email'];
                $subject = '新訂單通知';

                $message = "接收到一筆新訂單 , \r\n";
                $message .= "訂單編號:{$Order->order_sn} , 房號: {$Order->room} , 總金額: {$Order->final_amount} \r\n";

                $headers = 'From: admin@ilover520.com' . "\r\n" ;

                mail($to, $subject, $message, $headers);
            }
            return redirect("/{$request->name}/thank")->with('successMsg', '訂單已送出');

        }catch(\Exception $e){
                
            DB::rollback();
            //$e->getMessage();

            // 寫入錯誤代碼後轉跳
            
            logger("{$e->getMessage()} \n\n-----------------------------------------------\n\n"); 
            
            return redirect("/{$request->name}/checkout")->with('errorMsg', '訂單新增失敗,請重新下單');
        }

        /*return view('cartCheckout')->with([ 'dealerDetect' => $request->name,
                                            'cartUser'     => $cartUser, 
                                            'dealerDatas'  => $dealerDatas,
                                            'categorys'    => $categorys,
                                        ]);
        */
    }
    



    /*----------------------------------------------------------------
     | 感謝訂購畫面
     |----------------------------------------------------------------
     |
     */
    public function thank( Request $request ){

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
        
        return view('cartThank')->with([ 'dealerDetect' => $request->name,
                                         'cartUser'     => $cartUser, 
                                         'dealerDatas'  => $dealerDatas,
                                         'categorys'    => $categorys,
                                         'orderSn'      => $request->session()->get('orderSn'),
                                         'orderAmount'  => $request->session()->get('orderAmount'),
                                       ]);           
    }
    



    /*----------------------------------------------------------------
     | 分類搜尋
     |----------------------------------------------------------------
     |
     */
    public function cartCategory( Request $request ){
        
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
        
        // 確認頁數
        $page = 1;

        if( isset($request->page) && !empty($request->page)){

            $page = $request->page;
        }
        
        // 先取出有庫存之商品存成陣列
        $tmpRes = GoodsStock::where('dealer_id',$cartUser)->where("goods_num",">=",0)->get();
        $canSells = [];
        if( count( $tmpRes) > 0 ){
            $tmpRes = json_decode($tmpRes,True);

            foreach ($tmpRes as $tmpRe) {
                array_push($canSells, $tmpRe['goods_id']);
            }
        }

        $showNum = 20;
        
        $start = ( $page - 1 ) * $showNum;
        
        $orderBy = 'created_at';

        // 計算總筆數
        $total = Goods::where('cid',$request->cid)->whereIn('id',$canSells)->count();
        
        // 計算總頁數
        $totalPage = ceil($total/$showNum);

        // 取出要呈現的商品
        $goods = Goods::where('cid',$request->cid)->whereIn('id',$canSells)->offset( $start )->limit( $showNum )->orderBy( $orderBy )->get();
        
        // 計算價格
        foreach ($goods as $goodk => $good) {
            
            $goodsPrice = GoodsPrice::where('dealer_id',$cartUser)->where('goods_id',$good['id'])->first();
                
                if( $goodsPrice == NULL){

                    $goodsPrice = round($dealerDatas['multiple'] * $good['w_price']);

                }else{

                    $goodsPrice ->toArray();

                    $goodsPrice = $goodsPrice->price;
                }
                
                $goods[$goodk]['goodsPrice'] = $goodsPrice;
        }
    
        $plist= $this->createPlist(url("/{$request->name}/cartCategory/$request->cid") , $page , $totalPage);

        // 取出分類名稱 
        $cateName = '無此分類';
        $category = Category::find( $request->cid );
        if( $category != NULL ){
            $cateName = $category->name;
        }

        return view('cartCategory')->with([ 'dealerDetect' => $request->name,
                                            'cartUser'     => $cartUser, 
                                            'dealerDatas'  => $dealerDatas,
                                            'categorys'    => $categorys,
                                            'goods'        => $goods,
                                            'plist'        => $plist,
                                            'cateName'     => $cateName,
                                        ]);
    }

    


    /*----------------------------------------------------------------
     | 關鍵字搜尋
     |----------------------------------------------------------------
     |
     */
    public function cartSearch( Request $request ){

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
        
        // 確認頁數
        $page = 1;

        if( isset($request->page) && !empty($request->page)){

            $page = $request->page;
        }
        
        // 先取出有庫存之商品存成陣列
        $tmpRes = GoodsStock::where('dealer_id',$cartUser)->where("goods_num",">=",0)->get();
        $canSells = [];
        if( count( $tmpRes) > 0 ){
            $tmpRes = json_decode($tmpRes,True);

            foreach ($tmpRes as $tmpRe) {
                array_push($canSells, $tmpRe['goods_id']);
            }
        }

        $showNum = 20;
        
        $start = ( $page - 1 ) * $showNum;
        
        $orderBy = 'created_at';

        $keyword = Input::get('keyword', false);

        // 計算總筆數
        $total = Goods::where(function ($query) use ($keyword) {
                      
                      $query->where('name','like', "%{$keyword}%"  )
                      ->orWhere('goods_sn', 'like', "%{$keyword}%" );

                 })->whereIn('id',$canSells)->count();
        
        // 計算總頁數
        $totalPage = ceil($total/$showNum);

        


        // 取出要呈現的商品
        $goods = Goods::where(function ($query) use ($keyword) {
                      
                      $query->where('name','like', "%{$keyword}%"  )
                      ->orWhere('goods_sn', 'like', "%{$keyword}%" );

                 })->whereIn('id',$canSells)->offset( $start )->limit( $showNum )->orderBy( $orderBy )->get();
        
        // 計算價格
        foreach ($goods as $goodk => $good) {
            
            $goodsPrice = GoodsPrice::where('dealer_id',$cartUser)->where('goods_id',$good['id'])->first();
                
                if( $goodsPrice == NULL){

                    $goodsPrice = round($dealerDatas['multiple'] * $good['w_price']);

                }else{

                    $goodsPrice ->toArray();

                    $goodsPrice = $goodsPrice->price;
                }
                
                $goods[$goodk]['goodsPrice'] = $goodsPrice;
        }
    
        $plist= $this->createPlist(url("/{$request->name}/cartSearch/") , $page , $totalPage , '' , "/?keyword=$keyword");

        // 取出分類名稱 
        $cateName = "搜尋:$keyword - 結果";


        return view('cartCategory')->with([ 'dealerDetect' => $request->name,
                                            'cartUser'     => $cartUser, 
                                            'dealerDatas'  => $dealerDatas,
                                            'categorys'    => $categorys,
                                            'goods'        => $goods,
                                            'plist'        => $plist,
                                            'cateName'     => $cateName,
                                        ]);
    }
    



    /*----------------------------------------------------------------
     | 文章
     |----------------------------------------------------------------
     |
     */
    public function article( Request $request ){
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
        
        if( isset($request->aid) && !empty($request->aid) ){
            
            $article = Article::find("$request->aid");
            
            if( $article != NUll){
                 
                $article = $article->toArray();
            }else{

                return back();
            }
        }else{

            return back();
        }
        

        return view('cartArticle')->with([ 'dealerDetect' => $request->name,
                                            'cartUser'     => $cartUser, 
                                            'dealerDatas'  => $dealerDatas,
                                            'categorys'    => $categorys,
                                            'article'      => $article

                                        ]);

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
        
        if( $request->session()->has('carts') ){

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
                     'sortname' => $allParent['sortname'],
                     'desc' => $allParent['desc'],
                     'status' => $allParent['status'],
                     'updated_at' => $allParent['updated_at'],
                     'level' => '',
                     'levelIcon' => '',
                     'category_pic' => $allParent['category_pic'],
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




    /*
    */
    public function createOrder( $_dealerId , $_room , $_payway , $_note='' ){
        
        $retrunID = '';

        $createSwitch = True; 
        $note = '';
        if( !empty($_note) ){
            $note = $_note;
        }
        while( $createSwitch ){
            
            //$orderSn =
            
            $radmonNUm = rand(0,999999);
            $radmonNUm = str_pad($radmonNUm,6,'0',STR_PAD_LEFT);
            
            try {
            
              
                $Order = new Order;

                $Order->dealer_id  = "$_dealerId";
                $Order->order_sn   = date("Ymd").$radmonNUm;
                $Order->room       = $_room;
                $Order->amount     = 0;
                $Order->status     = '2';
                $Order->source     = '1'; 
                $Order->payway     = $_payway;
                $Order->note       = $note;
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
    



    /*----------
     |
     |
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

        $roleName = 'General';
        
        $OrderLog = new OrderLog;

        $OrderLog->user_id      = 0;
        $OrderLog->user_name    = '一般用戶';
        $OrderLog->user_role    = $roleName;
        $OrderLog->order_id     = $_orderId;
        $OrderLog->order_status = $_orderStatus;
        $OrderLog->desc         = $operationDesc;

        $OrderLog->save();
        
    }    
    



    /*----------------------------------------------------------------
     | 產生分頁表
     |----------------------------------------------------------------
     |
     */
    public function createPlist( $url , $now , $totalPage , $length = 2 , $add = ''){
        
        if( empty($length) ){

            $length = 5;
        }
        $plist = '<nav aria-label="Page navigation"><ul class="pagination">
                  <li>
                  <a href="'.$url.'/1'.$add.'" aria-label="Previous">
                  <span aria-hidden="true">&laquo;</span>
                  </a>
                  </li>';
        for ($i=$length; $i > 0 ; $i--) { 
            
            if( ($now - $i) > 0 ){

                $tmpPage = $now - $i;

                $plist .='<li><a href="'.$url.'/'.$tmpPage.$add.'">'.$tmpPage.'</a></li>';
            }

        }
        
        $plist .='<li class="active" ><a href="'.$url.'/'.$now.$add.'">'.$now.'</a></li>';
        
        for ($i=1 ;$i <= $length ; $i++) { 

            if( ($now + $i) <= $totalPage ){
                
                $tmpPage = $now + $i;

                $plist .='<li><a href="'.$url.'/'.$tmpPage.$add.'">'.$tmpPage.'</a></li>';
            }

        }        

        $plist .='<li>
                  <a href="'.$url.'/'.$totalPage.$add.'" aria-label="Next">
                  <span aria-hidden="true">&raquo;</span>
                  </a>
                  </li>
                  </ul>
                  </nav>';
        
        return $plist;

    }



    public function stock( Request $request ){
        exit;

        //  DB::beginTransaction();

        // try {
            $date = date("Y-m-d H:i:s");
            for ($i=1; $i <= 164 ; $i++) { 
                
                GoodsStock::updateOrCreate(
                    ['dealer_id' => 12, 'goods_id' => $i],
                    ['goods_num' => 3]
                );
                
            }

        //     DB::commit();
        // } catch (Exception $e) {
                
        //     logger("{$e->getMessage()} \n\n-----------------------------------------------\n\n"); 
        // }
    }
    public function import( Request $request ){
        exit;
        $datas = Excel::load(public_path('goods.xlsx'), function($reader) {

        // reader methods
            //$reader->first();
            $reader->noHeading();
            //dd($reader->get());
        });

        //$mainpicExtension = $request->file('mainpic')->extension();
        //$request->file('mainpic')->storeAs("logo/{$user->id}/","wlogo.$mainpicExtension",'goodsImage');

        $rows = $datas->toArray();
        $today = '20190514';
        // dd($rows);
        DB::beginTransaction();
        // 女性gigh
        $type1 = ['85','86','87','88','89','90','91','92','94','95','96','98','99','105','106','107','108','109','110','111','112','114','115','116','121','122','123','125','126','127','128','129','130','131','132','134','135','136','137','138','139','140','141','144','146','147','148','149','150','151','152','153','156','157','159','160','161','162','164'];

        // 男性增強
        $type2 = ['93','100','113','117','118','120','124','142','143','145','163'];
        // 另類
        $type3 = ['97','101','102','103','104','119','133','54','55','158'];
        // 睡衣
        $type4 = ['35','36','37','38','39','40','41','42','43','44','45','46','47','48','49','50','51','52','53','54','55','56','57','58','59','60','61','62','63','64','65','66','67','68','69','70','71','72','73','74','75','76','77','78','79','80','81','82','83','84'];
        // 內褲
        $type5 = ['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','32','33','34'];

        try {
            
            foreach ($rows as $rowk => $row) {
                if( in_array($rowk, $type1)){
                    $class='2';
                }elseif(in_array($rowk, $type2)){
                    $class='3';
                }elseif(in_array($rowk, $type3)){
                    $class='4';
                }elseif(in_array($rowk, $type4)){
                    $class='6';
                }elseif(in_array($rowk, $type5)){
                    $class='7';
                }else{
                    $class='2';
                }                    
            if( $rowk > 0){
                
                $goods = new Goods;

                $goods->name       = $row['1'];

                $goods->goods_sn   = $row['3'];

                $goods->cid        = $class;

                $goods->price      = intval($row['5']);

                $goods->w_price    = intval($row['4']);
                
                $tmpExtension1 = explode('.', $row['7'])[1];

                $goods->main_pic  = "main/{$today}/{$today}_{$rowk}.{$tmpExtension1}";

                $tmpExtension0 = explode('.', $row['6'])[1];

                $goods->thumbnail = "thumbnail/{$today}/{$today}_{$rowk}.{$tmpExtension0}";
                $goods->status = 1;
                $goods->created_at = date("Y-m-d H:i:s");

                $goods->updated_at = date("Y-m-d H:i:s");
                $goods->desc = '首批商品';

                $goods->save();

                if( !empty($row['8']) ){

                    $tmpExtension = explode('.', $row['8'])[1];

                    $goodsPic = new GoodsPic;

                    $goodsPic->gid       = $goods->id;

                    $goodsPic->pic       = "images/other/{$today}/{$today}_{$rowk}".'_'."1.$tmpExtension";

                    $goodsPic->sort      = 1;

                    $goodsPic->save();                    
                }
                if( !empty($row['9']) ){

                    $tmpExtension = explode('.', $row['9'])[1];

                    $goodsPic = new GoodsPic;

                    $goodsPic->gid       = $goods->id;

                    $goodsPic->pic       = "images/other/{$today}/{$today}_{$rowk}".'_'."2.$tmpExtension";

                    $goodsPic->sort      = 2;

                    $goodsPic->save();                    
                }
                if( !empty($row['10']) ){

                    $tmpExtension = explode('.', $row['10'])[1];

                    $goodsPic = new GoodsPic;

                    $goodsPic->gid       = $goods->id;

                    $goodsPic->pic       = "images/other/{$today}/{$today}_{$rowk}".'_'."3.$tmpExtension";

                    $goodsPic->sort      = 3;

                    $goodsPic->save();                    
                }                                
                if( !empty($row['11']) ){

                    $tmpExtension = explode('.', $row['11'])[1];

                    $goodsPic = new GoodsPic;

                    $goodsPic->gid       = $goods->id;

                    $goodsPic->pic       = "images/other/{$today}/{$today}_{$rowk}".'_'."4.$tmpExtension";

                    $goodsPic->sort      = 4;

                    $goodsPic->save();                    
                }  
                if( !empty($row['12']) ){

                    $tmpExtension = explode('.', $row['12'])[1];

                    $goodsPic = new GoodsPic;

                    $goodsPic->gid       = $goods->id;

                    $goodsPic->pic       = "images/other/{$today}/{$today}_{$rowk}".'_'."5.$tmpExtension";

                    $goodsPic->sort      = 5;

                    $goodsPic->save();                    
                }  
                if( !empty($row['13']) ){

                    $tmpExtension = explode('.', $row['13'])[1];

                    $goodsPic = new GoodsPic;

                    $goodsPic->gid       = $goods->id;

                    $goodsPic->pic       = "images/other/{$today}/{$today}_{$rowk}".'_'."6.$tmpExtension";

                    $goodsPic->sort      = 6;

                    $goodsPic->save();                    
                } 
                if( !empty($row['14']) ){

                    $tmpExtension = explode('.', $row['14'])[1];

                    $goodsPic = new GoodsPic;

                    $goodsPic->gid       = $goods->id;

                    $goodsPic->pic       = "images/other/{$today}/{$today}_{$rowk}".'_'."7.$tmpExtension";

                    $goodsPic->sort      = 7;

                    $goodsPic->save();                    
                }
                if( !empty($row['15']) ){

                    $tmpExtension = explode('.', $row['15'])[1];

                    $goodsPic = new GoodsPic;

                    $goodsPic->gid       = $goods->id;

                    $goodsPic->pic       = "images/other/{$today}/{$today}_{$rowk}".'_'."8.$tmpExtension";

                    $goodsPic->sort      = 8;

                    $goodsPic->save();                    
                }                                                                                               
            }

            }

            DB::commit();
        
        } catch (Exception $e) {

            logger("{$e->getMessage()} \n\n-----------------------------------------------\n\n"); 
        }

        
    }
    public function rename( Request $request ){
        exit;
        $goods = Goods::orderBy('id')->get();
        $goods = json_decode($goods,true);

        DB::beginTransaction();

        try {

        foreach ($goods as $good) {
            
            $newname = explode('[', $good['name'] )[0];
            
            $flight = Goods::find($good['id']);

            $flight->name = $newname;

            $flight->save();
        
        DB::commit();

        }} catch (Exception $e) {
            
        }
    }
    public function desc( Request $request ){
        
       exit;
        echo "ENTER";
        $goods = Goods::orderBy('id')->get();
        $goods = json_decode($goods,true);

        DB::beginTransaction();

        try {
            
            foreach ($goods as $good) {
            //echo $good['goods_sn'];

                $datas = DB::connection('mysql2')->table('xyzs_goods')->select("cat_id")->where('goods_sn',$good['goods_sn'])->first();
                


                if( $datas != NULL){
                    echo $good['id'];
                    echo '<br>';
                    switch ($datas->cat_id) {
                        case '154':
                            $tmpCat = 10;
                            break;
                        case '155':
                            $tmpCat = 11;
                            break;                        
                        case '156':
                            $tmpCat = 12;
                            break;
                        case '157':
                            $tmpCat = 13;
                            break;
                        case '158':
                            $tmpCat = 14;
                            break;
                        case '159':
                            $tmpCat = 1;
                            break;
                        case '160':
                            $tmpCat = 3;
                            break;
                        case '161':
                            $tmpCat = 6;
                            break;
                        case '162':
                            $tmpCat = 7;
                            break;
                        case '163':
                            $tmpCat = 9;
                            break;
                        case '164':
                            $tmpCat = 4;
                            break;
                        case '165':
                            $tmpCat = 2;
                            break;
                        case '166':
                            $tmpCat = 5;
                            break;    
                        case '167':
                            $tmpCat = 8;
                            break;                                                                                                                                                                                                                                                                                                                                            
                        default:
                            $tmpCat = 1;
                            break;
                    }
                    $flight = Goods::find($good['id']);

                    $flight->cid = $tmpCat;

                    $flight->save();
                }else{
                    //echo $good['goods_sn'].'找不到<br>';
                }

            }

            DB::commit();

        } catch (Exception $e) {
            
        }


        
    }
}

            // 確認檔案存在(縮圖)
                // if( Storage::disk('small')->exists($row['6']) ){
    
                //     $tmpExtension = explode('.', $row['6'])[1];
    
                //     $contents = Storage::disk('small')->get($row['6']);
    
                //     Storage::disk("goodsImage")->put("/images/thumbnail/{$today}/{$today}_{$rowk}.{$tmpExtension}", $contents);
                  
                // }
                // if( Storage::disk('commom')->exists($row['7']) ){
    
                //     $tmpExtension = explode('.', $row['7'])[1];
    
                //     $contents = Storage::disk('commom')->get($row['7']);
    
                //     Storage::disk("goodsImage")->put("/images/main/{$today}/{$today}_{$rowk}.{$tmpExtension}", $contents);
                  
                // }   
                // if( Storage::disk('commom')->exists($row['8']) && !empty($row['8'])){
    
                //     $tmpExtension = explode('.', $row['8'])[1];
    
                //     $contents = Storage::disk('commom')->get($row['8']);
    
                //     Storage::disk("goodsImage")->put("/images/other/{$today}/{$today}_{$rowk}_1.{$tmpExtension}", $contents);
                  
                // }
                // if( Storage::disk('commom')->exists($row['9']) && !empty($row['9'])){
    
                //     $tmpExtension = explode('.', $row['9'])[1];
    
                //     $contents = Storage::disk('commom')->get($row['9']);
    
                //     Storage::disk("goodsImage")->put("/images/other/{$today}/{$today}_{$rowk}_2.{$tmpExtension}", $contents);
                  
                // }
                // if( Storage::disk('commom')->exists($row['10']) && !empty($row['10'])){
    
                //     $tmpExtension = explode('.', $row['10'])[1];
    
                //     $contents = Storage::disk('commom')->get($row['10']);
    
                //     Storage::disk("goodsImage")->put("/images/other/{$today}/{$today}_{$rowk}_3.{$tmpExtension}", $contents);
                  
                // }            
                // if( Storage::disk('commom')->exists($row['11']) && !empty($row['11'])){
    
                //     $tmpExtension = explode('.', $row['11'])[1];
    
                //     $contents = Storage::disk('commom')->get($row['11']);
    
                //     Storage::disk("goodsImage")->put("/images/other/{$today}/{$today}_{$rowk}_4.{$tmpExtension}", $contents);
                  
                // }
                // if( Storage::disk('commom')->exists($row['12']) && !empty($row['12'])){
    
                //     $tmpExtension = explode('.', $row['12'])[1];
    
                //     $contents = Storage::disk('commom')->get($row['12']);
    
                //     Storage::disk("goodsImage")->put("/images/other/{$today}/{$today}_{$rowk}_5.{$tmpExtension}", $contents);
                  
                // }     
                // if( Storage::disk('commom')->exists($row['13']) && !empty($row['13'])){
    
                //     $tmpExtension = explode('.', $row['13'])[1];
    
                //     $contents = Storage::disk('commom')->get($row['13']);
    
                //     Storage::disk("goodsImage")->put("/images/other/{$today}/{$today}_{$rowk}_6.{$tmpExtension}", $contents);
                  
                // }     
                // if( Storage::disk('commom')->exists($row['14']) && !empty($row['14'])){
    
                //     $tmpExtension = explode('.', $row['14'])[1];
    
                //     $contents = Storage::disk('commom')->get($row['14']);
    
                //     Storage::disk("goodsImage")->put("/images/other/{$today}/{$today}_{$rowk}_7.{$tmpExtension}", $contents);
                  
                // }     
                // if( Storage::disk('commom')->exists($row['15']) && !empty($row['15'])){
    
                //     $tmpExtension = explode('.', $row['15'])[1];
    
                //     $contents = Storage::disk('commom')->get($row['15']);
    
                //     Storage::disk("goodsImage")->put("/images/other/{$today}/{$today}_{$rowk}_8.{$tmpExtension}", $contents);
                  
                // }  