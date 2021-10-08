<?php


namespace Yjtec\PanoEdit\Requests\Model3d;

use Illuminate\Foundation\Http\FormRequest;

class ClassifyMoveRequest extends FormRequest
{

    public function authorize()
    {

        return true;
    }

    public function rules()
    {
        return [
            'classify_id' => 'required|integer',
            'ids' => 'required|array'
        ];
    }

    public function messages()
    {
        return [
            'classify_id.required' => '分类ID不能为空',
            'classify_id.integer' => '分类ID必须为整数',
            'classify_id.exists' => '分类ID不存在',

            'ids.required' => '模型ID不能为空',
            'ids.array' => '模型ID必须是数组'

        ];
    }
}