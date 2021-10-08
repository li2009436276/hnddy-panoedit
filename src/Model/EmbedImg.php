<?php


namespace Yjtec\PanoEdit\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmbedImg extends Model
{
    use SoftDeletes;
    protected $table = 'embed_img';
    protected $fillable = ['id','embed_id','key','url'];
}