<?php


namespace Yjtec\PanoEdit\Requests\Model3d;

use Illuminate\Foundation\Http\FormRequest;

class ClassifyEditRequest extends FormRequest
{

    public function authorize(){

       return true;
    }

    public function rules(){
        return [
            'id'   => 'required|integer|min:1|exists:model3d_classify',
            'name' => 'required|string|max:50'
        ];
    }

    public function messages(){

        return [
            'id.required'   => '分类ID不能为空',
            'id.integer'    => '分类ID类型错误',
            'id.exists'     => '分类ID不存在',
            
            'name.required' => '分类名称不能为空',
        ];
    }
}