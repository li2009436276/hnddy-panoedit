<?php


namespace Yjtec\PanoEdit\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SandTable extends Model
{
	use SoftDeletes;
    protected $table = 'sand_table';
    protected $fillable = [
        'project_id','scene_id','bg_img','scene_name','heading','align','x','y','scene_thumb','key','width','height','domain'
    ];
}