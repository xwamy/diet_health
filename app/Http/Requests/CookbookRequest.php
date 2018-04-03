<?php

namespace App\Http\Requests;


class CookbookRequest extends Request
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
            'timer'=>'required',
            'difficulty'=>'required',
            'practice'=>'required',
            'skill'=>'required',
            'food_type'=>'required',
            'thumb'=>'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '请输入烹饪方式',
            'sort.required'=>'请输入排序',
            'timer.required'=>'请输入烹饪时长',
            'difficulty.required'=>'请选择烹饪时长',
            'practice.required'=>'请输入菜谱做法',
            'skill.required'=>'请选择制作技巧',
            'food_type.required'=>'请输入食物类型',
            'thumb.required'=>'请上传缩略图',
        ];
    }
}
