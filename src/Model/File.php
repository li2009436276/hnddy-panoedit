<?php


namespace Yjtec\PanoEdit\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use SoftDeletes;

    /**
     *  @OA\Schema(
     *      schema="PanoEditFile",
     *      @OA\Property(property="nickname",              type="string",      description="用户昵称"),
     *      @OA\Property(property="id",                    type="integer",     description="ID"),
     *      @OA\Property(property="user_id",               type="integer",     description="用户id"),
     *      @OA\Property(property="path",                  type="string",      description="文件路径"),
     *      @OA\Property(property="name",                  type="string",      description="文件名称"),
     *      @OA\Property(property="ext",                   type="string",      description="文件后缀"),
     *      @OA\Property(property="size",                  type="integer",     description="文件大小"),
     *      @OA\Property(property="width",                 type="integer",     description="文件：宽"),
     *      @OA\Property(property="height",                type="string",      description="文件：高"),
     *      @OA\Property(property="classify_id",           type="integer",     description="分类ID"),
     *
     *  )
     *
     * @OA\Schema(
     *      schema="PanoEditFileLists",
     *      allOf= {
     *          @OA\Schema(
     *              @OA\Property(property="data",type="array",@OA\Items(ref="#/components/schemas/PanoEditFile"))
     *          ),
     *          @OA\Schema(ref="#/components/schemas/Page"),
     *      }
     *  )
     */

    protected $table = 'files';
    protected $fillable = [
        'id','app_id','user_id','path','type','name','ext','classify_id','height','width','size','sort','category','apply_type'
    ];
    /*protected $hidden = [
        'user_id'
    ];*/

    /**
     * 文件全路径
     * @param $value
     * @return array
     */
    public function getPathAttribute($value){
        return array( 'url' => \Storage::url(env('APP_ENV').$value),'path' => $value);
    }
}