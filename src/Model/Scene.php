<?php


namespace Yjtec\PanoEdit\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Scene extends Model
{
    use SoftDeletes;
    protected $table = 'scene';
    protected $fillable = [
        'id','project_id','panorama_id','group_id','name','filename','path','file_path','scene_image','scene_preview','thumb','service_pano_id','domain','key','status','size','width','height'
    ];

    public function __construct(array $attributes = [])
    {

        if (config('pano_edit.project') == 'vryun') {

            $this->table = 'scene2';
        }

        parent::__construct($attributes);
    }
}