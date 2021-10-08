<?php


namespace Yjtec\PanoEdit\Model;


use Illuminate\Database\Eloquent\Model;

class ButtonClassify extends Model
{
    protected $table = 'button_classify';
    protected $fillable = [
        'id','name','default_value','description'
    ];
}