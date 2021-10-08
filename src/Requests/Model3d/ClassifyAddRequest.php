<?php
/**
 * Created by PhpStorm.
 * User: lmm
 * Date: 2019/4/30
 * Time: 10:01
 */

namespace Yjtec\PanoEdit\Requests\Model3d;

use Illuminate\Foundation\Http\FormRequest;


class ClassifyAddRequest extends FormRequest
{
	public function authorize(){

       return true;
    }

    public function rules(){

        return [
            'name'   => 'required|string',
        ];
    }

    public function messages(){

        return [
            'name.required' => '分类名称不能为空',
        ];
    }
}