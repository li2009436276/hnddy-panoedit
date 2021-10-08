<?php

namespace Yjtec\PanoEdit\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Model3dFile extends Model
{
    protected $table = 'model3d_files';
    protected $fillable = [
        'id','model3d_id','name','ext',
    ];

    /**
     * 查询多边形点
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files(){

        return $this->hasMany(Model3dFile::class,'hotspot_id','id');
    }
}

?>