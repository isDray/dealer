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

// 使用商品類別輔助工具
use App\freeHelper\categoryTool;

// 使用商品輔助工具
use App\freeHelper\goodsTool;


class PriceController extends Controller
{
    /*----------------------------------------------------------------
     | 所有商品清單
     |----------------------------------------------------------------
     |
     */
    public function index( Request $request ){
        
        $dfInput = $request->input();
        
        $dfStock = 0;
        
        if( isset($dfInput['stock']) ){

            $dfStock = $dfInput['stock'];
        }

        $dfCompare = "";

        if( isset($dfInput['compare']) ){

            echo $dfCompare = $dfInput['compare'];
        }

        $pageTitle = '商品售價列表';

        if( Auth::user()->hasRole('Admin') ){
            
            exit;

        }elseif( Auth::user()->hasRole('Dealer') ){

        }else{

            return back()->with('errorMsg', ' 無此權限 , 請勿嘗試非法操作' ); 
        
        }
        // 取出所有分類
        $categorys = categoryTool::getAllCategoryForSelect();
        return view('priceList')->with([ 'title'     => $pageTitle,
                                         'dfStock'   => $dfStock,
                                         'dfCompare' => $dfCompare,
                                         'categorys' => $categorys
                                         //'goods'   => $dealers
                                       ]); 
    }




    /*----------------------------------------------------------------
     | 商品查詢
     |----------------------------------------------------------------
     |
     */
    public function query( Request $request ){

        if( Auth::user()->hasRole('Admin') ){
            
            exit;

        }elseif( Auth::user()->hasRole('Dealer') ){

        }else{

            return back()->with('errorMsg', ' 無此權限 , 請勿嘗試非法操作' ); 
        
        }
        
        // 先取出自身倍數
        $tmpMutiple = Dealer::where('dealer_id',Auth::id())->first();
        $tmpMutiple = $tmpMutiple->multiple;
        
        $orderItems  = [
                        '0'=>'id',
                        '3'=>'w_price',
                        '4'=>'selfPrice',
                        '5'=>'allStock',
                        '6'=>'updated_at',
                      ];
        
        // 整理排序關鍵字
        if( array_key_exists($request->order['0']['column'], $orderItems )){

            $orderBy = $orderItems[ $request->order['0']['column'] ];
        
        }else{

            $orderBy = '';
        }

        $orderWay = $request->order['0']['dir'];

        $query = DB::table('goods as g');
        
        $query->leftJoin('goods_cat as gc', 'g.id', '=', 'gc.gid');

        $query->leftJoin(  DB::raw("(SELECT goods_id , goods_num as  allStock FROM goods_stock  WHERE dealer_id = ".Auth::id()." )AS gs"),'gs.goods_id','=','g.id' );

        $query->leftJoin(  DB::raw("(SELECT goods_id , price as selfPrice FROM goods_price  WHERE dealer_id = ".Auth::id()." )AS gp"),'gp.goods_id','=','g.id' );

        $recordsTotal = $query->count();

        // 針對庫存做過濾
        

        /*if( isset($request->stock) && !empty($request->stock) ){

            $stockGoodsArr = [];

            if( $request->stock == 1 ){
                
                $stockGoods = GoodsStock::where('dealer_id',Auth::id())->where('goods_num','>',1)->get();
            }

            if( $request->stock == 2 ){
                
                $stockGoods = GoodsStock::where('dealer_id',Auth::id())->where('goods_num','<=',1)->get();
            }
            
            if( $request->stock == 3 ){
                
                $stockGoods = GoodsStock::where('dealer_id',Auth::id())->where('goods_num','<= ',0)->get();
            }            
           

            if( count( $stockGoods ) > 0 ){

                $stockGoods = json_decode($stockGoods,true);

                foreach ($stockGoods as $stockGood) {

                    array_push($stockGoodsArr, $stockGood['goods_id']);
                }

            }

        }*/
        
        /* 針對數量做比較 */

        if( isset($request->compare) && !empty($request->compare) && $request->compareStock != '' ){

            //stockGoodsArr = [];

            if( $request->compare == 1){$cps = '>';}

            if( $request->compare == 2){$cps = '=';}

            if( $request->compare == 3){$cps = '<';}  
            

            //$query->where('allStock',$cps,$request->compareStock);
            $query->whereRaw("IFNULL( gs.allStock, 0) $cps {$request->compareStock}");
            /*$stockGoods =  GoodsStock::where('dealer_id',Auth::id())->where('goods_num',$cps,$request->compareStock)->get(); 
            
            if( count( $stockGoods ) > 0 ){

                $stockGoods = json_decode($stockGoods,true);

                foreach ($stockGoods as $stockGood) {

                    array_push($stockGoodsArr, $stockGood['goods_id']);
                }

            } */
        }
        if( isset($request->myKeyword) && !empty($request->myKeyword) ){
               
            $filterSearch = $request->myKeyword;

            $query->where(function ($cond) use ($filterSearch) {
            	
                $cond->where('goods_sn', 'like', "%$filterSearch%");
                     //->orWhere('name', 'like' ,"%$filterSearch%");
            });
        }
        
        if( isset($request->nameKeyword) && !empty($request->nameKeyword) ){
               
            $query->where('g.name','LIKE',"%{$request->nameKeyword}%");
        }
        
        /*
        if( isset($stockGoodsArr) ){
            
            $query->whereIn('id',$stockGoodsArr);

        }
        */

        if( !empty( $request->category ) ){
            
            $filterCategory = $request->category;

            $query->where(function ( $query_add  ) use ($filterCategory) {
                
                $query_add->where( 'g.cid', $filterCategory );
                $query_add->orWhere( 'gc.cid',  $filterCategory );

            });

        }        

        // 如果有排序就執行
        if( !empty( $orderBy ) ){
            
            $query->orderBy($orderBy , $orderWay );

        }

        if( !empty( $request->start ) ){

            $query->offset($request->start);
        }
        
        if( !empty($request->length) ){

            $query->limit($request->length);
        }
        //$goods = Goods::get();


        $allFilter = $query->count();
        
        //echo $query->toSql();
        $goods = $query->selectRaw("g.* , IFNULL( gs.allStock, 0) as allStock , ROUND(IFNULL( gp.selfPrice , g.w_price*".$tmpMutiple.")) as selfPrice")->get();
        
        $goods = $goods->toArray();
        

        /*$goodsPrices = Goods::leftJoin('goods_price', function($join) {
            $join->on('goods.id', '=', 'goods_price.goods_id');
        })
        ->where('goods_price.dealer_id',Auth::id())
        ->get();*/
        $goodsPrices = GoodsPrice::where('dealer_id',Auth::id())->get();
        
        $goodsPrices = $goodsPrices->toArray();
         
        $customPrice = []; 
        // 重新整理價格
        foreach ($goodsPrices as $goodsPrice ) {

            $customPrice[ $goodsPrice['goods_id'] ] = $goodsPrice['price'];
        }
        
        // 取出經銷商資料
        /*$dealer = Dealer::where('dealer_id',Auth::id())->first();
        $dealer = $dealer->toArray();

        $defaultMultiple = $dealer['multiple'];

        foreach ($goods as $key => $good) {
          

        	if( array_key_exists($good->id, $customPrice) ){

                $goods[$key]->price = $customPrice[ $good->id ];
        	
        	}else{
                
                $goods[$key]->price =  round( $good->w_price * $defaultMultiple );
        	}
        }*/
        

        $returnData = [];

        foreach ($goods as $key => $value) {
            
            // 取出庫存
            /*$tmpRes = GoodsStock::where('dealer_id',Auth::id())->where('goods_id',$value->id)->first();
            
            if( $tmpRes != NULL){

                $tmpStock = $tmpRes->goods_num;

            }else{
                $tmpStock = 0;
            }
            */
            array_push($returnData, [
            
                $value->goods_sn,
                $value->name,
                $value->w_price,
                $value->selfPrice,
                $value->allStock,
                $value->updated_at,
                $value->id,
                $value->thumbnail,


            ]);


                
        }

        echo json_encode( ['data'=>$returnData , 'recordsTotal'=>$recordsTotal, 'recordsFiltered'=>$allFilter] );

        /*var_dump($goods);*/
    }




    /*----------------------------------------------------------------
     | 商品價格編輯
     |----------------------------------------------------------------
     |
     */
    public function priceEdit( Request $request ){
        
        if( Auth::user()->hasRole('Admin') ){
            
            exit;

        }elseif( Auth::user()->hasRole('Dealer') ){

        }else{

            return back()->with('errorMsg', ' 無此權限 , 請勿嘗試非法操作' ); 
        
        }        

        // title 名稱
        $pageTitle = "編輯商品價格";

        // 確認要編輯的商品為存在的商品
        if( empty( $request->id ) || !$this->chkGoodsExist($request->id) ){

            return redirect()->back();
        }
        
        // 取出所有分類( 分類使用 )
        $category  = categoryTool::getAllCategoryForSelect();
        
        // 取出要編輯的商品基本資料 , 並且轉換為array
        $goodsData = goodsTool::getGoods( $request->id );
        $goodsData = $goodsData->toArray();

        // 取出相關圖片 , 並轉換為array
        $goodsPics  = goodsTool::getGoodsPic( $request->id );
        $goodsPics  = $goodsPics->toArray();

        // 取出編輯商品的擴展類別
        $goodsCats = [];
        $tmpGoodsCats = GoodsCat::where( 'gid' , $request->id )->get();
        $tmpGoodsCats = $tmpGoodsCats->toArray();

        foreach ($tmpGoodsCats as $tmpGoodsCat) {

            array_push($goodsCats, $tmpGoodsCat['cid'] );
        }

        $multiple = Multiple::orderBy('multiple', 'asc')->get();
        $multiples = $multiple->toArray();

        
        $chkgoodsPrice = GoodsPrice::where('goods_id' , $request->id)->where('dealer_id',Auth::id())->exists();

        if( $chkgoodsPrice ){
            $goodsPrice = GoodsPrice::where('goods_id' , $request->id)->where('dealer_id',Auth::id())->first();
            $goodsPrice = $goodsPrice->toArray();

        }else{

            $goodsPrice = [];
        }


       

        
        $chkDealer = Dealer::where('dealer_id',Auth::id())->exists();

        if( $chkDealer ){

            $dealer = Dealer::where('dealer_id',Auth::id())->first();
            $dealer = $dealer->toArray();

        }else{

        	$dealer = [];
        }
        
        // 取出商品庫存
        $stock = GoodsStock::where('dealer_id',Auth::id())->where('goods_id',$request->id)->first();

        if( $stock == NULL){

            $stock = 0;

        }else{

            $stock = $stock->toArray();
            $stock = $stock['goods_num'];

        }
        // var_dump($stock);
        return view('priceEdit')->with([
                                        'title'     => $pageTitle,
                                        'categorys' => $category,
                                        'goodsData' => $goodsData,
                                        'goodsPics' => $goodsPics,
                                        'goodsCats' => $goodsCats,
                                        'multiples' => $multiples,
                                        'goodsPrice' => $goodsPrice,
                                        'dealer'=>$dealer,
                                        'stock'=>$stock
                                        ]);   
    }



    
    /*----------------------------------------------------------------
     | 針對售價進行編輯
     |----------------------------------------------------------------
     |
     */
     public function priceEditDo( Request $request ){

        if( Auth::user()->hasRole('Admin') ){
            
            exit;

        }elseif( Auth::user()->hasRole('Dealer') ){

        }else{

            return back()->with('errorMsg', ' 無此權限 , 請勿嘗試非法操作' ); 
        
        }

        // 檢驗資料
        $validator = Validator::make($request->all(), [
            'id'        => 'required|exists:goods,id',
            'multiple'  => 'required',
            'custome'   => 'nullable|integer|min:0',
            'stock'     => 'required|integer|min:0',


  
        ],[
            'id.required'    => '缺少商品id',
            'id.exists'      => '商品不存在',
            'multiple.required' => '倍數為必選',
            'custome.integer' => '自訂售價只接受正整數',
            'custome.min'     => '自訂售價只接受正整數',
            'stock.required'=> '庫存為必填',
            'stock.integer'=> '庫存只接受正整數',
            'stock.min'=> '庫存只接受正整數'

        ]  );
        
        $errText = '';
        
        if ($validator->fails()) {
                
            $errors = $validator->errors();
                
            foreach( $errors->all() as $message ){
                    
                $errText .= "$message<br>";
            }

        }

        $chkMultiple = Multiple::where( 'id' ,$request->multiple )->exists();

        if( !$chkMultiple && $request->multiple != 999){

            $errText .= '售價倍數不存在<br>';
        }

        if( !empty( $errText ) ){

            return back()->with('errorMsg', $errText );

        }

        if( $chkMultiple ){
            $multiple = Multiple::where( 'id' ,$request->multiple )->first();
            $multiple = $multiple->toArray();
        }
        
        $tmpPrice = 0;

        if( $request->multiple == 999 ){

        	$tmpPrice = intval($request->custome);

        }else{

        	$goods = Goods::find($request->id);
        	$tmpPrice = round( $multiple['multiple'] * $goods->w_price );
        }

        /*$goodsPrice = GoodsPrice::updateOrCreate(

            ['dealer_id' => Auth::id() , 'goods_id' => $request->id ],
            [
             'multiple_id' => $multiple['id'],
             'multiple'    => $multiple['multiple'],
             'price'       => $tmpPrice
            ]
        );*/
        DB::beginTransaction();   
        
        try{
            $chkStock = GoodsStock::where('dealer_id',Auth::id())->where('goods_id',$request->id)->exists();
        
            $nowDate = date("Y-m-d H:i:s");

            if( $chkStock ){
            
                DB::table('goods_stock')
                ->where('dealer_id', Auth::id())
                ->where('goods_id', $request->id)
                ->update(['goods_num' => $request->stock,
                          'updated_at'=> $nowDate
                         ]);

            }else{
            

                DB::table('goods_stock')->insert(
                    ['dealer_id' => Auth::id(), 
                     'goods_id'  => $request->id,
                     'goods_num' => $request->stock,
                     'created_at' => $nowDate,
                     'updated_at'=> $nowDate,
                    ]
                );
            }

        if( GoodsPrice::where('dealer_id',Auth::id())->where('goods_id',$request->id)->exists() ){
            
            $goodsPrice = GoodsPrice::where('dealer_id',Auth::id())->where('goods_id',$request->id);
           
            if( $chkMultiple ){ 
            	$tmpMultiple_id = $multiple['id'] ;
            }else{
                if( $request->multiple==999 ){

                	$tmpMultiple_id = '999';

                }else{

                    $tmpMultiple_id = '';
                }
            };

            
            if( $chkMultiple ){

            	$tmpMultiple = $multiple['multiple'];

            }else{

                $tmpMultiple = '';
            };
                            
            $goodsPrice->price = $tmpPrice;

            $goodsPrice->update(['multiple_id' => $tmpMultiple_id , 'multiple'=>$tmpMultiple ,'price'=>$tmpPrice ]);
                  

        }else{
            
            if( $chkMultiple ){ 
            	
            	$tmpMultiple_id = $multiple['id'];

            }else{

                if( $request->multiple==999 ){

                	$tmpMultiple_id = '999';

                }else{

                    $tmpMultiple_id = '';
                }
            };

            
            if( $chkMultiple ){

            	$tmpMultiple = $multiple['multiple'];

            }else{

                $tmpMultiple = '';
            };
            $goodsPrice = new GoodsPrice;
            $goodsPrice->dealer_id = Auth::id();
            $goodsPrice->goods_id  = $request->id;
            $goodsPrice->multiple_id = $tmpMultiple_id;
            $goodsPrice->multiple = $tmpMultiple;
            $goodsPrice->price = $tmpPrice;
            $goodsPrice->save();
           
        }

            DB::commit();

            return redirect('/price')->with('successMsg', '商品編輯成功');

        } catch (Exception $e) {

            DB::rollback();
            //$e->getMessage();

            // 寫入錯誤代碼後轉跳
            
            logger("{$e->getMessage()} \n\n-----------------------------------------------\n\n"); 

            return redirect('/price')->with('errorMsg', '商品編輯失敗');       
        }        





     }




    /*----------------------------------------------------------------
     | 確認商品存在
     |----------------------------------------------------------------
     |
     */
     public function chkGoodsExist( $_goodsId ){

     	return (Goods::where('id',$_goodsId)->exists());
     }
}
