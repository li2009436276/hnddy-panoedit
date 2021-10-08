<?php


namespace App\Http\Requests\Project;


use Yjtec\PanoEdit\Requests\ProjectComment;

class CommentAddRequest extends BaseRequest
{
    public function rules(){
        return [
            'project_id'    => 'required|integer|min:1',
            'scene_id'      => 'required|integer|min:1',
            'ath'           => 'required|string',
            'atv'           => 'required|string',
            'title'         => 'required|string|max:20',
        ];
    }
}