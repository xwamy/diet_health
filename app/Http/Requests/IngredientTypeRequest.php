<?php

namespace App\Http\Requests;


class IngredientTypeRequest extends Request
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
            'sort'=>'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '请输入分组名称',
            'sort.required'=>'请输入排序',
        ];
    }
}
