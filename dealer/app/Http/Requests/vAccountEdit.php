<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class vAccountEdit extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules( )
    {   
        return [
            'name'      => "required|unique:users,name,{$this->id}",
            'email'     => "required|unique:users,email,{$this->id}",
            'password'  => 'same:password2',
            'useTarget' => 'required|in:Admin,Dealer',
            'phone'     => 'required|regex:/^09[0-9]{8}$/',
            'address'   => 'required'
        ];
    }

    public function messages(){

        return [

            'name.required'     => '姓名為必填',
            'name.unique'       => '姓名已有人使用過',
            'email.required'    => '信箱為必填',
            'email.unique'      => '信箱已有人使用過',
            'password.same'     => '密碼輸入不一致',
            'useTarget.required'=> '管理平台為必填',
            'useTarget.in'      => '管理平台無效',
            'phone.required'    => '手機號碼為必填',
            'phone.regex'       => '手機格式有誤',
            'address.required'  => '地址為必填'

        ];

    } 

    public function phoneRule(){

    }
}
