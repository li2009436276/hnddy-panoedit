<?php


namespace Yjtec\PanoEdit\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Button extends Model
{
    use SoftDeletes;

    protected $table        = 'buttons';
    protected $fillable     = [
        'id','classify_id','foreign_keys_id','container','icon','text','css','type','key','front_key','status','event_id'
    ];

    /**
     * 按钮类型
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function classify(){        
        return $this->belongsTo(ButtonClassify::class,'front_key','name')->select(['id','name','container','icon','text']);
    }

    /**
     * 获取按钮事件
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function event_data(){
        return $this->hasOne(EventData::class,'id','event_id');
    }
}