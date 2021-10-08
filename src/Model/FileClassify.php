<?php


namespace Yjtec\PanoEdit\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileClassify extends Model
{
    use SoftDeletes;

    /**
     *  @OA\Schema(
     *      schema="FileClassify",
     *      @OA\Property(property="id",                    type="integer",     description="ID"),
     *      @OA\Property(property="user_id",               type="integer",     description="用户id"),
     *      @OA\Property(property="name",                  type="string",      description="分类名称"),
     *      @OA\Property(property="audio_count",           type="integer",     description="该分类下的音频数"),
     *  )
     *
     * @OA\Schema(
     *      schema="FileClassifyLists",
     *      allOf= {
     *          @OA\Schema(
     *              @OA\Property(property="data",type="array",@OA\Items(ref="#/components/schemas/FileClassify"))
     *          )
     *      }
     *  )
     */

    protected $table = 'file_classify';
    protected $fillable = [
        'id','app_id','user_id','name','type'
    ];
    protected $hidden = [
        'user_id'
    ];
}