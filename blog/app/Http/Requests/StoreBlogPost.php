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
            'username'=>'required|unique:_user|max:255',
            'age'=>'required|integer',
        ];
    }
    public function messages()
    {
        return [
            'username.required'=>'用户名不可为空',
            'username.unique'=>'用户名已存在',
            'username.max'=>'用户名太长',
            'age.required'=>'年龄不可为空',
            'age.integer'=>'年龄格式错误',
        ];
    }
}
