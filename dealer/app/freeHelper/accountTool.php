<?php
namespace App\freeHelper;

use App\Permission;
use App\Role;

use App\User;
Class accountTool{
    

    // 取出所有帳號
    public static function getAllAccount(){

        return User::get();

    }
    
    // 取出指定帳號
    public static function getAccount( $_id ){

        return User::find( $_id );

    }
    // 確認帳號是否存在
    public static function accountExist( $_id ){

        return User::where('id',"$_id")->exists();
    }
    
}
?>