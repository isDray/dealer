<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\User;
use App\Role;
use App\Permission;

use App\freeHelper\rolePermissionTool;
// 使用驗證
use App\Http\Requests\vRoleNew;


class RoleController extends Controller
{    function __construct(){
        session(['activeMenu' => 'permission2']);
    }
    // 身分管理列表
    public function index(){
        
        $pageTitle = "身分管理";
        
        // 取得所有身分
        $roles = rolePermissionTool::getAllRoles();

        return view('roleList')->with(['title'=>$pageTitle,
                                       'roles'=>$roles 
                                       ]);
	}

    // 身分管理新增
    public function new(){

        $pageTitle = '新增身分';
        
        // 撈出所有權限
        $permissions = Permission::get();
        
        return view('roleNew')->with(['title'=>$pageTitle,
                                             'permissions'=>$permissions]);
    }

    // 身分管理新增執行
    public function newDo( vRoleNew $request ){
        
        
        //迴圈整理出合格權限
        $addPermissions = [];
        if( count($request->permissions) > 0){ 
        foreach ($request->permissions as $permission) {

            if( rolePermissionTool::permissionsExist( $permission ) ){

                array_push($addPermissions, $permission);

            }
        }
        }
        //rolePermissionTool::permissionsExist()
        DB::beginTransaction();
        try {
            
            
            
                $owner = new Role();
                $owner->name         = $request->code;
                $owner->display_name = $request->name;
                $owner->description  = $request->note;
                $owner->save();
                
                foreach ($addPermissions as $addPermission) {
                    
                   $tmpPermission = Permission::find($addPermission);
                   $owner->attachPermission($tmpPermission);

                }    

               
            
            DB::commit();

            return redirect('/role')->with('successMsg', '身分新增成功');

        }catch(\Exception $e){

            DB::rollback();
            //$e->getMessage();

            // 寫入錯誤代碼後轉跳
            
            logger("{$e->getMessage()} \n\n-----------------------------------------------\n\n"); 
            
            return back()->with('errorMsg', '身分新增失敗');
            

        }
    }


    // 編輯身分
    public function edit( $_id = '' ){
        
        $pageTitle = "編輯身分";

        // 取出身分相關資訊
        if( empty( $_id ) ){

            return redirect('/role');

        }

        // 確認身分存在
        if( !rolePermissionTool::roleExist($_id) ){

            return redirect('/role');
        }

        // 取出身分
        $role = rolePermissionTool::getRole( $_id );
         
        
        // 取出所有權限
        $permissions = rolePermissionTool::getAllPermissions();

        // 取出啟用的權限
        $tmpPermissionUses = $role->perms()->get();
        
        $permissionUse = [];

        foreach ($tmpPermissionUses as $tmpPermissionUse) {
            
            array_push($permissionUse, $tmpPermissionUse->id) ;

        }
       
        
        return view('roleEdit')->with(['title'         => $pageTitle,
                                       'role'          => $role,
                                       'permissions'   => $permissions,
                                       'permissionUse' => $permissionUse ]);
    }

    // 編輯身分執行
    public function editDo( vRoleNew $request ){

        $role = Role::find( $request->id );

       
        //迴圈整理出合格權限
        $addPermissions = [];
        if( count($request->permissions) > 0){ 
        foreach ($request->permissions as $permission) {

            if( rolePermissionTool::permissionsExist( $permission ) ){

                array_push($addPermissions, $permission);

            }
        }       
        }


        //$role->perms()->sync([])
        
        DB::beginTransaction();
        try {
            

                $owner = Role::find($request->id);
                $owner->name         = $request->code;
                $owner->display_name = $request->name;
                $owner->description  = $request->note;
                $owner->save();


                $owner->perms()->sync([]);

                foreach ($addPermissions as $addPermission) {
                    
                   $tmpPermission = Permission::find($addPermission);
                   $owner->attachPermission($tmpPermission);

                }    

               
                
            DB::commit();

            return redirect("roleEdit/$request->id")->with('successMsg', '身分編輯成功');

        }catch(\Exception $e){

            DB::rollback();
            //$e->getMessage();

            // 寫入錯誤代碼後轉跳
            
            logger("{$e->getMessage()} \n\n-----------------------------------------------\n\n"); 
            
            return back()->with('errorMsg', '身分編輯失敗');
            

        }
    
    }
    
    public function DeleteDo( Request $request){

        $role = Role::find( $request->id );
        
        // 避免兩組主要身分被刪除
        if( $role->name != 'Admin' && $role->name != 'Dealer' ){

            
            if( $role->delete() ){

                return redirect("role")->with('successMsg', '身分刪除成功');

            }else{

                return back()->with('errorMsg', '身分刪除失敗');
            }
        
        }else{

            return back()->with('errorMsg', '此身分無法進行刪除動作');
        }
    }
}
