<?php 

namespace Yjtec\PanoEdit\Requests\Model3d;


use Illuminate\Foundation\Http\FormRequest;
/**
 * 
 */
class Model3dListsRequest extends FormRequest
{
	
	public function authorize(){

       return true;
    }

    public function rules(){

        return [
            'classify_id'   => 'nullable|integer|min:0',
            'page'		    => 'nullable|integer|min:1',
            'page_size'		=> 'nullable|integer|min:1'
        ];
    }

    public function messages()
    {
        return [
            'classify_id.integer'   => '分类ID必须为整数',
            'page.integer' 			=> '分页必须为整数',
            'page_size.integer'  	=> '每页条数必须为整数',

        ];
    }
}
?>