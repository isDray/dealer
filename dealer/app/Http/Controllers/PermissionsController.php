<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// 權限model
use App\User;
use App\Permission;

// 使用驗證
use App\Http\Requests\vPermissionsNew;
class PermissionsController extends Controller
{
    
    // 列表
    public function index(){
        


        $pageTitle = '權限列表';
        
        // 取出所有權
        $permissions = Permission::get();

        
    	return view('permissionsList')->with(['title'=>$pageTitle,
    		                                  'permissions'=>$permissions]);

    }

    // 新增權限頁面
    public function new(){

    	$pageTitle = '新增權限';

    	return view('permissionsNew')->with(['title'=>$pageTitle]);
    }

    // 新增權限動作
    public function newDo(vPermissionsNew $request){
        
        

        try {
            
            $createPost = new Permission();
            $createPost->name         = trim($request->code);
            $createPost->display_name = trim($request->name);
            $createPost->description  = trim($request->note);
            $createPost->save();
            
            return redirect('permissionsNew')->with('successMsg', '權限新增成功');

        }catch(\Exception $e){
            
            //$e->getMessage();

                // 寫入錯誤代碼後轉跳
            logger("{$e->getMessage()} \n\n-----------------------------------------------\n\n"); 
            
            return back()->with('errorMsg', '權限新增失敗');

        }

    }

    // 編輯權限
    public function edit( $_id = '' ){
        

        $pageTitle = '編輯權限';

        if( empty( $_id ) ){

            return redirect('permissions');
        }
        
        if( !$this->permissionsExist( $_id) ){

        	return redirect('permissions');
        }
        
        // 取出權限
        $permissions = $this->getPermissions( $_id );

        return view('permissionsEdit')->with(['title'=>$pageTitle,
        	                                  'permissions'=>$permissions]);
    }

    // 編輯權限動作
    public function editDo(vPermissionsNew $request){
    	
        if( empty( $request->id ) ){

            return redirect()->back();
        }
        
        if( !$this->permissionsExist( $request->id) ){

        	return redirect()->back();
        }
        try {
           
            $Permission = Permission::find($request->id);
            $Permission->name         = trim($request->code);
            $Permission->display_name = trim($request->name);
            $Permission->description  = trim($request->note);
            $Permission->save();

            return redirect("permissions")->with('successMsg', '權限編輯成功');

        }catch(\Exception $e){
            
            //$e->getMessage();

                // 寫入錯誤代碼後轉跳
            logger("{$e->getMessage()} \n\n-----------------------------------------------\n\n"); 
            
            return back()->with('errorMsg', '權限編輯失敗');

        }        
    }

    public function DeleteDo( Request $request){
    	
    	if( empty( $request->id ) ){

            return redirect()->back();
        }
        
        if( !$this->permissionsExist( $request->id) ){

        	return redirect()->back();
        }

        try {

            $Permission = Permission::find($request->id);

            $Permission->delete();

            return redirect("permissions")->with('successMsg', '權限刪除成功');

        }catch(\Exception $e){
            
            //$e->getMessage();

                // 寫入錯誤代碼後轉跳
            logger("{$e->getMessage()} \n\n-----------------------------------------------\n\n"); 
            
            return back()->with('errorMsg', '權限刪除失敗');

        }        
    }
    
    /*----------------------------------------------------------------
     | 工具區
     |
     */

    // 確認權限存在
    protected  function permissionsExist( $_id ){
        
        return Permission::where('id',"$_id")->exists();
    }

    // 取出權限
    protected function getPermissions( $_id ){

    	return Permission::where('id',"$_id")->first();
    }
}
