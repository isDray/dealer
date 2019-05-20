<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// 使用商品類別輔助工具
use App\freeHelper\categoryTool;

// 使用商品輔助工具
use App\freeHelper\goodsTool;

// 使用DB類
use DB;
use Auth;
use Validator;

// 使用Goods model
use App\Goods;
use App\GoodsPic;
use App\GoodsCat;
use App\GoodsStock;
use App\Dealer;
class GoodsController extends Controller
{
    
    /*----------------------------------------------------------------
     | 商品清單
     |----------------------------------------------------------------
     | 提供商品列表以及進階查詢功能 , 同時也提供新增、修改、刪除的操
     | 作連結
     |
     */

    public function index( Request $request ){
        $dfInput = $request->input();
        
        $dfStatus = 0;
        
        if( isset($dfInput['status']) ){

            $dfStatus = $dfInput['status'];
        }

        if( !Auth::user()->can('goodsList') ){
        
        }

    	// title 名稱
    	$pageTitle = "商品管理";

        // 取出所有分類
        $categorys = categoryTool::getAllCategoryForSelect();

        $goods = goodsTool::getAllGoods();

        $goods = $goods->toArray();

        return view('goodsList')->with([ 'title'     => $pageTitle,
                                         'goods'     => $goods,
                                         'categorys' => $categorys,
                                         'dfStatus'  => $dfStatus,
                                        ]);
    }


    // 商品新增介面
    public function new(){
        
        if( !Auth::user()->can('goodsNew') ){


        }
    	// title 名稱
    	$pageTitle = "新增商品";
        

        // 取出所有分類
        $category = categoryTool::getAllCategoryForSelect();

        return view('goodsNew')->with(['title'     => $pageTitle,
                                       'categorys' => $category
                                         ]);       	
    }

    // 商品新增執行
    public function newDo( Request $request){
    
        $appendMessage = [];
        
        if( isset($request->file) > 0){
            
            foreach ( $request->file as $key => $value) {

                $picNum = $key+1;
                $appendMessage["file.$key.image"] = "第{$picNum}張商品圖片格式錯誤";
            }
        }
        

        // 如果有新增擴展類則需要將其作驗證
        if( isset($request->multiplCategory) > 0 ){

            foreach ( $request->multiplCategory as $key => $value) {
                
                $nowNum = $key + 1 ;
                $appendMessage["multiplCategory.$key.exists"] = "第{$nowNum}項擴展分類為不存在的分類";

            }
        }
         
        $extendCategorys = [] ;
        
        // 刪除與主要分類重複的商品
        if( isset($request->multiplCategory) > 0 ){

            foreach ( $request->multiplCategory as $key => $value) {
                
                if( $value != $request->category ){

                    array_push( $extendCategorys , $value );
                }

            }
        }
        
        // 驗證規則
        $validatedData = $request->validate([

            'name'      => 'required|unique:goods,name|max:255',
            'goods_sn'  => 'required|unique:goods,goods_sn|max:100',
            'category'  => "required|integer|exists:category,id",
            'price'     => "required|integer",
            'wprice'    => "required|integer",
            'file.*'    => 'image',
            'mainpic'   => 'required|image',
            'thumbnail' => 'required|image',
            'multiplCategory.*'=>"exists:category,id",

            /*
            'keyword' => 'max:255',
            'desc'    => 'max:255',
            'sort'    => 'required|integer'*/
        ],
        // 錯誤訊息
        [ 'name.required' => '產品名稱為必填',
          'name.unique'   => '產品名稱重複',
          'name.max'      => '類別名稱長度過長(100字元)',

          'goods_sn.required' => '貨號為必填',
          'goods_sn.unique'   => '貨號重複',
          'goods_sn.max'      => '貨號長度過長(100字元)',

          'category.required' => '商品類別不可為空值',
          'category.integer'  => '商品類別格式錯誤',
          'category.exists'   => '商品類別不存在',

          'price.required'    => '商品售價為必填',
          'price.integer'     => '商品售價格式錯誤',

          'wprice.required'   => '商品批發價為必填',
          'wprice.integer'    => '商品批發價格式錯誤',
          
          'mainpic.required'  => '商品主圖必須上傳',
          'mainpic.image'     => '商品主圖格式錯誤',

          'thumbnail.required' => '商品縮圖必須上傳',
          'thumbnail.image'    => '商品縮圖格式錯誤',          

        ]+$appendMessage);
        
        // 新增置資料庫
        

        DB::beginTransaction();

        try {
            
            $goods = new Goods;

            $goods->name       = $request->name;

            $goods->goods_sn   = $request->goods_sn;

            $goods->cid        = $request->category;

            $goods->price      = $request->price;

            $goods->w_price    = $request->wprice;

            if( isset($request->status) ){
                
                $goods->status      = 1;
 
            }else{

                $goods->status      = 0;
        
            }
            
            /* 由於圖檔檔名是由商品id產生的,所以必須要先儲存,主圖跟
             * 縮圖後續再以更新的方式寫入
             */
            $goods->main_pic  = '';
            
            $goods->thumbnail = '';
              
            $goods->desc = $request->desc;

            $goods->save();

            if( count($extendCategorys) > 0 ){

                foreach ($extendCategorys as $extendCategoryk => $extendCategory) {
                
                    if( !GoodsCat::where('gid',"{$goods->id}")->where("cid",$extendCategory)->exists() ){
                        
                        $GoodsCat = new GoodsCat;
                        $GoodsCat->gid = $goods->id;
                        $GoodsCat->cid = $extendCategory;
                        $GoodsCat->save();


                    }
                }
            }
            
            // 儲存的資料夾以當日日期命名
            $folderName = date("Ymd");
            
            // 檔名以 日期_商品id 命名
            $fileName   = date("Ymd")."_".$goods->id;

            $mainPic      = $request->file('mainpic');

            $nowExtension = $mainPic->extension();
            
            if( $mainPic->storeAs("images/main/$folderName","$fileName.$nowExtension",'goodsImage') ){

                $goods->main_pic = "main/$folderName/$fileName.$nowExtension";
                $goods->save();
                
            }

            $thumbnailPic = $request->file('thumbnail');
            
            $nowExtension = $thumbnailPic->extension();
            
            if( $thumbnailPic->storeAs("images/thumbnail/$folderName","$fileName.$nowExtension",'goodsImage') ){

                $goods->thumbnail = "thumbnail/$folderName/$fileName.$nowExtension";
                $goods->save();
            }
            
            if( isset($request->file) > 0 ){
                
                foreach ( $request->file as $key => $value) {

                    $sortNum = $key+1;
                    $nowExtension =  $value->extension();

                    if(  $value->storeAs("images/other/$folderName","$fileName".'_'."$sortNum.$nowExtension",'goodsImage') ){
                    
                        $goodsPic = new GoodsPic;

                        $goodsPic->gid       = $goods->id;

                        $goodsPic->pic       = "images/other/$folderName/$fileName".'_'."$sortNum.$nowExtension";

                        $goodsPic->sort      = $sortNum;

                        $goodsPic->save();
                    }

                }
            }
            
            DB::commit();

            return redirect('/goods')->with('successMsg', '商品新增成功');

        }catch(\Exception $e){

            DB::rollback();
            //$e->getMessage();

            // 寫入錯誤代碼後轉跳
            
            logger("{$e->getMessage()} \n\n-----------------------------------------------\n\n"); 
            
            return back()->with('errorMsg', '商品新增失敗');
            

        }        

        /* 圖片上傳功能
        $files = $request->file('file');
        
        foreach ($files as  $filek => $file) {

            $nowExtension = $file->extension();

            $file->storeAs('images',"HAHAH$filek.$nowExtension",'goodsImage');

        }
        */

    }
    



    // 商品編輯頁面
    public function edit( Request $request ){
        
        // title 名稱
        $pageTitle = "編輯商品";

        // 確認要編輯的商品為存在的商品
        if( empty( $request->id ) || !goodsTool::goodsExist( $request->id ) ){

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


        return view('goodsEdit')->with([
                                        'title'     => $pageTitle,
                                        'categorys' => $category,
                                        'goodsData' => $goodsData,
                                        'goodsPics' => $goodsPics,
                                        'goodsCats' => $goodsCats
                                        ]);   
    }


    // 編修商品執行
    public function editDo( Request $request ){
        
        // 檢查是不是合格的商品ID
        if( empty( $request->id ) || !goodsTool::goodsExist($request->id ) ){
            
            return redirect()->back();

        }
        
        $appendMessage = [] ;
        // 如果有新增擴展類則需要將其作驗證

        if( isset($request->multiplCategory) ){

            foreach ( $request->multiplCategory as $key => $value) {
                
                $nowNum = $key + 1 ;
                $appendMessage["multiplCategory.$key.exists"] = "第{$nowNum}項擴展分類為不存在的分類";

            }
        }
         
        $extendCategorys = [] ;

        // 刪除與主要分類重複的商品
        if( isset($request->multiplCategory) ){

            foreach ( $request->multiplCategory as $key => $value) {
                
                if( $value != $request->category ){

                    array_push( $extendCategorys , $value );
                }

            }
        }

        // 驗證規則
        $validatedData = $request->validate([

            'name'      => "required|unique:goods,name,$request->id|max:255",
            'goods_sn'  => "required|unique:goods,goods_sn,$request->id|max:100",
            'category'  => "required|integer|exists:category,id",
            'price'     => "required|integer",
            'wprice'    => "required|integer",
            'file.*'    => 'image',
            'mainpic'   => 'image',
            'thumbnail' => 'image',
            'multiplCategory.*'=>"exists:category,id",

        ],
        // 錯誤訊息
        [ 'name.required' => '產品名稱為必填',
          'name.unique'   => '產品名稱重複',
          'name.max'      => '類別名稱長度過長(100字元)',

          'goods_sn.required' => '貨號為必填',
          'goods_sn.unique'   => '貨號重複',
          'goods_sn.max'      => '貨號長度過長(100字元)',

          'category.required' => '商品類別不可為空值',
          'category.integer'  => '商品類別格式錯誤',
          'category.exists'   => '商品類別不存在',

          'price.required'    => '商品售價為必填',
          'price.integer'     => '商品售價格式錯誤',

          'wprice.required'   => '商品批發價為必填',
          'wprice.integer'    => '商品批發價格式錯誤',
          
          'mainpic.required'  => '商品主圖必須上傳',
          'mainpic.image'     => '商品主圖格式錯誤',

          'thumbnail.required' => '商品縮圖必須上傳',
          'thumbnail.image'    => '商品縮圖格式錯誤',          

        ]+$appendMessage);
        
        //更新資料庫

        DB::beginTransaction();
        
        try {
            

            $goods = Goods::find( $request->id );

            $goods->name       = $request->name;

            $goods->goods_sn   = $request->goods_sn;

            $goods->cid        = $request->category;

            $goods->price      = $request->price;

            $goods->w_price    = $request->wprice;

            if( isset($request->status) ){
                
                $goods->status      = 1;
 
            }else{

                $goods->status      = 0;
        
            }
            
            $goods->desc = $request->desc;
            
            
            // 儲存的資料夾以當日日期命名
            $folderName = date("Ymd");
            
            // 檔名以 日期_商品id 命名
            $fileName   = date("Ymd")."_".$goods->id;
            
            if( isset($request->mainpic) ){

                $mainPic      = $request->file('mainpic');
    
                $nowExtension = $mainPic->extension();
                
                if( file_exists( public_path()."/images/".$goods->main_pic ) ){

                    unlink( public_path()."/images/".$goods->main_pic );
                }
                if( $mainPic->storeAs("images/main/$folderName","$fileName.$nowExtension",'goodsImage') ){
                    

                    $goods->main_pic = "main/$folderName/$fileName.$nowExtension";

                    
                }
            }
            if( isset($request->thumbnail) ){

                $thumbnailPic = $request->file('thumbnail');
                
                $nowExtension = $thumbnailPic->extension();
                
                if( file_exists( public_path()."/images/".$goods->thumbnail ) ){

                    unlink( public_path()."/images/".$goods->thumbnail );
                }

                if( $thumbnailPic->storeAs("images/thumbnail/$folderName","$fileName.$nowExtension",'goodsImage') ){
                     
                    $goods->thumbnail = "thumbnail/$folderName/$fileName.$nowExtension";

                }
            }

            $goods->save();

            
            GoodsCat::where('gid',"{$goods->id}")->delete();
            
            // 擴展類別更新
            if( count($extendCategorys) > 0 ){

                foreach ($extendCategorys as $extendCategoryk => $extendCategory) {
                
                    if( !GoodsCat::where('gid',"{$goods->id}")->where("cid",$extendCategory)->exists() ){
                        
                        $GoodsCat = new GoodsCat;
                        $GoodsCat->gid = $goods->id;
                        $GoodsCat->cid = $extendCategory;
                        $GoodsCat->save();


                    }
                }
            }

            DB::commit();

            return redirect('/goods')->with('successMsg', '商品編輯成功');

        }catch(\Exception $e){

            DB::rollback();
            //$e->getMessage();

            // 寫入錯誤代碼後轉跳
            
            logger("{$e->getMessage()} \n\n-----------------------------------------------\n\n"); 
            
            return back()->with('errorMsg', '商品編輯失敗');
            

        } 
    }



    // 商品刪除
    public function DeleteDo( Request $request ){
        
        if( !Auth::user()->can('goodsDelete') ){

            exit;
        }
        // 確定要刪除的商品確實存在
        if( empty( $request->id ) || !goodsTool::goodsExist( $request->id ) ){

            return redirect()->back();

        }


        // 執行相關刪除
        
        try {
        
            // 取出商品相關圖片 , 並且刪除
        
            // 主圖及縮圖
            $tmpPics = Goods::select('thumbnail','main_pic')->where('id',$request->id)->first();
            $tmpPics = $tmpPics->toArray();
        
            foreach ($tmpPics as $tmpPic ) {
            
                if( file_exists( public_path("images/$tmpPic") ) ){
                
                    unlink( public_path("images/$tmpPic") );
                }
            }

            // 其他相關圖片
            $tmpPics = GoodsPic::select('pic')->where('gid',$request->id)->get();
            $tmpPics = $tmpPics->toArray();
        
            foreach ($tmpPics as $tmpPic ) {
            
                if( file_exists( public_path("/{$tmpPic['pic']}") ) ){
 
                    unlink( public_path("/{$tmpPic['pic']}") );
                }
            }
            


            
            $goods = Goods::find($request->id);

            $goods->delete();
            

            return redirect("/goods")->with('successMsg', '商品刪除成功');

        }catch(\Exception $e){
            
            //$e->getMessage();

                // 寫入錯誤代碼後轉跳
            logger("{$e->getMessage()} \n\n-----------------------------------------------\n\n"); 
            
            return back()->with('errorMsg', '商品刪除失敗');

        } 
              
    }

   
    // AJAX 上傳圖片
    public function ajaxPic( Request $request ){
        
        // 檢測是否為圖片格式
        $validator = Validator::make($request->all(), [
            'file' => 'image ',
            
        ]);
        if ($validator->fails()) {

            echo json_encode(False);

        }
        else{

            if( empty($request->goodsid) || !goodsTool::goodsExist( $request->goodsid ) ){

                echo json_encode(False);

            }else{
                
                // 取得最後排序
                $sortNum = goodsTool::getMaxSort( $request->goodsid );
                
                if( empty($sortNum) ){

                    $sortNum = 1;

                }else{

                    $sortNum = $sortNum + 1;
                }

                $realSort =  $sortNum;
                $nowExtension = $request->file->extension();
                
                // 儲存的資料夾以當日日期命名
                $folderName = date("Ymd");
            
                // 檔名以 日期_商品id 命名
                $fileName   = date("Ymd")."_".$request->goodsid;
                while( goodsTool::goodsPicExist( $request->goodsid ,  "images/other/$folderName/$fileName".'_'."$sortNum.$nowExtension" ) ){
                    
                    $sortNum += 1;

                }
                if(  $request->file->storeAs("images/other/$folderName","$fileName".'_'."$sortNum.$nowExtension",'goodsImage') ){

                        $goodsPic = new GoodsPic;

                        $goodsPic->gid       = $request->goodsid;

                        $goodsPic->pic       = "images/other/$folderName/$fileName".'_'."$sortNum.$nowExtension";

                        $goodsPic->sort      = $realSort;
                        if( $goodsPic->save() ){

                            echo json_encode("images/other/$folderName/$fileName".'_'."$sortNum.$nowExtension");
                        }
                }

            }
        }
    }


    // AJAX 移除圖片
    public function ajaxPicDelete( Request $request ){
        
        if( empty($request->gid) || empty( $request->picPath ) ){

            echo json_encode([false , '缺少必要參數' ] );
            exit;
        }
        
        if( !goodsTool::goodsPicExist( $request->gid , $request->picPath ) ){
            
            echo json_encode([false , '要刪除的資料不存在' ] );
            exit;
        }

        if( file_exists(public_path().'/'.$request->picPath) ){

            unlink(public_path().'/'.$request->picPath);

        }
        $res = GoodsPic::where('gid',$request->gid)
                       ->where('pic',$request->picPath)
                       ->delete();

        if( $res ){

            echo json_encode([true , '刪除成功' ] );
            exit;

        }else{

            echo json_encode([false , '刪除失敗' ] );
            exit;
        }

        

    }



    public function ajaxPicSort( Request $request ){
        

        if( empty($request->gid) || empty( $request->sort ) ){

            echo json_encode([false , '缺少必要參數' ] );
            exit;
        }

        DB::beginTransaction();

        try {
            



            GoodsPic::where('gid', $request->gid)->update(['sort' => 0]);
            
            foreach ($request->sort as $key => $value) {
                
                GoodsPic::where('gid', $request->gid)
                        ->where('pic', $value)
                        ->update(['sort' => $key+1]);
        
            }
               
            
            DB::commit();
             
            echo json_encode([true]);

        }catch(\Exception $e){

            DB::rollback();
            //$e->getMessage();

            // 寫入錯誤代碼後轉跳
            
            logger("{$e->getMessage()} \n\n-----------------------------------------------\n\n"); 
            echo json_encode([false]);
        }        
    }

   

    /*----------------------------------------------------------------
     | 商品列表查詢
     |----------------------------------------------------------------
     | 根據接收到不同的條件回傳對應的商品資訊
     |
     |
     */
    public function query( Request $request ){
        
        $orderItems  = ['3'=>'status',
                        '4'=>'price',
                        '5'=>'w_price',
                        '6'=>'updated_at',
                      ];

        $orderWay = $request->order['0']['dir'];

        // 排序欄位 ( 依照哪一欄位做排序 )
        $orderBy = $orderItems[ $request->order['0']['column'] ];
        
        // 計算全部商品數量
        $recordsTotal = Goods::count();
         

        $query = DB::table('goods as g');
        
        $query->leftJoin('goods_cat as gc', 'g.id', '=', 'gc.gid');

        // 最低價
        if( !empty( $request->min_price )){

            $query->where( 'g.w_price', '>=', (int)"{$request->min_price}");

        }      

        // 最高價
        if( !empty( $request->max_price )){

            $query->where( 'g.w_price', '<=', (int)"{$request->max_price}");

        }
        
        // 關鍵字查詢
        if( !empty($request->myKeyword) ){
            

            $filterSearch = $request->myKeyword;

            $query->where(function ( $query_add  ) use ($filterSearch) {
                
                $query_add->where( 'g.name', 'like', "%{$filterSearch}%");

                $query_add->orWhere( 'g.goods_sn', 'like', "%{$filterSearch}%");
            
                $query_add->orWhere( 'g.updated_at', 'like', "%{$filterSearch}%");                


            });            

        }
        
        // 類別
        if( !empty( $request->category ) ){
            
            $filterCategory = $request->category;

            $query->where(function ( $query_add  ) use ($filterCategory) {
                
                $query_add->where( 'g.cid', $filterCategory );
                $query_add->orWhere( 'gc.cid',  $filterCategory );

            });

        }
        
        // 是否啟用
        if( !empty( $request->status ) ){
            
            if( $request->status == 1){
                
                $query->where( 'g.status', '1' );

            }else{
                
                $query->where( 'g.status', '0' );
            }
        }
        $query->groupBy( 'id' );
        $suitNum = $query->count();
        
        $query->offset( $request->start );
        
        $query->limit( $request->length );

        

        $query->orderBy($orderBy , $orderWay );
    
        $goods = $query->get();
        
        $goods = $goods->toArray();
        
        $returnData = [];

        foreach ($goods as $key => $value) {
            
            // 取出庫存總和
            $tmpRes = GoodsStock::selectRaw("SUM(goods_num) as stock")->where('goods_id',$value->id)->groupBy('goods_id')->first();
            if( $tmpRes!=NULL){
                $tmpStock = $tmpRes->stock;
            }else{
                $tmpStock = 0;
            }

            // 取出庫存來源
            $tmpDatas = GoodsStock::where('goods_id',$value->id)->get();

            if( count($tmpDatas) > 0 ){
                
                $returnStock = [];
    
                foreach ($tmpDatas as $tmpDatak => $tmpData) {
                    
                    $tmpDealer = Dealer::where('dealer_id',$tmpData->dealer_id)->first();
    
                    if( $tmpDealer != NULL ){
    
                        $tmpDealerName = "ID:".$tmpData->dealer_id.",".$tmpDealer->hotel_name;
    
                    }else{
                        $tmpDealerName = "ID:".$tmpData->dealer_id.",";
                    }
    
                    $returnStock[] = [ 'name'=> $tmpDealerName,
                                      'num' => $tmpData->goods_num
                                    ];
                }
    
                
            }else{

                $returnStock = [];
            }

            array_push($returnData, [
            $value->thumbnail,
            $value->name,
            $value->goods_sn,
            $value->status,
            $value->price,
            $value->w_price,
            $tmpStock,
            $value->updated_at,
            $value->id,
            $returnStock
                                ]);
            


        }

        echo json_encode( ['data'=>$returnData , 'recordsTotal'=>$recordsTotal, 'recordsFiltered'=>$suitNum] );
    }

    

    
    /*----------------------------------------------------------------
     | 撈出庫存細項
     |----------------------------------------------------------------
     |
     */

    public function ajaxStock( Request $request){
        
        if( !Auth::user()->hasRole('Admin') || empty($request->gid)){

            return json_encode([false,[]]);
        }
        
        $tmpDatas = GoodsStock::where('goods_id',$request->gid)->get();

        if( count($tmpDatas) > 0 ){
            
            $returnData = [];

            foreach ($tmpDatas as $tmpDatak => $tmpData) {
                
                $tmpDealer = Dealer::where('dealer_id',$tmpData->dealer_id)->first();

                if( $tmpDealer != NULL ){

                    $tmpDealerName = "ID:".$tmpData->dealer_id.",".$tmpDealer->hotel_name;

                }else{
                    $tmpDealerName = "ID:".$tmpData->dealer_id.",";
                }

                $returnData[] = [ 'name'=> $tmpDealerName,
                                  'num' => $tmpData->goods_num
                                ];
            }

            return json_encode([true,$returnData]);
        }else{

            return json_encode([false,[]]);
        }
    }
}
