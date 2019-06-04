<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNconePost extends FormRequest
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
            'name'=>'required|unique:ncone',
            'url'=>'required',
        ];
    }
    public function messages()
    {
        return [
            'name.required'=>'网站名称不可为空',
            'url.name'=>'网站名称已存在',
            'url.required'=>'网站地址不可为空',
        ];
    }
}
