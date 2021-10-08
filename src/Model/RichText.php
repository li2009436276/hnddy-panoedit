<?php


namespace Yjtec\PanoEdit\Model;


use Illuminate\Database\Eloquent\Model;
use Yjtec\Emoji\Emoji;

class RichText extends Model
{
    protected $table = 'rich_text';
    protected $fillable = ['id','uuid','content'];

    /**
     * 处理表情
     */
    /*public function setContentAttribute($value){
        return $this->attributes['content'] = Emoji::encode($value);
    }*/

    public function getContentAttribute(){
        return Emoji::decode($this->attributes['content']);
    }
}