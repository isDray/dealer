<?php
namespace App\freeHelper;

use App\Permission;
use App\Role;

Class rolePermissionTool{
    
    // 身份分組結構
    public static $RoleCategory  =  [
                                       'Admin' => [ ['PermissionsManager'],
                                                    ['WebSetManager'],
                                                    ['GoodsManager' , 'GoodsViewer'],
                                                    ['OrderManager'],
                                                    ['PurchaseManager']
                                                  ],

                                       'Dealer'=> []

                                    ];

    /*public static $RoleCategory['Dealer'] = [

                                     ];*/
    /* 權限區塊
     *----------------------------------------------------------------
     */
    
    // 取出所有權限
    public static function getAllPermissions(){

    	return Permission::get();

    }
    // 確認權限存在
    public static function permissionsExist( $_id ){
        
        return Permission::where('id',"$_id")->exists();
    }
    


    /* 角色區塊
     *----------------------------------------------------------------
     */


    // 取出所有角色
    public static function getAllRoles(){

    	return Role::get();
    }

    // 取出指定身分
    public static function getRole( $_id ){

    	return Role::find( $_id );

    }

    // 確認角色是否存在
    public static function roleExist( $_id ){

    	return Role::where('id',"$_id")->exists();
    }
    

    // 取出兩個根身分
    public static function getRootCategory(){

    	return Role::where('name',"Admin")
                   ->orWhere('name','Dealer')
    	           ->get();
    }

    // 取出除了根身分外的所有身分
    public static function getUnRootCategory(){

        return Role::where('name','!=',"Admin")
                   ->where('name','!=','Dealer')
                   ->get();        
    }

}
?>