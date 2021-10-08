<?php 
namespace Yjtec\PanoEdit\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 
 */
class Model3dClassify extends Model
{
	
	use SoftDeletes;
    protected $table = 'model3d_classify';
    protected $fillable = [
        'id','user_id','name'
    ];
}

?>