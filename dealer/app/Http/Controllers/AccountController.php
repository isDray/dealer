<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


use App\freeHelper\accountTool;
use App\freeHelper\rolePermissionTool;

use App\User;
use App\Role;
use App\Permission;

// 使用驗證
use App\Http\Requests\vAccountNew;
use App\Http\Requests\vAccountEdit;

use Validator;

class AccountController extends Controller
{   

    // 帳號列表 
    public function index(){

    	$pageTitle = "帳號管理";
        
        // 取出所有使用者
        $tmpUsers = accountTool::getAllAccount();
        
        $users    = [];
        foreach ($tmpUsers as $tmpUser) {
            
            if( $tmpUser->hasRole('Admin') ){
                $rootRole = 'Admin';
            }

            if( $tmpUser->hasRole('Dealer') ){
                $rootRole = 'Dealer';
            }            

            $tmpArr = [ 'id'        => $tmpUser->id,
                        'name'      => $tmpUser->name,
                        'email'     => $tmpUser->email,
                        'updated_at'=> $tmpUser->updated_at,
                        'rootRole'  => $rootRole
                      ];

            array_push($users, $tmpArr);
        }

        return view('accountList')->with(['title' => $pageTitle,
                                          'users' => $users
                                         ]);
    }

    // 帳號新增
    public function new(){

    	$pageTitle = "新增帳號";
        
        // 最大兩個腳色分類
        $rootRoles = rolePermissionTool::getRootCategory();
        
        // 其餘腳色
        // 取出所有除了根身分之外的身分
        $childRoles = rolePermissionTool::getUnRootCategory();
        $childRoles= $childRoles->toArray();
        
        $tmpchildRoles = [];
        
        // 將key先行轉換 , 避免到view後跑太多迴圈
        foreach ($childRoles as $childRolek => $childRole) {
            

            $tmpchildRoles[$childRole['name']] = $childRole;
        }
        
        $childRoles = $tmpchildRoles;

        $groupRole = rolePermissionTool::$RoleCategory;
       
        return view('accountNew')->with(['title'      => $pageTitle,
        	                             'rootRoles'  => $rootRoles,
                                         'childRoles' => $childRoles,
                                         'groupRole'  => $groupRole
        	                             ]);
    }

    // 帳號新增
    public function newDo(vAccountNew $request){
       
        /*
        var_dump($request->input());
        
        $aa = Role::where('name','sssw')->first();
        if(!$aa){
            echo "ENTER";
        }
        exit;
        */


        DB::beginTransaction();

    	try {
    		
    		$user = new User();
    		$user->name = $request->name;
            $user->password = Hash::make( $request->password );
            $user->email =  $request->email;
            $user->phone    = $request->phone;
            $user->address  = $request->address;
            $user->save();

            $role = Role::where('name',$request->useTarget)->first();
            $user->attachRole($role);
            
            if( count($request->addRole) > 0){
            foreach ($request->addRole as $addRole) {
                
                $cRole = Role::where('name', $addRole)->first();
                $user->attachRole($cRole);

            }
            }

            DB::commit();
            
            return redirect('/account')->with('successMsg', '帳號新增成功');

    	} catch (Exception $e) {

            DB::rollback();
            //$e->getMessage();

            // 寫入錯誤代碼後轉跳
            
            logger("{$e->getMessage()} \n\n-----------------------------------------------\n\n"); 
            
            return back()->with('errorMsg', '帳號新增失敗');    		
    	}

    }    

    // 帳號編輯
    public function edit( $_id = '' ){
        
        if( empty( $_id ) ){

            return redirect('/account');
        }

        if( !accountTool::accountExist( $_id ) ){

            return redirect('/account');
        }

        $pageTitle = '編輯帳號';

        // 最大分類
        $rootRoles = rolePermissionTool::getRootCategory();
        


        // 取出該會員資料
        $user = accountTool::getAccount( $_id );
        
        $useRootRole = 'Dealer';
        
        if( $user->hasRole('Admin') ){
            
            $useRootRole = 'Admin';
        }
        

        // 取出所有除了根身分之外的身分
        $childRoles = rolePermissionTool::getUnRootCategory();
        $childRoles= $childRoles->toArray();
        
        $tmpchildRoles = [];
        
        // 將key先行轉換 , 避免到view後跑太多迴圈
        foreach ($childRoles as $childRolek => $childRole) {
            

            $tmpchildRoles[$childRole['name']] = $childRole;
        }
        
        $childRoles = $tmpchildRoles;

        // 取出會員的所有身分
        $allRoles = $user->roles()->get();
        
        $useChildRoles = [];

        // 排除根身分後 , 剩下的都是使用中身份
        foreach ($allRoles as  $allRolesk => $allRole) {
            
            if( $allRole->name !='Admin' && $allRole->name != 'Dealer'){
                
                array_push( $useChildRoles, $allRole->name );
            }
            
        }



        $groupRole = rolePermissionTool::$RoleCategory;

        return view('accountEdit')->with(['title'         => $pageTitle,
                                          'rootRoles'     => $rootRoles,
                                          'user'          => $user,
                                          'useRootRole'   => $useRootRole,
                                          'childRoles'    => $childRoles,
                                          'useChildRoles' => $useChildRoles,
                                          'groupRole'     => $groupRole
                                         ]);        
    }

    // 帳號編輯執行
    public function editDo(vAccountEdit $request){
           /* $role = Role::where('name',"Admin")->first();
            var_dump($role);
            //$user->detachRoles($role);
            
            $role = Role::where('name',"Dealer")->first();
            //$user->detachRoles($role);
            
            // 再把編輯的加上去
            $role = Role::where('name',$request->useTarget)->first();
           // $user->attachRole($role);
            exit;*/
        /*$validator = Validator::make($request->all(), [
            'name' => 'required|max:30',
            'email'=> 'required|email',
        ],[
            'name.required' => '姓名欄位為必填',
            'name.max'      => '姓名上限為30字元',
            'email.required'=> '信箱欄位為必填',
            'email.email'   => '信箱格式錯誤'
        ]);

        if ($validator->fails()) {
                
            $errText = '';

            $errors = $validator->errors();
                
            foreach( $errors->all() as $message ){
                    
                $errText .= "$message<br>";
            }

            return back()->with('errorMsg', $errText );

        }*/

        DB::beginTransaction();

        try {
            
            
            $user = User::find( $request->id );

            $user->name  = $request->name;

            $user->email = $request->email;
            
            if( !empty($request->password) ){
            
                $user->password = Hash::make( $request->password );
            
            }
            
            $user->phone    = $request->phone;

            $user->address  = $request->address;

            $user->save();
            
            $user->detachRoles($user->roles);
            /*
            $role = Role::where('name',"Dealer")->get();
            $user->detachRoles($role);

            $role = Role::where('name',"Admin")->get();
            $user->detachRoles($role);  
            */
            $role = Role::where('name',$request->useTarget)->first();

            $user->attachRole($role);

            if( count($request->addRole) > 0){

                foreach ($request->addRole as $addRole) {
                    
                    $cRole = Role::where('name', $addRole)->first();
                    $user->attachRole($cRole);
    
                }
            }

            DB::commit();
            
            return redirect('/account')->with('successMsg', '帳號編輯成功');

        } catch (Exception $e) {

            DB::rollback();
            //$e->getMessage();

            // 寫入錯誤代碼後轉跳
            
            logger("{$e->getMessage()} \n\n-----------------------------------------------\n\n"); 
            
            return back()->with('errorMsg', '帳號新增失敗');          
        }

    }

    // 刪除
    public function DeleteDo( Request $request ){

        $user = User::find( $request->id );
        
        if( $user->delete() ){

            return redirect("/account")->with('successMsg', '帳號刪除成功');

        }else{

            return back()->with('errorMsg', '帳號刪除失敗');
        }
    }
}
