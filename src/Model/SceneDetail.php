<?php


namespace Yjtec\PanoEdit\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SceneDetail extends Model
{
    use SoftDeletes;
    protected $table = 'scene_details';
    protected $fillable = ['id','scene_id','title','url','ath','atv','fov','key'];
    protected $appends = ['url_path'];

    public function getUrlPathAttribute(){

        return !empty($this->url) ? \Storage::url(config('env.app_env').$this->url) : $this->url;
    }


}