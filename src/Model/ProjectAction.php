<?php


namespace Yjtec\PanoEdit\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectAction extends Model
{
    use SoftDeletes;

    protected $table = 'project_action';
    protected $fillable = [
        'id','action_id','project_id','value','key'
    ];

    /**
     * 获取项目功能类
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function action_classify(){

        return $this->belongsTo(ProjectActionClassify::class,'action_id','id')->select(['id','name','include_status']);
    }
}