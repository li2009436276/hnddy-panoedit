<?php


namespace Yjtec\PanoEdit\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HotspotArticle extends Model
{
    use SoftDeletes;
    protected $table = 'hotspot_articles';
    protected $fillable = ['id','user_id','title','context'];

}