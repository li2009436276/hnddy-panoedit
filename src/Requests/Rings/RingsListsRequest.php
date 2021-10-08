<?php

namespace Yjtec\PanoEdit\Requests\Rings;


use Illuminate\Foundation\Http\FormRequest;

class RingsListsRequest extends FormRequest
{

    public function authorize(){

        return true;
    }

    public function rules(){
        return [
            'page' => 'required|integer|min:1',
            'page_size' => 'nullable|integer|min:1|max:100',
            'search_field' => 'nullable|string|in:title,phone,user_id',
            'search_value' => 'nullable|string',
            'sort_field' => 'nullable|string|min:1|in:id,status',
            'sort_rule' => 'nullable|string|min:1|in:asc,desc',
            'rings_status' => 'nullable|integer|min:1|in:1,2',
        ];
    }
}