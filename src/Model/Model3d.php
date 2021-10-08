<?php 

namespace Yjtec\PanoEdit\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Model3d extends Model
{
    use SoftDeletes;
    protected $table = 'model3d';
    protected $fillable = [
        'id','user_id','path','status','name','thumb','classify_id'
    ];


    //缩略图
    public function getPathAttribute($value){

    	return ['url' => \Storage::url(config('env.app_env').$value),'path'=>$value];
    }

    //缩略图
    public function getThumbAttribute($value){

    	return ['url' => \Storage::url(config('env.app_env').$value),'path'=>$value];
    }

    /**
     * 查询多边形点
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files(){

        return $this->hasMany(Model3dFile::class,'model3d_id','id');
    }
}

?>