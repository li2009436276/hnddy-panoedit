<?php


namespace Yjtec\PanoEdit\Requests\Model3d;


use Illuminate\Foundation\Http\FormRequest;

class Model3dUpdateRequest extends FormRequest
{
    public function authorize(){

        return true;
    }

    public function rules(){

        return [
            'id'    => 'required|integer|exists:model3d',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => '模型ID不能为空',
            'id.integer' => '模型ID必须为整数',
            'id.exists' => '模型ID不存在',
        ];
    }
}