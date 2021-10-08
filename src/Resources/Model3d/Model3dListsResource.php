<?php  


namespace Yjtec\PanoEdit\Resources\Model3d;


use Yjtec\PanoEdit\Resources\BaseResource;

/**
 * @OA\Schema(
 *      schema="Model3dLists",
 *      required={
 *          "id",  "path", "thumb", "user_id", "status", "deleted_at", "created_at","ext"
 *      },
 *      @OA\Property(property="id",type="integer",format="int32",description="3d模型ID"),
 *      @OA\Property(property="path",type="string",description="模型路径"),
 *      @OA\Property(property="user_id",type="integer",description="用户ID"),
 *      @OA\Property(property="status",type="integer",description="状态 0:不可用，1：可用"),
 *      @OA\Property(property="updated_at",type="string",description="禁用时间"),
 *      @OA\Property(property="created_at",type="string",description="创建时间"),
 *  )
 */
class Model3dListsResource extends BaseResource
{
	public function toArray($request)
    {
        return [
            'id' 			=> $this->id,
            'path' 			=> $this->path,
            'status' 		=> $this->status,
            'name'			=> $this->name,
            'thumb'			=> $this->thumb,
            'created_at' 	=> date('Y-m-d H:i:s', strtotime($this->created_at)),
            'updated_at' 	=> date('Y-m-d H:i:s', strtotime($this->updated_at)),
            'deleted_at' 	=> $this->deleted_at,
            'user_id' 		=> $this->user_id,
        ];
    }
}
?>