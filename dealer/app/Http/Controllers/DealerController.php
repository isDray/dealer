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
use App\Category;
use App\DealerCategory;
use App\DealerGoods;
/*
use App\Purchase;
use App\PurchaseGoods;
use App\GoodsStock;
use App\PurchaseLog;
*/
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use \Exception;
use Storage;
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

        $orderItems  = [
                        '0'=>'users.id',
                      ];
        
        // 整理排序關鍵字
        if( array_key_exists($request->order['0']['column'], $orderItems )){

            $orderBy = $orderItems[ $request->order['0']['column'] ];
        
        }else{

            $orderBy = '';
        }
        
        
        $orderWay = $request->order['0']['dir'];

        $query = DB::table('users')
        ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
        ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
        ->leftJoin('dealer', 'users.id', '=', 'dealer.dealer_id');

        $query->where( 'roles.name','Dealer' );
        
        if( !empty( $request->dealer ) ){

            $query->where( 'users.id',$request->dealer );
        }

        if( !empty( $request->status ) ){
            if( $request->status == 1){
                $query->where( 'dealer.status',1 );
            }else{
                $query->where( 'dealer.status',0 );
            }
        }

        // 如果有排序就執行
        if( !empty( $orderBy ) ){
            
            $query->orderBy($orderBy , $orderWay );

        }

        $recordsTotal = $query->count();
        $allFilter    = $query->count();
        $datas = $query->select('users.id as uid','users.*','dealer.*')->get();

        $datas = $datas->toArray();

        $returnData = [];

        foreach ($datas as $key => $value) {
        
            array_push($returnData, [
                
                $value->uid,
                $value->hotel_name,
                $value->user_name,
                $value->email,
                $value->user_phone,
                explode(' ',$value->enable_date)[0],
                $value->detect,
                $value->status,

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

        // 取出所有分類
        $categorys = Category::where("status",1)->orderBy('sort','ASC')->get();
        $categorys = json_decode($categorys,true);

        /*****************************************************************
         * 取出所有商品 , 以及當前經銷商可用商品 , 整理完格式後傳給前台
         * 作呈現
         *
         */

        // 取出所有商品跟類別資料
        $goods = Goods::leftJoin('category', function($join) {

            $join->on('goods.cid', '=', 'category.id');
        })->select('goods.*', 'category.id as categoryId' , 'category.name as categoryName')->get();

        $goods = json_decode($goods,true);
        
        // 將商品資料整理 , 方便呈現
        $selectGoods = [];

        foreach ( $goods as $good ) {
            
            // 如果 selectGoods 不包含商品類別名稱的key值 , 則需要新增一個
            if( !array_key_exists($good['categoryName'], $selectGoods) ){
                
                $selectGoods[ $good['categoryName'] ] = [];
            }

            array_push( $selectGoods[ $good['categoryName'] ] , [ 'name' => $good['name'],
                                                                  'id'   => $good['id'],
                                                                ]);
        }        

    	return view('dealerNew')->with(['title'     => $pageTitle,
    		                            'multiples' => $multiple,
                                        'categorys' => $categorys,
                                        'selectGoods' => $selectGoods,
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
            'accessWay'  => 'required',
            'password1'  => 'required|same:password2',
            'multiple'   => 'nullable|exists:multiple,multiple',
            'user_name'  => 'required|max:64',
            'user_email' => 'nullable|email',
            'user_phone' => 'nullable|regex:/^09[0-9]{8}$/',
            'user_tel'   => 'nullable|regex:/^[0-9]{9,12}$/',
            'hotel_name' => 'nullable|max:64',
            'hotel_tel'  => 'nullable|regex:/^[0-9]{9,12}$/',
            'hotel_extension' => 'nullable|max:8',
            'hotel_phone' => 'nullable|regex:/^09[0-9]{8}$/',
            // 'hotel_address' => 'required',
            'ship_name' => 'nullable|max:64',
            'ship_phone'=> 'nullable|regex:/^09[0-9]{8}$/',
            'ship_tel' =>'nullable|regex:/^[0-9]{9,12}$/',
            'mainpic'=>'mimes:jpeg,png',
            'thumbnail'=>'mimes:jpeg,png',
            'hotel_email'=> 'nullable|email',

  
        ],[
            'account.required' => '帳號為必填',
            'account.max'      => '帳號最多為64個字元',
            'accessWay.required'=> '網站代碼為必填',
            'password1.required' => '密碼為必填',
            'password1.same'     => '密碼驗證不一致',
            // 'multiple.required' => '價格預設倍數為必填',
            'multiple.exists' => '價格預設倍數不存在',
            'user_name.required' => '聯絡人為必填',
            'user_name.max' => '聯絡人姓名最多為64個字元',
            // 'user_email.required'=> '聯絡人信箱為必填',
            'user_email.email'=> '聯絡人信箱格式錯誤',
            // 'user_phone.required' => '聯絡人手機為必填',
            'user_phone.regex'=> '聯絡人手機格式錯誤',
            'user_tel.regex'=>'聯絡人電話格式錯誤',
            // 'hotel_name.required'=>'旅館名稱為必填',
            'hotel_name.max'=> '旅館名稱最多為64字元',
            // 'hotel_tel.required' => '旅館電話為必填',
            'hotel_tel.regex' => '旅館電話格式錯誤',
            'hotel_extension.max'=>'旅館分機最多為8個字元',
            'hotel_phone.regex' => '旅館手機格式錯誤',
            'hotel_email.email'=> '旅館信箱格式錯誤',
            // 'hotel_address.required' => '旅館地址為必填',
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

            return redirect('/newdealerNew')->withInput()->with('errorMsg', $errText );

        }

        // 開始新增經銷商資料
        DB::beginTransaction();   
        
        try{
    		
    		$user = new User();
    		$user->name = $request->account;
            $user->password = Hash::make( $request->password1 );


            if( empty($request->user_email) || !isset($request->user_email) ){

                $user->email = NULL;
            }else{
                
                $user->email =  $request->user_email;
            }

            $user->save();
            
            $user2 = User::find( $user->id );

            // if( $request->accessWay == 1){

            //     $user2->detect = str_pad( $user->id ,4,'0',STR_PAD_LEFT);

            // }else{

            //     $user2->detect = $request->account;
            // } 
            if( isset($request->accessWay) && !empty($request->accessWay) ){
                
                $user2->detect = trim($request->accessWay);

            }else{
                
                $user2->detect = str_pad( $user->id ,4,'0',STR_PAD_LEFT);

            }
            $user2->save();
            
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

            if( isset($request->enable_date) ){
                
                $tmpData = strtotime($request->enable_date);
                $enableDate = date("Y-m-d",$tmpData);
                
            }            

            $dealer = new Dealer();
            $dealer->dealer_id     = $user->id;

            $dealer->company       = isset( $request->company )? trim( $request->company ):'';
            $dealer->ein           = isset( $request->ein )? trim( $request->ein ):'';            

            $dealer->hotel_name    = isset( $request->hotel_name )? trim( $request->hotel_name ):'';
            $dealer->web_url       = isset( $request->hotel_url)? trim( $request->hotel_url):''; 
            $dealer->hotel_phone   = isset( $request->hotel_phone )? trim( $request->hotel_phone ):'';
            $dealer->hotel_tel     = isset( $request->hotel_tel )? trim( $request->hotel_tel):'';
            $dealer->hotel_extension = isset( $request->hotel_extension )? trim( $request->hotel_extension):'';
            $dealer->hotel_email   = isset( $request->hotel_email )? trim( $request->hotel_email):'';
            $dealer->hotel_address = isset( $request->hotel_address )? trim( $request->hotel_address ):'';
            $dealer->user_name     = isset( $request->user_name )? trim( $request->user_name ):'';
            $dealer->user_position = isset( $request->user_position )? trim( $request->user_position ):'';
            $dealer->user_phone    = isset( $request->user_phone)? trim( $request->user_phone ):'';
            $dealer->user_tel      = isset( $request->user_tel)?trim($request->user_tel):'';
            $dealer->ship_name     = isset( $request->ship_name)?trim($request->ship_name):'';
            $dealer->ship_phone    = isset( $request->ship_phone)?trim($request->ship_phone):'';
            $dealer->ship_tel      = isset( $request->ship_tel)?trim($request->ship_tel):'';
            $dealer->ship_address  = isset( $request->ship_address)?trim($request->ship_address):'';
            if( $request->file('mainpic') != null){
                $dealer->logo1         = 'wlogo.'.$mainpicExtension;
            }else{
                $dealer->logo1  = '';
            }
            if( $request->file('thumbnail') != null){
                $dealer->logo2         = 'mlogo.'.$thumbnailExtension;
            }else{
                $dealer->logo2  = '';
            }
            $dealer->multiple      = isset( $request->multiple)?trim($request->multiple):'2.0';
            $dealer->status        = 1;
            $dealer->credit_pay    = isset( $request->credit_pay)?1:0;            
            $dealer->enable_date   = isset( $request->enable_date)?trim($enableDate):'';            
            $dealer->logo_color1   = isset( $request->logocolor1)?trim($request->logocolor1):'#fff';
            $dealer->logo_color2   = isset( $request->logocolor2)?trim($request->logocolor2):'#fff';
            
            // banner儲存
            $bannerArr = ["0","1","2","3"];
                
            // 如果代碼不存在 , 則給預設1  
            if( !in_array($request->web_type, $bannerArr) || !isset($request->web_type) ){
                    
                $webBanner = 1;

            }else{
                $webBanner = $request->web_type;
            }

            // 如果代碼不存在 , 則給預設1  
            if( !in_array($request->mobile_type, $bannerArr) || !isset($request->mobile_type) ){
                    
                $mobileBanner = 1;

            }else{
                $mobileBanner = $request->mobile_type;
            }                
                
            $dealer->banner_type_w = $webBanner;

            $dealer->banner_type_m = $mobileBanner;
            
            $dealer->note          = $request->note;
            
            $dealer->save();

            // 清除會員所有可用分類
            DealerCategory::where('dealer_id', $user->id )->delete();

            if( isset( $request->allCategory ) ){

                foreach ($request->allCategory as $allCategory) {
                    
                    $dealerCategory = new DealerCategory;

                    $dealerCategory->dealer_id = $user->id;

                    $dealerCategory->category_id = $allCategory;

                    $dealerCategory->save();
                }
            }            

            /*****************************************************************
             * 編輯經銷商可用商品
             *
             */

            // 清除會員所有可用商品
            DealerGoods::where('dealer_id', $user->id )->delete();
                
            // 如果有接收到可用商品陣列 , 則將其存入資料庫
            if( isset( $request->ableGoods ) ){
                    
                foreach ($request->ableGoods as $ableGood) {
                
                    $dealerGoods = new DealerGoods;

                    $dealerGoods->dealer_id = $user->id;

                    $dealerGoods->goods_id  = $ableGood;

                    $dealerGoods->save();
                }                    
            }

            DB::commit();

            return redirect('/newdealer')->with('successMsg', '經銷商新增成功');

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
        

        if( empty($dealer['logo_color1']) ){
            $dealer['logo_color1'] = '#fff';
        }

        if( empty($dealer['logo_color2']) ){
            $dealer['logo_color2'] = '#fff';
        }        

        if( !empty($dealer['enable_date']) ){
            $dealer['enable_date'] = explode(' ', $dealer['enable_date'])[0];
        }
        // 計算目前是使用哪種連結方式
        $dealerUser = User::where('id',$request->id)->first();
        if(  $dealerUser != NULL ){
            
            $dealerUser = json_decode($dealerUser,true);
    
            $accessWay = $dealerUser['detect'];
           

        }

        // 取出所有分類
        $categorys = Category::where("status",1)->orderBy('sort','ASC')->get();
        $categorys = json_decode($categorys,true);
        
        // 取出所有有選擇的分類
        $ablecategorys = DealerCategory::where('dealer_id',$request->id)->get();
        $ablecategorys = json_decode($ablecategorys,true);
        
        $tmpAbleCategory = [];
        foreach ($ablecategorys as $ablecategorys) {

            array_push($tmpAbleCategory, $ablecategorys['category_id']);
        }
        
        /*****************************************************************
         * 取出所有商品 , 以及當前經銷商可用商品 , 整理完格式後傳給前台
         * 作呈現
         *
         */

        // 取出所有商品跟類別資料
        $goods = Goods::leftJoin('category', function($join) {

            $join->on('goods.cid', '=', 'category.id');
        })->select('goods.*', 'category.id as categoryId' , 'category.name as categoryName')->get();

        $goods = json_decode($goods,true);
        
        // 將商品資料整理 , 方便呈現
        $selectGoods = [];

        foreach ( $goods as $good ) {
            
            // 如果 selectGoods 不包含商品類別名稱的key值 , 則需要新增一個
            if( !array_key_exists($good['categoryName'], $selectGoods) ){
                
                $selectGoods[ $good['categoryName'] ] = [];
            }

            array_push( $selectGoods[ $good['categoryName'] ] , [ 'name' => $good['name'],
                                                                  'id'   => $good['id'],
                                                                  'goods_sn' => $good['goods_sn'],
                                                                ]);
        }

        $getAbleGoods = DealerGoods::where('dealer_id',$request->id)->get();
        
        $ableGoods = [];

        foreach ($getAbleGoods as $getAbleGood ) {

            array_push( $ableGoods, $getAbleGood['goods_id'] );

        }

    	return view('dealerEdit')->with(['title'     => $pageTitle,
    		                             'multiples' => $multiple,
    		                             'dealer'    => $dealer,
                                         'accessWay' => $accessWay,
                                         'categorys' => $categorys,
                                         'ablecategorys' => $tmpAbleCategory,
                                         'selectGoods'   => $selectGoods,
                                         'ableGoods' => $ableGoods,
    		                            ]);  
    }




    /*----------------------------------------------------------------
     | 經銷商編輯實作
     |----------------------------------------------------------------
     |
     */
    public function dealerEditDo( Request $request ){
        
        // 列表功能一定要系統方才能查看
        if( Auth::user()->hasRole('Admin') ){

            // 確認權限
            if( !Auth::user()->can('dealerNew') ){

                return back()->with('errorMsg', '帳號無此操作權限 , 如有需要請切換帳號或聯絡管理員增加權限' );
            }

        }elseif( Auth::user()->hasRole('Dealer') ){
            
            if( empty( $request->dealerId ) || !$this->chkDealer($request->dealerId) ){

                return back()->with('errorMsg', '要編輯的經銷商非您所擁有 , 請勿嘗試非法操作' );
            }

        }else{

            return back()->with('errorMsg', '帳號無此操作權限 , 請勿嘗試非法操作' );

        }

        // 檢驗資料
        $validator = Validator::make($request->all(), [
            'account'    => 'required|max:64',
            'password1'  => 'nullable|same:password2',
            'accessWay'  => 'required',
            'multiple'   => 'nullable|exists:multiple,multiple',
            'user_name'  => 'required|max:64',
            'user_email' => 'nullable|email',
            'user_phone' => 'nullable|regex:/^09[0-9]{8}$/',
            'user_tel'   => 'nullable|regex:/^[0-9]{9,12}$/',
            'hotel_name' => 'nullable|max:64',
            'hotel_tel'  => 'nullable|regex:/^[0-9]{9,12}$/',
            'hotel_extension' => 'nullable|max:8',
            'hotel_phone' => 'nullable|regex:/^09[0-9]{8}$/',
            // 'hotel_address' => 'required',
            'ship_name' => 'nullable|max:64',
            'ship_phone'=> 'nullable|regex:/^09[0-9]{8}$/',
            'ship_tel' =>'nullable|regex:/^[0-9]{9,12}$/',
            'mainpic'=>'mimes:jpeg,png',
            'thumbnail'=>'mimes:jpeg,png',
            'hotel_email'=> 'nullable|email',

  
        ],[
            'account.required' => '帳號為必填',
            'account.max'      => '帳號最多為64個字元',
            'password1.required' => '密碼為必填',
            'password1.same'     => '密碼驗證不一致',
            'accessWay.required' => '網站代碼為必填',
            // 'multiple.required' => '價格預設倍數為必填',
            'multiple.exists' => '價格預設倍數不存在',
            'user_name.required' => '聯絡人為必填',
            'user_name.max' => '聯絡人姓名最多為64個字元',
            // 'user_email.required'=> '聯絡人信箱為必填',
            'user_email.email'=> '聯絡人信箱格式錯誤',
            // 'user_phone.required' => '聯絡人手機為必填',
            'user_phone.regex'=> '聯絡人手機格式錯誤',
            'user_tel.regex'=>'聯絡人電話格式錯誤',
            // 'hotel_name.required'=>'旅館名稱為必填',
            'hotel_name.max'=> '旅館名稱最多為64字元',
            // 'hotel_tel.required' => '旅館電話為必填',
            'hotel_tel.regex' => '旅館電話格式錯誤',
            'hotel_email.email'=> '旅館信箱格式錯誤',
            'hotel_phone.regex' => '旅館手機格式錯誤',
            'hotel_extension.max'=>'分機最多為8個字元',
            // 'hotel_address.required' => '旅館地址為必填',
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
        if( !empty( $request->oldpassword ) ){
            
            $user = User::find($request->dealerId);
            
            if( !Hash::check($request->oldpassword, $user->password) ){

                $errText .= '舊密碼輸入錯誤<br>';
            }
        }
        if( !empty( $errText ) ){

            return back()->with('errorMsg', $errText );

        }

        // 開始更新經銷商資料

        DB::beginTransaction();   
        if( Auth::user()->hasRole('Admin') ){

            try{
                
                $user = User::find($request->dealerId);
                $user->name = $request->account;
                if( !empty( $request->oldpassword ) ){
                    $user->password = Hash::make( $request->password1 );
                }
                /*if( $request->accessWay == 1){
    
                    $user->detect = str_pad( $user->id ,4,'0',STR_PAD_LEFT);
    
                }else{
    
                    $user->detect = $request->account;
                }*/    
                // $user->detect = trim($request->accessWay);      
                if( isset($request->accessWay) && !empty($request->accessWay) ){
                    
                    $user->detect = trim($request->accessWay);
    
                }else{
                    
                    $user->detect = str_pad( $user->id ,4,'0',STR_PAD_LEFT);
    
                }
                $user->email =  $request->user_email;
                $user->save();
                
                
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
                
    
                $dealer = Dealer::where('dealer_id',$request->dealerId)->first();
                if( $dealer == NULL ){
    
                    $dealer = new Dealer();
                }
                
                if( isset($request->enable_date) ){
                    
                    $tmpData = strtotime($request->enable_date);
                    $enableDate = date("Y-m-d",$tmpData);
                    
                }

                $dealer->dealer_id     = $user->id;
                $dealer->company       = isset( $request->company )? trim( $request->company ):'';
                $dealer->ein           = isset( $request->ein )? trim( $request->ein ):'';

                $dealer->hotel_name    = isset( $request->hotel_name )? trim( $request->hotel_name ):'';
                $dealer->web_url       = isset( $request->hotel_url)? trim( $request->hotel_url):''; 
                $dealer->hotel_phone   = isset( $request->hotel_phone )? trim( $request->hotel_phone ):'';
                $dealer->hotel_tel     = isset( $request->hotel_tel )? trim( $request->hotel_tel):'';
                $dealer->hotel_extension = isset( $request->hotel_extension )? trim( $request->hotel_extension):'';
                
                $dealer->hotel_email   = isset( $request->hotel_email )? trim( $request->hotel_email):'';
                $dealer->hotel_address = isset( $request->hotel_address )? trim( $request->hotel_address ):'';
                $dealer->user_name     = isset( $request->user_name )? trim( $request->user_name ):'';
                $dealer->user_position = isset( $request->user_position )? trim( $request->user_position ):'';
                $dealer->user_phone    = isset( $request->user_phone)? trim( $request->user_phone ):'';
                $dealer->user_tel      = isset( $request->user_tel)?trim($request->user_tel):'';
                $dealer->ship_name     = isset( $request->ship_name)?trim($request->ship_name):'';
                $dealer->ship_phone    = isset( $request->ship_phone)?trim($request->ship_phone):'';
                $dealer->ship_tel      = isset( $request->ship_tel)?trim($request->ship_tel):'';
                $dealer->ship_address  = isset( $request->ship_address)?trim($request->ship_address):'';
                $dealer->status        = isset( $request->status)?1:0;
                $dealer->credit_pay    = isset( $request->credit_pay)?1:0;
                
                $dealer->enable_date   = isset( $request->enable_date)?trim($enableDate):'';
                $dealer->logo_color1   = isset( $request->logocolor1)?trim($request->logocolor1):'#fff';
                $dealer->logo_color2   = isset( $request->logocolor2)?trim($request->logocolor2):'#fff';
               
                if( $request->file('mainpic') != null){
                    $dealer->logo1         = 'wlogo.'.$mainpicExtension;
                }/*else{
                    $dealer->logo1  = '';
                }*/
                if( $request->file('thumbnail') != null){
                    $dealer->logo2         = 'mlogo.'.$thumbnailExtension;
                }/*else{
                    $dealer->logo2 = '';
                }*/
                $dealer->multiple      = isset( $request->multiple)?trim($request->multiple):'2.2';
                
                $dealer->note          = $request->note;

                // banner儲存
                $bannerArr = ["0","1","2","3"];
                
                // 如果代碼不存在 , 則給預設1  
                if( !in_array($request->web_type, $bannerArr) || !isset($request->web_type) ){
                    
                    $webBanner = 1;

                }else{
                    $webBanner = $request->web_type;
                }

                // 如果代碼不存在 , 則給預設1  
                if( !in_array($request->mobile_type, $bannerArr) || !isset($request->mobile_type) ){
                    
                    $mobileBanner = 1;

                }else{
                    $mobileBanner = $request->mobile_type;
                }                
                
                $dealer->banner_type_w = $webBanner;

                $dealer->banner_type_m = $mobileBanner;

                $dealer->save();
                
                // 清除會員所有可用分類
                DealerCategory::where('dealer_id', $request->dealerId )->delete();

                if( isset( $request->allCategory ) ){

                    foreach ($request->allCategory as $allCategory) {
                    
                        $dealerCategory = new DealerCategory;

                        $dealerCategory->dealer_id = $request->dealerId;

                        $dealerCategory->category_id = $allCategory;

                        $dealerCategory->save();
                    }
                }

                /*****************************************************************
                 * 編輯經銷商可用商品
                 *
                 */

                // 清除會員所有可用商品
                DealerGoods::where('dealer_id', $request->dealerId )->delete();
                
                // 如果有接收到可用商品陣列 , 則將其存入資料庫
                if( isset( $request->ableGoods ) ){
                    
                    foreach ($request->ableGoods as $ableGood) {
                    
                        $dealerGoods = new DealerGoods;

                        $dealerGoods->dealer_id = $request->dealerId;

                        $dealerGoods->goods_id  = $ableGood;

                        $dealerGoods->save();
                    }                    
                }
     
                DB::commit();
                if( Auth::user()->hasRole('Admin') ){
    
                    return redirect('/newdealer')->with('successMsg', '經銷商編輯成功');
    
                }elseif( Auth::user()->hasRole('Dealer') ){
                    return redirect('/newdealerEdit/'.$user->id)->with('successMsg', '經銷商編輯成功');
                }
    
            } catch (Exception $e) {
    
                DB::rollback();
                //$e->getMessage();
    
                // 寫入錯誤代碼後轉跳
                
                logger("{$e->getMessage()} \n\n-----------------------------------------------\n\n"); 
    
                return back()->withInput()->with('errorMsg', '經銷商編輯失敗 , 請稍後再試' );         
            } 

        }elseif( Auth::user()->hasRole('Dealer') ){
            
            try {

                $user = User::find($request->dealerId);
                $user->name = $request->account;
                if( !empty( $request->oldpassword ) ){
                    $user->password = Hash::make( $request->password1 );
                }
                $user->save();
                
                $dealer = Dealer::where('dealer_id',$request->dealerId)->first();
                if( $dealer == NULL ){
    
                    $dealer = new Dealer();
                }      

                $dealer->multiple      = isset( $request->multiple)?trim($request->multiple):'2.0';

                $dealer->ship_name     = isset( $request->ship_name)?trim($request->ship_name):'';
                $dealer->ship_phone    = isset( $request->ship_phone)?trim($request->ship_phone):'';
                $dealer->ship_tel      = isset( $request->ship_tel)?trim($request->ship_tel):'';
                $dealer->ship_address  = isset( $request->ship_address)?trim($request->ship_address):'';                

                $dealer->save();

                DB::commit();
                
                return redirect('newdealerEdit/'.$user->id)->with('successMsg', '經銷商編輯成功');          


            } catch (Exception $e) {

                DB::rollback();
                //$e->getMessage();
    
                // 寫入錯誤代碼後轉跳
                
                logger("{$e->getMessage()} \n\n-----------------------------------------------\n\n"); 
    
                return back()->withInput()->with('errorMsg', '經銷商編輯失敗 , 請稍後再試' );                    
            }
        }        
    }
    



    /*----------------------------------------------------------------
     | 可用類別及商品編修
     |----------------------------------------------------------------
     |
     */
    public function newdealerCategoryAndGoods( Request $request ){
    
        $pageTitle = "經銷商可用分類及商品";

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

            return back()->with('errorMsg', '無此操作權限 , 請勿嘗試非法操作' );

        }

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

        // 取出所有分類
        $categorys = Category::where("status",1)->orderBy('sort','ASC')->get();
        $categorys = json_decode($categorys,true);
        
        // 取出所有有選擇的分類
        $ablecategorys = DealerCategory::where('dealer_id',$request->id)->get();
        $ablecategorys = json_decode($ablecategorys,true);
        
        $tmpAbleCategory = [];
        foreach ($ablecategorys as $ablecategorys) {

            array_push($tmpAbleCategory, $ablecategorys['category_id']);
        }
        
        /*****************************************************************
         * 取出所有商品 , 以及當前經銷商可用商品 , 整理完格式後傳給前台
         * 作呈現
         *
         */

        // 取出所有商品跟類別資料
        $goods = Goods::leftJoin('category', function($join) {

            $join->on('goods.cid', '=', 'category.id');
        })->select('goods.*', 'category.id as categoryId' , 'category.name as categoryName')->get();

        $goods = json_decode($goods,true);
        
        // 將商品資料整理 , 方便呈現
        $selectGoods = [];

        foreach ( $goods as $good ) {
            
            // 如果 selectGoods 不包含商品類別名稱的key值 , 則需要新增一個
            if( !array_key_exists($good['categoryName'], $selectGoods) ){
                
                $selectGoods[ $good['categoryName'] ] = [];
            }

            array_push( $selectGoods[ $good['categoryName'] ] , [ 'name' => $good['name'],
                                                                  'id'   => $good['id'],
                                                                  'goods_sn' => $good['goods_sn'],
                                                                ]);
        }

        $getAbleGoods = DealerGoods::where('dealer_id',$request->id)->get();
        
        $ableGoods = [];

        foreach ($getAbleGoods as $getAbleGood ) {

            array_push( $ableGoods, $getAbleGood['goods_id'] );

        }

        return view('dealerAble')->with(['title'     => $pageTitle,
                                         'dealer'    => $dealer,
                                         'categorys' => $categorys,
                                         'ablecategorys' => $tmpAbleCategory,
                                         'selectGoods'   => $selectGoods,
                                         'ableGoods' => $ableGoods,
                                        ]);          
    }
    



    /*----------------------------------------------------------------
     | 經銷商可用商品實作
     |----------------------------------------------------------------
     |
     */
    public function newdealerCategoryAndGoodsDo( Request $request ){

        // 列表功能一定要系統方才能查看
        if( Auth::user()->hasRole('Admin') ){

            // 確認權限
            if( !Auth::user()->can('dealerNew') ){

                return back()->with('errorMsg', '帳號無此操作權限 , 如有需要請切換帳號或聯絡管理員增加權限' );
            }

        }elseif( Auth::user()->hasRole('Dealer') ){

            return back()->with('errorMsg', '無此操作權限 , 請勿嘗試非法操作' );

        }

        $validator = Validator::make($request->all(), [
            'ableWay'    => 'required',
        ],[
            'ableWay.required' => '可用商品模式必須選擇"選單選取"或者"輸入貨號"',

        ]  );
        // 檢驗資料

        $errText = '';
        
        if( empty( $request->dealerId ) ){
            
            $errText = "缺少必要參數 , 無法進行操作";
        }
        if( !empty( $errText ) ){

            return back()->with('errorMsg', $errText );

        }    

        DB::beginTransaction();
        try {

            // 清除會員所有可用分類
            
            DealerCategory::where('dealer_id', $request->dealerId )->delete();

            if( isset( $request->allCategory ) ){

                foreach ($request->allCategory as $allCategory) {
                    
                    $dealerCategory = new DealerCategory;

                    $dealerCategory->dealer_id = $request->dealerId;

                    $dealerCategory->category_id = $allCategory;

                    $dealerCategory->save();
                }
            }

            /*****************************************************************
             * 編輯經銷商可用商品
             *
             */
            
            if( $request->ableWay == 1 ){
                // 清除會員所有可用商品
                DealerGoods::where('dealer_id', $request->dealerId )->delete();
                
                // 如果有接收到可用商品陣列 , 則將其存入資料庫
                if( isset( $request->ableGoods ) ){
                    
                    foreach ($request->ableGoods as $ableGood) {
                    
                        $dealerGoods = new DealerGoods;

                        $dealerGoods->dealer_id = $request->dealerId;

                        $dealerGoods->goods_id  = $ableGood;

                        $dealerGoods->save();
                    }                    
                }
            
            }elseif( $request->ableWay == 2 ){
                
                if( isset($request->ableGoodsText) ){
                    
                    // 清除會員所有可用商品
                    DealerGoods::where('dealer_id', $request->dealerId )->delete();

                    $ableWayTexts = explode("\n",$request->ableGoodsText);
                    
                    foreach ( $ableWayTexts as $ableWayText ) {

                        if( $this->checkGoodsExist( trim($ableWayText) ) ){
                            
                            $tmpAbleGood = $this->goodsSnToId( trim($ableWayText) );
                            
                            $dealerGoods = new DealerGoods;

                            $dealerGoods->dealer_id = $request->dealerId;

                            $dealerGoods->goods_id  = trim($tmpAbleGood);

                            $dealerGoods->save();
                        }
                    }

                }

            }
     
            DB::commit();
            
           
            return redirect('/newdealer')->with('successMsg', '經銷商可用分類及商品編輯成功');

    
        } catch (Exception $e) {
    
            DB::rollback();
            ///$e->getMessage();
    
            // 寫入錯誤代碼後轉跳
                
            logger("{$e->getMessage()} \n\n-----------------------------------------------\n\n"); 
    
            return back()->withInput()->with('errorMsg', '經銷商可用分類及商品編輯失敗 , 請稍後再試' );         
        }         
    }




    /*----------------------------------------------------------------
     | 編輯經銷商密碼
     |----------------------------------------------------------------
     |
     */
    public function dealerPassword( Request $request ){
        
        $pageTitle = "經銷商密碼修改";

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

        return view('dealerPassword')->with(['title'     => $pageTitle,
                                         'dealer'    => $dealer,
                                        ]);         
    }




    /*----------------------------------------------------------------
     | 編輯經銷商密碼實作
     |----------------------------------------------------------------
     |
     */
    public function dealerPasswordDo( Request $request ){

        // 列表功能一定要系統方才能查看
        if( Auth::user()->hasRole('Admin') ){

            // 確認權限
            if( !Auth::user()->can('dealerNew') ){

                return back()->with('errorMsg', '帳號無此操作權限 , 如有需要請切換帳號或聯絡管理員增加權限' );
            }

        }elseif( Auth::user()->hasRole('Dealer') ){
            
            if( empty( $request->dealerId ) || !$this->chkDealer($request->dealerId) ){

                return back()->with('errorMsg', '要編輯的經銷商非您所擁有 , 請勿嘗試非法操作' );
            }

        }        
        // 檢驗資料
        $validator = Validator::make($request->all(), [
            'password1'  => 'nullable|same:password2',
        ],[
            'account.required' => '帳號為必填',
            'account.max'      => '帳號最多為64個字元',
            'password1.required' => '密碼為必填',
            'password1.same'     => '密碼驗證不一致',
        ]  );

        $errText = '';
        
        if ($validator->fails()) {
                
            $errors = $validator->errors();
                
            foreach( $errors->all() as $message ){
                    
                $errText .= "$message<br>";
            }

        }
        if( !empty( $request->oldpassword ) ){
            
            $user = User::find($request->dealerId);
            
            if( !Hash::check($request->oldpassword, $user->password) ){

                $errText .= '舊密碼輸入錯誤<br>';
            }
        }
        if( !empty( $errText ) ){

            return back()->with('errorMsg', $errText );

        }            
        DB::beginTransaction();
        try {

                $user = User::find($request->dealerId);
                if( !empty( $request->oldpassword ) ){
                    $user->password = Hash::make( $request->password1 );
                }
                $user->save();
                
                DB::commit();
                
                if( Auth::user()->hasRole('Dealer') ){
                    
                    
                    return redirect('newdealerEdit/'.$user->id)->with('successMsg', '經銷商密碼修改成功 , 系統即將自動登出 , 請稍後自行登入'); 
                
                }else{
                    
                    return redirect('newdealerEdit/'.$user->id)->with('successMsg', '經銷商密碼修改成功');          
                }


        } catch (Exception $e) {

                DB::rollback();
                
                logger("{$e->getMessage()} \n\n-----------------------------------------------\n\n"); 
    
                return back()->withInput()->with('errorMsg', '經銷商密碼修改失敗 , 請稍後再試' );                    
        }

    }




    /*----------------------------------------------------------------
     | 經銷商刪除
     |----------------------------------------------------------------
     | 系統方單方面可以執行刪除經銷商的相關資料
     |
     */
    public function dealerDeleteDo( Request $request ){
        
        if( Auth::user()->hasRole('Admin') ){
            
            // 確認有刪除經銷商的權限
            if( !Auth::user()->can('dealerDelete') ){

                return back()->with('errorMsg', '帳號無此操作權限 , 如有需要請切換帳號或聯絡管理員增加權限' );
            }            

        }else{
        
            return back()->with('errorMsg', ' 無此權限 , 請勿嘗試非法操作' ); 
        
        }

        // 檢驗資料
        $validator = Validator::make($request->all(), [
            'id'    => 'required|exists:users,id',


  
        ],[
            'id.required'     => '缺少必要參數',
            'id.exists' => '要刪除的經銷商不存在',
        ]  );
        
        $errText = '';
        
        if ($validator->fails()) {
                
            $errors = $validator->errors();
                
            foreach( $errors->all() as $message ){
                    
                $errText .= "$message<br>";
            }

        }       

        if( !empty( $errText ) ){

            return back()->with('errorMsg', $errText );

        }

        $dealer = User::find( $request->id );

        if( $dealer->delete() ){

            return redirect('/newdealer')->with('successMsg', '經銷商刪除成功');

        }else{

            return redirect('/newdealer')->with('erroeMsg', '經銷商刪除失敗請稍後再試');
        }

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
    



    /*----------------------------------------------------------------
     | 下載QR code 
     |----------------------------------------------------------------
     |
     */
    public function qrDownload( Request $request ){

        $tmpDatas = User::find( $request->id );

        $url = "https://".$_SERVER['SERVER_NAME'].'/'.$tmpDatas->detect;


        $image = QrCode::format('png')
                        ->size(110)
                        ->errorCorrection('H')
                        ->margin(0)
                        ->generate($url);
        
        $output_file = 'qr/1/qrcode'.'.png';
        Storage::disk('goodsImage')->put($output_file, $image);        

        return response()->download( public_path(). "/qr/1/qrcode.png")->deleteFileAfterSend(true);

    }
    



    /*----------------------------------------------------------------
     | 確認商品存在
     |----------------------------------------------------------------
     |
     */
    public function checkGoodsExist( $_goodsSn ){
        
        return Goods::where("goods_sn",$_goodsSn)->exists();

    }

    /*----------------------------------------------------------------
     | 商品sn轉換為id
     |----------------------------------------------------------------
     |
     */
    public function goodsSnToId( $_goodsSn ){
        
        $tmpId = Goods::where("goods_sn",$_goodsSn)->first();
        $tmpId = $tmpId->id;
        return $tmpId;

    }    


}
