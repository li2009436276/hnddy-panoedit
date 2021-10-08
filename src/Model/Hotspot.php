<?php


namespace Yjtec\PanoEdit\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hotspot extends Model
{
    use SoftDeletes;
    protected $table = 'hotspot';
    protected $fillable = [
        'id','scene_id','key','url','title','type','action','ath','atv','icon_type','img','action_data','show_title','font_size','scale'
    ];
}