<?php

namespace App\Http\Requests;


class NutritiveRequest extends Request
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
            'name'=>'required',
            'type'=>'required',
            'sort'=>'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '请输入营养名称',
            'type.required'=>'请选择营养类型',
            'sort.required'=>'请输入排序',
        ];
    }
}
