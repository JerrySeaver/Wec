<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBlogPost extends FormRequest
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
        return[
            'email'=>'required|unique:users',
            'pwd'=>'required',
        ];
    }
    public function messages()
    {
        return [
            'email.required'=>'邮箱不可为空',
            'email.unique'=>'邮箱已存在',
            'pwd.required'=>'密码不可为空',
            'repwd.required'=>'请在此输入密码',
        ];
    }
}
