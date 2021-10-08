<?php


namespace Yjtec\PanoEdit\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HotspotPolygons extends Model
{
    use SoftDeletes;
    protected $table = 'hotspot_polygons';
    protected $fillable = [
        'id','scene_id','key','title','type','action','ath','atv','icon_type','img','action_data','show_title',
    ];

    /**
     * 查询多边形点
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function point(){

        return $this->hasMany(HotspotPolygonsPoint::class,'hotspot_id','id')->select(['id','hotspot_id','atv','ath']);
    }
}