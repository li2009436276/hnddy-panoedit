<?php


namespace Yjtec\PanoEdit\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RadarContainer extends Model
{
    use SoftDeletes;

    protected $table = 'radar_container';
    protected $fillable = [
        'id','front_key','foreign_keys_id','container','bg_img','type','key','domain'
    ];

    /**
     * 获取平面导航
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function spots(){
        return $this->hasMany(Radar::class,'container_id','id')->select(['id','container_id','scene_id','name','angle','align','x','y','url','key','scene_name']);
    }
}