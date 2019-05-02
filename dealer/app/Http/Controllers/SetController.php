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
/*
use App\Purchase;
use App\PurchaseGoods;
use App\GoodsStock;
use App\PurchaseLog;
*/
use \Exception;
class SetController extends Controller
{




    /*----------------------------------------------------------------
     | 網站設置主頁
     |----------------------------------------------------------------
     |
     */
    public function set( Request $request ){
        
        // 如果沒有權限直接跳回
        if( !Auth::user()->can('setEdit') ){

            return back()->with('errorMsg', '帳號無此操作權限 , 如有需要請切換帳號或聯絡管理員增加權限' );

        }

        $pageTitle = '網站設置';

        $set = DB::table('set')->find(1);

        return view('set')->with([ 'title' => $pageTitle,
        	                       'webSet'   => $set
                                         ]); 
    }
    



    /*----------------------------------------------------------------
     | 網站主頁設置實作
     |----------------------------------------------------------------
     |
     */
    public function setDo( Request $request ){
        
        // 如果沒有權限直接跳回
        if( !Auth::user()->can('setEdit') ){

            return back()->with('errorMsg', '帳號無此操作權限 , 如有需要請切換帳號或聯絡管理員增加權限' );
            
        }
        // 表單驗證
        $errText = '';

        $validator = Validator::make($request->all(), [

            'name'     => 'required',
            'showType' => 'required',
            'sortType' => 'required',
            'way'      => 'required',

        ],[
            'dealerId.required'=> '網站名稱為必填',
            'showType.required'=> '呈現方式為必填',
            'sortType.required'=> '排序方式為必填',
            'way.required'     => '排序規則為必填',
            
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors();
                
            foreach( $errors->all() as $message ){
                    
                $errText .= "$message<br>";
            
            }
            
        }        

        if( !empty( $errText ) ){

        	return back()->with('errorMsg', $errText );
        }
        
        // 判斷資料庫裏面是否已經有一筆資料了
        $web = DB::table('set')->find(1);
        
        // 如果有資料就用更新的 , 沒有就新增一筆
        if( empty($web) ){
            
            $insertId = DB::table('set')->insertGetId(
                ['name'      => $request->name,
                 'show_type' => $request->showType,
                 'sort_type' => $request->sortType,
                 'sort_way'  => $request->way,
                 'created_at' => date('Y-m-d H:i:s'),
                 'updated_at' => date('Y-m-d H:i:s'),
                ]
            );

            if( !empty($insertId) ){

                return redirect('/set')->with('successMsg', '網站設置成功');

            }else{

                return back()->with('errorMsg', '網站設置失敗 , 請稍後再試' );
            }

        }else{
            
            $res = DB::table('set')
            ->where('id', 1)
            ->update(['name'      => $request->name,
                       'show_type' => $request->showType,
                       'sort_type' => $request->sortType,
                       'sort_way'  => $request->way,
                       'created_at' => date('Y-m-d H:i:s'),
                       'updated_at' => date('Y-m-d H:i:s'),                       
                    ]);
            if( $res ){

                return redirect('/set')->with('successMsg', '網站設置成功');

            }else{

                return back()->with('errorMsg', '網站設置失敗 , 請稍後再試' );
            }
        }
    }


}
