<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class vRoleNew extends FormRequest
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
    public function rules()
    {
        return [
            
            'code' => 'required|regex:/^\w+$/',
            'name' => 'required',

        ];
    }

    public function messages(){

        return [

            'code.required' => '權限代碼為必填',
            'code.regex'    =>'權限代碼只可使用英文、數字、底線',
            'name.required' => '權限名稱為必填',
            
        ];

    }    
}
