<?php
/**
 * @OA\Schema(
 *      schema="npcRingsLists",
 *      required={
 *          "id", "title", "path", "thumb", "user", "status", "deleted_at", "created_at","ext"
 *      },
 *      @OA\Property(property="id",type="integer",format="int32",description="全景ID"),
 *      @OA\Property(property="name",type="string",description="全景名称"),
 *      @OA\Property(property="thumb",type="string",description="封面图"),
 *      @OA\Property(property="path",type="string",description="环物文件夹"),
 *      @OA\Property(property="ext",type="string",description="环物扩展名"),
 *      @OA\Property(property="num",type="integer",description="环物图片数"),
 *      @OA\Property(property="user",type="object",description="用户信息"),
 *      @OA\Property(property="status",type="integer",description="状态 4：生成成功，5：禁用"),
 *      @OA\Property(property="updated_at",type="string",description="禁用时间"),
 *      @OA\Property(property="created_at",type="string",description="创建时间"),
 *  )
 */

namespace Yjtec\PanoEdit\Resources\Rings;

use Yjtec\PanoEdit\Resources\BaseResource;

class RingsListsResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'path' => $this->path,
            'thumb' => $this->thumb,
            'status' => $this->status,
            'num' => $this->num,
            'ext' => $this->ext,
            'created_at' => date('Y-m-d H:i:s', strtotime($this->created_at)),
            'updated_at' => date('Y-m-d H:i:s', strtotime($this->updated_at)),
            'deleted_at' => $this->deleted_at,
            'user' => $this->user,
        ];
    }
}
