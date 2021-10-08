<?php


namespace Yjtec\PanoEdit\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectSceneGroup extends Model
{
    use SoftDeletes;

    /**
     *  @OA\Schema(
     *      schema="YjtecProjectSceneGroup",
     *      @OA\Property(property="id",                    type="integer",     description="ID"),
     *      @OA\Property(property="project_id",            type="integer",     description="项目ID"),
     *      @OA\Property(property="name",                  type="string",      description="分组名称"),
     *
     *  )
     */
    protected $table = 'project_scene_group';

    protected $fillable = [
        'id','project_id','name','key'
    ];

    public function __construct(array $attributes = [])
    {

        if (config('pano_edit.project') == 'vryun') {

            $this->table = 'project2_scene_group';
        }

        parent::__construct($attributes);
    }
}