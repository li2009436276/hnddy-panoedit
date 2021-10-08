<?php

namespace Yjtec\PanoEdit\Requests\Rings;


use Illuminate\Foundation\Http\FormRequest;

class RingsDisableRequest extends FormRequest
{

    public function authorize(){

        return true;
    }

    public function rules(){
        return [
            'rings_status' => 'nullable|integer|min:1|in:1,2',
        ];
    }
}