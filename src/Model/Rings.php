<?php


namespace Yjtec\PanoEdit\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rings extends Model
{
    use SoftDeletes;

    /**
     *  @OA\Schema(
     *      schema="Rings",
     *      @OA\Property(property="id",                    type="integer",     description="ID"),
     *      @OA\Property(property="user_id",               type="integer",     description="用户id"),
     *      @OA\Property(property="title",                 type="string",      description="环物名称"),
     *      @OA\Property(property="path",                  type="string",      description="文件夹"),
     *      @OA\Property(property="thumb",                 type="string",      description="封面"),
     *      @OA\Property(property="num",                   type="integer",     description="num"),
     *      @OA\Property(property="status",                type="integer",     description="状态 正常:1,禁用:2"),
     *      @OA\Property(property="created_at",             type="string",     description="创建时间"),
     *      @OA\Property(property="deleted_at",             type="string",     description="删除时间"),
     *
     *  )
     *
     * @OA\Schema(
     *      schema="RingsLists",
     *      allOf= {
     *          @OA\Schema(
     *              @OA\Property(property="data",type="array",@OA\Items(ref="#/components/schemas/Rings"))
     *          ),
     *          @OA\Schema(ref="#/components/schemas/Page"),
     *      }
     *  )
     */

    protected $table = 'rings';
    protected $fillable = [
        'id','user_id','title','path','thumb','num','status','ext'
    ];

    /**
     * 文件全路径
     * @param $value
     * @return array
     */
    public function getThumbAttribute($value){
        return ['url' => \Storage::url(env('APP_ENV').$value), 'path' => $value];
    }
}