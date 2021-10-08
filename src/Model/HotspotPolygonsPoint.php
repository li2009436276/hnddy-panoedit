<?php


namespace Yjtec\PanoEdit\Model;


use Illuminate\Database\Eloquent\Model;

class HotspotPolygonsPoint extends Model
{
    protected $table = 'hotspot_polygons_points';
    protected $fillable = ['id','hotspot_id','ath','atv'];
}