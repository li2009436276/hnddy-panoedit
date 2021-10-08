<?php


namespace Yjtec\PanoEdit\Model;


use Illuminate\Database\Eloquent\Model;

class ProjectActionClassify extends Model
{
    protected $table = 'project_action_classify';
    protected $fillable = [
        'id','name','include_status'
    ];
}