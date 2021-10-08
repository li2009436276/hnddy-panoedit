<?php


namespace Yjtec\PanoEdit\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventData extends Model
{

    use SoftDeletes;

    protected $table = 'event_data';
    protected $guarded = [];
}