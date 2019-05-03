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
/*
use App\Purchase;
use App\PurchaseGoods;
use App\GoodsStock;
use App\PurchaseLog;
*/
use \Exception;
class DealerController extends Controller
{
    
    /*----------------------------------------------------------------
     | 經銷會員列表
     |----------------------------------------------------------------
     | 
    */
    public function index( Request $request ){
        
        $pageTitle = '經銷會員列表';

        // 列表功能一定要系統方才能查看
        if( !Auth::user()->hasRole('Admin') ){

            return back()->with('errorMsg', '帳號無此操作權限 , 請勿嘗試非法操作' );
        }

        // 確認權限
        if( !Auth::user()->can('dealerList') ){

        	return back()->with('errorMsg', '帳號無此操作權限 , 如有需要請切換帳號或聯絡管理員增加權限' );
        }
        
        // 取出所有經銷商
        $dealers = Role::where('name','Dealer')->first()->users()->get();
        $dealers = $dealers->toArray();

        return view('dealerList')->with([ 'title'   => $pageTitle,
                                            'dealers' => $dealers
                                         ]); 
    }




    /*----------------------------------------------------------------
     | 經銷商ajax 查詢
     |----------------------------------------------------------------
     | 根據接收到的資料查詢符合條件的經銷商再以json格式回傳 
     |
     */
    public function query( Request $request ){

        // 列表功能一定要系統方才能查看
        if( !Auth::user()->hasRole('Admin') ){
            exit;
        }

        // 確認權限
        if( !Auth::user()->can('dealerList') ){
            exit;
        }

        $query = DB::table('users')
        ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
        ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
        ->leftJoin('dealer', 'users.id', '=', 'dealer.dealer_id');

        $query->where( 'roles.name','Dealer' );

        $recordsTotal = $query->count();
        $allFilter    = $query->count();
        $datas = $query->select('users.id as uid','users.*','dealer.*')->get();

        $datas = $datas->toArray();

        $returnData = [];

        foreach ($datas as $key => $value) {
        
            array_push($returnData, [
            
                $value->hotel_name,
                $value->user_name,
                $value->email,
                $value->user_phone,
                $value->created_at,
                $value->uid,

            ]);
                
        }
        
        
        
        echo json_encode( ['data'=>$returnData , 'recordsTotal'=>$recordsTotal, 'recordsFiltered'=>$allFilter] );        
    }




    /*----------------------------------------------------------------
     | 經銷商新增頁面
     |----------------------------------------------------------------
     |
     */
    public function dealerNew( Request $request ){
        
        $pageTitle = '經銷商新增';

        // 列表功能一定要系統方才能查看
        if( !Auth::user()->hasRole('Admin') ){

            return back()->with('errorMsg', '帳號無此操作權限 , 請勿嘗試非法操作' );
        }

        // 確認權限
        if( !Auth::user()->can('dealerNew') ){

        	return back()->with('errorMsg', '帳號無此操作權限 , 如有需要請切換帳號或聯絡管理員增加權限' );
        }

        $multiple = Multiple::orderBy('multiple', 'asc')->get();
        $multiple = $multiple->toArray();

    	return view('dealerNew')->with(['title'   => $pageTitle,
    		                            'multiples'=> $multiple,
    		                           ]);                
    }




    /*----------------------------------------------------------------
     | 經銷商新增實作
     |----------------------------------------------------------------
     |
     */
    public function dealerNewDo( Request $request ){

        
        // 列表功能一定要系統方才能查看
        if( !Auth::user()->hasRole('Admin') ){

            return back()->with('errorMsg', '帳號無此操作權限 , 請勿嘗試非法操作' );
        }

        // 確認權限
        if( !Auth::user()->can('dealerNew') ){

        	return back()->with('errorMsg', '帳號無此操作權限 , 如有需要請切換帳號或聯絡管理員增加權限' );
        }
        

        // 檢驗資料
        $validator = Validator::make($request->all(), [
            'account'    => 'required|max:64',
            'password1'  => 'required|same:password2',
            'multiple'   => 'required|exists:multiple,multiple',
            'user_name'  => 'required|max:64',
            'user_email' => 'required|email',
            'user_phone' => 'required|regex:/^09[0-9]{8}$/',
            'user_tel'   => 'nullable|regex:/^[0-9]{9,12}$/',
            'hotel_name' => 'required|max:64',
            'hotel_tel'  => 'required|regex:/^[0-9]{9,12}$/',
            'hotel_phone' => 'nullable|regex:/^09[0-9]{8}$/',
            'hotel_address' => 'required',
            'ship_name' => 'nullable|max:64',
            'ship_phone'=> 'nullable|regex:/^09[0-9]{8}$/',
            'ship_tel' =>'nullable|regex:/^[0-9]{9,12}$/',
            'mainpic'=>'mimes:jpeg,png',
            'thumbnail'=>'mimes:jpeg,png',

  
        ],[
            'account.required' => '帳號為必填',
            'account.max'      => '帳號最多為64個字元',
            'password1.required' => '密碼為必填',
            'password1.same'     => '密碼驗證不一致',
            'multiple.required' => '價格預設倍數為必填',
            'multiple.exists' => '價格預設倍數不存在',
            'user_name.required' => '聯絡人為必填',
            'user_name.max' => '聯絡人姓名最多為64個字元',
            'user_email.required'=> '聯絡人信箱為必填',
            'user_email.email'=> '聯絡人信箱格式錯誤',
            'user_phone.required' => '聯絡人手機為必填',
            'user_phone.regex'=> '聯絡人手機格式錯誤',
            'user_tel.regex'=>'聯絡人電話格式錯誤',
            'hotel_name.required'=>'旅館名稱為必填',
            'hotel_name.max'=> '旅館名稱最多為64字元',
            'hotel_tel.required' => '旅館電話為必填',
            'hotel_tel.regex' => '旅館電話格式錯誤',
            'hotel_phone.regex' => '旅館手機格式錯誤',
            'hotel_address.required' => '旅館地址為必填',
            'ship_name.max'=>'預設收貨人姓名最多為64字元',
            'ship_phone.regex'=>'預設收貨手機格式錯誤',
            'ship_tel.regex' => '預設收貨電話格式錯誤',
            'mainpic.mimes'=>'網頁版logo只接受 jpeg 、png 格式',
            'thumbnail.mimes'=>'手機版logo只接受 jpeg 、png 格式',
        ]  );
        
        $errText = '';
        
        if ($validator->fails()) {
                
            $errors = $validator->errors();
                
            foreach( $errors->all() as $message ){
                    
                $errText .= "$message<br>";
            }

        }
        if( !empty( $errText ) ){

            return redirect('/dealerNew')->withInput()->with('errorMsg', $errText );

        }

        // 開始新增經銷商資料
        DB::beginTransaction();   
        
        try{
    		
    		$user = new User();
    		$user->name = $request->account;
            $user->password = Hash::make( $request->password1 );
            $user->email =  $request->user_email;
            $user->save();
            
            // 給身分
            $role = Role::where('name','Dealer')->first();
            $user->attachRole($role);
            
            // 計算檔名
            if( $request->file('mainpic') == null){
                
                $mainpicExtension = '';

            }else{

                $mainpicExtension = $request->file('mainpic')->extension();
                $request->file('mainpic')->storeAs("logo/{$user->id}/","wlogo.$mainpicExtension",'goodsImage');

            }
            
            if( $request->file('thumbnail') == null){
                
                $thumbnailExtension = '';

            }else{
            	
                $thumbnailExtension = $request->file('thumbnail')->extension();
                $request->file('thumbnail')->storeAs("logo/{$user->id}/","mlogo.$thumbnailExtension",'goodsImage');
            }            
            

            $dealer = new Dealer();
            $dealer->dealer_id     = $user->id;
            $dealer->hotel_name    = isset( $request->hotel_name )? trim( $request->hotel_name ):'';
            $dealer->web_url       = isset( $request->hotel_url)? trim( $request->hotel_url):''; 
            $dealer->hotel_phone   = isset( $request->hotel_phone )? trim( $request->hotel_phone ):'';
            $dealer->hotel_tel     = isset( $request->hotel_tel )? trim( $request->hotel_tel):'';
            $dealer->hotel_address = isset( $request->hotel_address )? trim( $request->hotel_address ):'';
            $dealer->user_name     = isset( $request->user_name )? trim( $request->user_name ):'';
            $dealer->user_phone    = isset( $request->user_phone)? trim( $request->user_phone ):'';
            $dealer->user_tel      = isset( $request->user_tel)?trim($request->user_tel):'';
            $dealer->ship_name     = isset( $request->ship_name)?trim($request->ship_name):'';
            $dealer->ship_phone    = isset( $request->ship_phone)?trim($request->ship_phone):'';
            $dealer->ship_tel      = isset( $request->ship_tel)?trim($request->ship_tel):'';
            $dealer->ship_address  = isset( $request->ship_address)?trim($request->ship_address):'';
            $dealer->logo1         = 'wlogo'.$mainpicExtension;
            $dealer->logo2         = 'mlogo'.$thumbnailExtension;
            $dealer->multiple      = isset( $request->multiple)?trim($request->multiple):'';
            
            $dealer->save();

            DB::commit();

            return redirect('/dealer')->with('successMsg', '經銷商新增成功');

        } catch (Exception $e) {

            DB::rollback();
            //$e->getMessage();

            // 寫入錯誤代碼後轉跳
            
            logger("{$e->getMessage()} \n\n-----------------------------------------------\n\n"); 

            return back()->withInput()->with('errorMsg', '經銷商新增失敗 , 請稍後再試' );         
        }          
    }




    /*----------------------------------------------------------------
     | 編輯經銷商頁面
     |----------------------------------------------------------------
     |
     */
    public function dealerEdit( Request $request ){
        
        $pageTitle = '經銷商編輯';

        // 如果不是系統方也不是經銷方則直接終止
        if( !Auth::user()->hasRole('Admin') && !Auth::user()->hasRole('Dealer') ){ exit; }
        
        if( empty( $request->id ) ){

            return back()->with('errorMsg', '缺少必要參數 , 請重新整理頁面後再試' );
        }

        // 列表功能一定要系統方才能查看
        if( Auth::user()->hasRole('Admin') ){

            // 確認權限
            if( !Auth::user()->can('dealerNew') ){

        	    return back()->with('errorMsg', '帳號無此操作權限 , 如有需要請切換帳號或聯絡管理員增加權限' );
            }

        }elseif( Auth::user()->hasRole('Dealer') ){
            
            // 如果是經銷商則需要確認是不是為自己的資料
            if( !$this->chkDealer( $request->id ) ){

                return back()->with('errorMsg', '要編輯的經銷商非您所擁有 , 請勿嘗試非法操作' );
            }
        }
        
        $multiple = Multiple::orderBy('multiple', 'asc')->get();
        $multiple = $multiple->toArray();

        $dealer = User::leftJoin('dealer', function($join) {
            $join->on('users.id', '=', 'dealer.dealer_id');
        })
        ->where('users.id',$request->id )
        ->first([
            'users.id as uid',
            'users.name',
            'users.email',
            'dealer.*'
        ]);
        
        $dealer = $dealer->toArray();

    	return view('dealerEdit')->with(['title'     => $pageTitle,
    		                             'multiples' => $multiple,
    		                             'dealer'    => $dealer
    		                            ]);  
    }




    /*----------------------------------------------------------------
     | 確認經銷商資料為當下操作者
     |----------------------------------------------------------------
     |
     */
    public function chkDealer( $_dealerId ){
        
        if( $_dealerId == Auth::id() ){
            
            return true;

        }else{
            
            return false;
        }
    }
}
