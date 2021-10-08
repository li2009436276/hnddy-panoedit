<?php


namespace Yjtec\PanoEdit\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Embed extends Model
{
    use SoftDeletes;
    protected $table = 'embed';
    protected $fillable = ['id','scene_id','key','type','ath','atv','rx','ry','rz','scale','action_data','edit_type'];

    /**
     * 嵌入type=4的图片
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function img(){
        return $this->hasMany(EmbedImg::class,'embed_id','id')->select(['id','embed_id','url']);
    }
}