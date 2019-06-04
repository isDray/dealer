<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Purchase;
use App\PurchaseGoods;
use DB;
use \Exception;
use App\PurchaseLog;
use App\Goods;

class ApiController extends Controller
{
    //

    public function index( Request $request){

        /*echo json_encode('ENTER');*/
        $key = 'a459ec4a-be91-461c-8b98-896d8283da64';
        
        if( $request->key == $key ){
            
            $now = date("Y-m-d H:i:s");

        	echo json_encode("ENTER");

        	logger("$now CALL SUCCESS \n\n-----------------------------------------------\n\n"); 

        }

    }



    /*----------------------------------------------------------------
     | 進貨單修改狀態
     |----------------------------------------------------------------
     |
     */

    public function toShipped( Request $request ){
        
        $key = 'a459ec4a-be91-461c-8b98-896d8283da64';
        
        if( $request->key == $key && $this->chkPurchaseExist( $request->purchaseSn ) ){      
            


            $Purchase = Purchase::where( 'purchase_sn' , $request->purchaseSn )->first();

            if( $Purchase != NULL && $Purchase->status == 2){
                
                DB::beginTransaction();
                
                try {

            	    $Purchase->status = 3;
            	    $Purchase->shipdate = date('Y-m-d H:i:s');
    
            	    $Purchase->save();
    
                    $PurchaseLog =  new PurchaseLog;
                    $PurchaseLog->user_id   = 16;
                    $PurchaseLog->user_name = 'Admin(同步)';
                    $PurchaseLog->user_role = 'Admin';
                    $PurchaseLog->purchase_id = $Purchase->id;
                    $PurchaseLog->purchase_status = $Purchase->status;
                    $PurchaseLog->purchase_status_text = '已出貨';        
                    $PurchaseLog->desc = '修改進貨單狀態';            
                    $PurchaseLog->save();            	
                    
                    DB::commit();
                    
                    echo json_encode([True]);

                }catch (Exception $e) {

                    DB::rollback();
                    //$e->getMessage();
        
                    // 寫入錯誤代碼後轉跳
                    
                    logger("{$e->getMessage()} \n\n-----------------------------------------------\n\n"); 
                    
                    echo json_encode([False]);         
                }    
               
            }

        }        
    }




    /*----------------------------------------------------------------
     | 更新進貨單資訊 - 以享愛官網為主
     |----------------------------------------------------------------
     |
     */
    public function toUpdate( Request $request ){

        $key = 'a459ec4a-be91-461c-8b98-896d8283da64';

        // 比對 api key 並且確認訂單存在
        if( $request->key == $key && $this->chkPurchaseExist( $request->purchaseSn ) ){
            
            // 將更新條件寫入資料庫
            DB::beginTransaction();
                
            try {
                
                // 更新同步衝突紀錄
                $conflictMsg = "";

                // 取出訂單id
                $purchaseId = Purchase::where("purchase_sn",$request->purchaseSn)->first();
                
                if( $purchaseId != NULL ){

                    $purchaseId = $purchaseId->id;

                }else{
                    
                    exit;
                }

                // 刪除進貨單的全部商品資料
                DB::table('purchase_goods')->where('purchase_id', $purchaseId )->delete();
                
                // 迴圈商品確認
                foreach ( $request->goodsItems as $goodsItem ) {
                    
                    // 如果商品在旅館系統不存在 , 則會無法新增 , 如果出現這種情形將貨號記錄起來
                    if( !Goods::where('goods_sn',$goodsItem['goods_sn'])->exists() ){
                        
                        $conflictMsg .= "貨號:{$goodsItem['goods_sn']}不存在,無法同步更新<br/>";

                        continue;
                    }

                    // 如果是經銷平台有的商品則需要全部加回去
                    $tmpGoods = Goods::where('goods_sn',$goodsItem['goods_sn'])->first();
                    
                    if( $tmpGoods != NULL){

                        $tmpGoodsName = $tmpGoods->name;

                        $tmpGoodsId   = $tmpGoods->id;
                    }
                    
                    // 重新寫入進貨單商品
                    $purchaseGoods = new PurchaseGoods;

                    $purchaseGoods->goods_id    = $tmpGoodsId;
                    $purchaseGoods->goods_sn    = $goodsItem['goods_sn'];
                    $purchaseGoods->goods_name  = $tmpGoodsName;
                    $purchaseGoods->w_price     = intval($goodsItem['goods_price']);
                    $purchaseGoods->num         = $goodsItem['goods_number'];
                    $purchaseGoods->subtotal    = ( intval($goodsItem['goods_price']) * $goodsItem['goods_number'] );
                    $purchaseGoods->purchase_id = $purchaseId;
                    $purchaseGoods->save();                    

                }

                // 進貨單主要資訊修改
                $purchase = Purchase::find($purchaseId);

                $purchase->amount       = intval( $request->purchaseAmount );
                $purchase->final_amount = intval( $request->purchaseTotalAmount );
                $purchase->ship_fee     = intval( $request->purchaseShipFee );
                $purchase->tax          = intval( $request->purchaseTax );
                $purchase->discount     = intval( $request->purchaseDiscount );

                if( !empty($conflictMsg) ){

                    $purchase->admin_note   = $purchase->admin_note ."<br>".date("Y-m-d H:i:s")."<br>".$conflictMsg;
                }
                
                $purchase->save();                

                DB::commit();

                    
                echo json_encode(['OK']);
            
            }catch (Exception $e) {

                DB::rollback();
                //$e->getMessage();
        
                // 寫入錯誤代碼後轉跳
                    
                logger("{$e} \n\n-----------------------------------------------\n\n"); 
                    
                echo json_encode(['NO']);         
            }                                    

        }else{
            echo json_encode(['NO']);
        }

    }
    /*----------------------------------------------------------------
     | 檢測訂單是否存在
     |----------------------------------------------------------------
     |
     */
    public function chkPurchaseExist( $_sn ){
          
        return Purchase::where('purchase_sn',$_sn)->exists();
    }

}
