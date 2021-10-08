<?php


namespace Yjtec\PanoEdit\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectComment extends Model
{
    use SoftDeletes;
    protected $table = 'project_comment';
    protected $fillable = [
        'id','user_id','project_id','scene_id','ath','atv','title','nickname','url'
    ];


    public function getUrlAttribute($value){
        return \Storage::url(env('APP_ENV').$value);
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id')->select('id', 'user_id', 'name');
    }
}