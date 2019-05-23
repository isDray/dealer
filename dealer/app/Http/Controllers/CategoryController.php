<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;

// 使用商品類別輔助工具
use App\freeHelper\categoryTool;

// 使用類別Model
use App\Category;
use DB;

class CategoryController extends Controller
{
    // 清單區塊
    public function index(){
        
        // title 名稱
    	$pageTitle = "類別管理";

        // 取出所有分類
        $category = categoryTool::getAllCategoryForSelect();

        return view('categoryList')->with(['title'     => $pageTitle,
                                           'categorys' => $category
                                         ]);
    }
    
    /*----------------------------------------------------------------
     | 類別查詢
     |----------------------------------------------------------------
     |
     */
    public function query( Request $request ){
        
        $orderItems  = [
                        '1'=>'sort',
                        '2'=>'status',
                        '3'=>'updated_at',
                      ];
        
        // 整理排序關鍵字
        if( array_key_exists($request->order['0']['column'], $orderItems )){

            $orderBy = $orderItems[ $request->order['0']['column'] ];
        
        }else{

            $orderBy = '';
        }
        
        
        $orderWay = $request->order['0']['dir'];

        $query = DB::table('category');
        
        $recordsTotal = $query->count();


        // 商品名稱
        if( !empty( $request->myKeyword ) ){
            
            $query->where('name','like',"%{$request->myKeyword}%");
        }
        
        // 如果有排序就執行
        if( !empty( $orderBy ) ){
            
            $query->orderBy($orderBy , $orderWay );

        }

        

        $suitNum      = $query->count();

        $query->offset( $request->start );

        $query->limit( $request->length );            

        $datas = $query->get();
    
        $returnData = [];

        foreach($datas as $key => $value) {

            array_push($returnData, [
                $value->id,
                $value->name,
                $value->status,
                $value->sort,
                $value->updated_at,
            ]);
        }

        return json_encode( ['data'=>$returnData , 'recordsTotal'=>$recordsTotal, 'recordsFiltered'=>$suitNum] );
    }

    // 新增類別介面
    public function new(){
    	
    	$pageTitle = "新增類別";
    	$title     = "類別";

        // 取出所有分類
        $category = categoryTool::getAllCategoryForSelect();
        
        //$category = $category->toArray();
        //$category = [];

        return view('categoryNew')->with(['title'     => $pageTitle,
                                          'categorys' => $category
                                         ]);    	
    }

    // 新增類別執行
    public function newDo( Request $request ){
    	
        /* 驗證表單區域
         * ----------------------------------------------------------------
         */

        // 如果在有選擇父類別的情形下 , 則需要多驗證是否存在的類別
        $not_parent = '';

        if( $request->parents != 0 ){

            $not_parent = '|exists:category,id';
        }
        
        // 驗證規則
        $validatedData = $request->validate([
            'name'    => 'required|unique:category,name|max:100',
            'sortname'=> "required|max:100",
            'parents' => "required|integer{$not_parent}",
            'keyword' => 'max:255',
            'desc'    => 'max:255',
            'sort'    => 'required|integer',
            'thumbnail' => 'required|image',
        ],
        // 錯誤訊息
        [ 'name.required' => '類別名稱為必填',
          'name.unique'   => '類別名稱重複',
          'name.max'      => '類別名稱長度過長(100字元)',
          'sortname.required' => '短類別名為必填',
          'sortname.max'  =>'短類別名稱長度過長(100字元)',           
          'parents.required' => '父類別不可為空值',
          'parents.integer'  => '父類別格式錯誤',
          'parents.exists'   => '父類別不存在',
          'keyword.max'      => '關鍵字長度過長(255字元)',
          'desc.max'         => '類別描述長度過長(255字元)',
          'sort.required'    => '排序為必填',
          'sort.integer'     => '排序格式錯誤',
          'thumbnail.required' => '類別icon必須上傳',
          'thumbnail.image'    => '類別icon格式錯誤',             

        ]);
        

        try {

            $category = new Category;

            $category->name    = $request->name;
            $category->parent  = $request->parents;
            $category->keyword = $request->keyword;
            $category->desc    = $request->desc;
            
            // 如果有勾選啟用寫入1
            if( isset($request->status) ){
                
                $category->status  = true;

            }else{
                
                $category->status  = false;
            }

            $category->sort    = $request->sort;

            $category->save();
            
            if( isset($request->thumbnail) ){
                $mainPic      = $request->file('thumbnail');

                $nowExtension = $mainPic->extension();
            
                if( $mainPic->storeAs("images/category/","{$category->id}.$nowExtension",'goodsImage') ){

                    $category->category_pic = "images/category/{$category->id}.$nowExtension";
                    $category->save();
                
                }   
            }            

            return redirect("/category")->with('successMsg', '新增類別成功');

        }catch(\Exception $e){
            
            //$e->getMessage();

            // 寫入錯誤代碼後轉跳
            logger("{$e->getMessage()} \n\n-----------------------------------------------\n\n"); 
            
            return back()->with('errorMsg', '新增類別失敗');

        } 

        
    }

    // 商品類別編輯介面
    public function edit( Request $request ){
        
        // 確認有接收到一組合格的商品類別id , 如果不通過則直接返回上一頁
        if( empty($request->id) || !categoryTool::categoryExist( $request->id ) ){
            
            return redirect()->back();

        }

        // 頁面title
        $pageTitle = "編輯類別";
        
        // 取出所有分類
        $category     = categoryTool::getAllCategoryForSelect();
        
        // 取出要編輯的類別
        $editCategory = categoryTool::getCategory( $request->id );
        
        // 將要編輯的類別轉換為陣列
        $editCategory = $editCategory->toArray();

        return view('categoryEdit')->with(['title'        => $pageTitle,
                                           'categorys'    => $category,
                                           'editCategory' => $editCategory
                                         ]);   
    }

    // 商品類別編輯介面
    public function editDo( Request $request ){

        // 確認有接收到一組合格的商品類別id , 如果不通過則直接返回上一頁
        if( empty($request->id) || !categoryTool::categoryExist( $request->id ) ){
            
            return redirect()->back();

        }

        // 編輯前驗證
        // 如果在有選擇父類別的情形下 , 則需要多驗證是否存在的類別
        $not_parent = '';

        if( $request->parents != 0 ){

            $not_parent = '|exists:category,id';
        }
        
        // 驗證規則
        $validatedData = $request->validate([
            'name'    => "required|unique:category,name,{$request->id}|max:100",
            'sortname'=> "required|max:100",
            'parents' => "required|integer{$not_parent}",
            'keyword' => 'max:255',
            'desc'    => 'max:255',
            'sort'    => 'required|integer',
            'thumbnail' => 'image',
        ],
        // 錯誤訊息
        [ 'name.required' => '類別名稱為必填',
          'name.unique'   => '類別名稱重複',
          'name.max'      => '類別名稱長度過長(100字元)',
          'sortname.required' => '短類別名為必填',
          'sortname.max'  =>'短類別名稱長度過長(100字元)',          
          'parents.required' => '父類別不可為空值',
          'parents.integer'  => '父類別格式錯誤',
          'parents.exists'   => '父類別不存在',
          'keyword.max'      => '關鍵字長度過長(255字元)',
          'desc.max'         => '類別描述長度過長(255字元)',
          'sort.required'    => '排序為必填',
          'sort.integer'     => '排序格式錯誤',
          'thumbnail.image'  => '類別icon格式錯誤'

        ]);        

        // 更新
        try {

            $category = Category::find($request->id);
            $category->name     = $request->name;
             $category->sortname     = $request->sortname;
            $category->parent   = $request->parents;
            $category->keyword  = $request->keyword;
            $category->desc     = $request->desc;
            if( isset($request->status) ){
                
                $category->status  = true;

            }else{
                
                $category->status  = false;
            }            
            $category->sort     = $request->sort;
            $category->save();
           
            if( isset($request->thumbnail) ){
                $mainPic      = $request->file('thumbnail');

                $nowExtension = $mainPic->extension();
            
                if( $mainPic->storeAs("images/category/","{$category->id}.$nowExtension",'goodsImage') ){

                    $category->category_pic = "images/category/{$category->id}.$nowExtension";
                    $category->save();
                
                }   
            }
        

            return redirect("/categoryEdit/{$request->id}")->with('successMsg', '商品分類編輯成功');

        }catch(\Exception $e){
            
            //$e->getMessage();

                // 寫入錯誤代碼後轉跳
            logger("{$e->getMessage()} \n\n-----------------------------------------------\n\n"); 
            
            return back()->with('errorMsg', '商品分類編輯失敗');

        }            

    }

    // 刪除商品類別
    public function DeleteDo( Request $request ){

        // 確認有接收到一組合格的商品類別id , 如果不通過則直接返回上一頁
        if( empty($request->id) || !categoryTool::categoryExist( $request->id ) ){
            
            return redirect()->back();

        }
        
        
        try {

            $category = Category::find($request->id);

            $category->delete();

            return redirect("/category")->with('successMsg', '商品分類刪除成功');

        }catch(\Exception $e){
            
            //$e->getMessage();

                // 寫入錯誤代碼後轉跳
            logger("{$e->getMessage()} \n\n-----------------------------------------------\n\n"); 
            
            return back()->with('errorMsg', '商品分類刪除失敗');

        } 
        
    }
}
