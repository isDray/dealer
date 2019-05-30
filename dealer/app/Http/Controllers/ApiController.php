<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Purchase;
use App\PurchaseGoods;
use DB;
use \Exception;
use App\PurchaseLog;

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
                    
                    echo json_encode([Fasle]);         
                }    
               
            }

        }        
    }



    /*----------------------------------------------------------------
     | 
     |----------------------------------------------------------------
     |
     */
    public function chkPurchaseExist( $_sn ){
          
        return Purchase::where('purchase_sn',$_sn)->exists();
    }
}
