<?php


namespace Yjtec\PanoEdit\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SceneAction extends Model
{
    use SoftDeletes;

    protected $table = 'scene_action';
    protected $fillable = [
        'id','scene_id','action_id','pid','sort','value','key'
    ];

    public function __construct(array $attributes = [])
    {
        if (config('pano_edit.project') == 'vryun') {

            $this->table = 'scene2_action2';
        }

        parent::__construct($attributes);
    }
}