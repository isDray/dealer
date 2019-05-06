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
        
        $pageTitle = '商品售價列表';

        if( Auth::user()->hasRole('Admin') ){
            
            exit;

        }elseif( Auth::user()->hasRole('Dealer') ){

        }else{

            return back()->with('errorMsg', ' 無此權限 , 請勿嘗試非法操作' ); 
        
        }
        return view('priceList')->with([ 'title'   => $pageTitle,
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

        $query = DB::table('goods');

        $recordsTotal = $query->count();

        
        
        if( isset($request->myKeyword) && !empty($request->myKeyword) ){
               
            $filterSearch = $request->myKeyword;

            $query->where(function ($cond) use ($filterSearch) {
            	
                $cond->where('goods_sn', 'like', "%$filterSearch%")
                     ->orWhere('name', 'like' ,"%$filterSearch%");
            });
        }
        
        if( !empty( $request->start ) ){

            $query->offset($request->start);
        }
        
        if( !empty($request->length) ){

            $query->limit($request->length);
        }
        //$goods = Goods::get();

        $allFilter = $query->count();

        $goods = $query->select('*')->get();
        

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
        $dealer = Dealer::where('dealer_id',Auth::id())->first();
        $dealer = $dealer->toArray();

        $defaultMultiple = $dealer['multiple'];

        foreach ($goods as $key => $good) {
          

        	if( array_key_exists($good->id, $customPrice) ){

                $goods[$key]->price = $customPrice[ $good->id ];
        	
        	}else{
                
                $goods[$key]->price =  round( $good->w_price * $defaultMultiple );
        	}
        }
        

        $returnData = [];

        foreach ($goods as $key => $value) {
        
            array_push($returnData, [
            
                $value->goods_sn,
                $value->name,
                $value->w_price,
                $value->price,
                $value->updated_at,
                $value->id,

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

        return view('priceEdit')->with([
                                        'title'     => $pageTitle,
                                        'categorys' => $category,
                                        'goodsData' => $goodsData,
                                        'goodsPics' => $goodsPics,
                                        'goodsCats' => $goodsCats,
                                        'multiples' => $multiples,
                                        'goodsPrice' => $goodsPrice,
                                        'dealer'=>$dealer
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


  
        ],[
            'id.required'    => '缺少商品id',
            'id.exists'      => '商品不存在',
            'multiple.required' => '倍數為必選',
            'custome.integer' => '自訂售價只接受正整數',
            'custome.min'     => '自訂售價只接受正整數',

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

            if( $goodsPrice->update(['multiple_id' => $tmpMultiple_id , 'multiple'=>$tmpMultiple ,'price'=>$tmpPrice ]) ){
    
                return redirect('/price')->with('successMsg', '售價編輯成功');
    
            }else{
    
                return redirect('/price')->with('errorMsg', '售價編輯失敗');
            }              

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
            if( $goodsPrice->save() ){
    
                return redirect('/price')->with('successMsg', '售價編輯成功');
    
            }else{
    
                return redirect('/price')->with('errorMsg', '售價編輯失敗');
            }            
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
