<?php


namespace Yjtec\PanoEdit\Repositories\Eloquent;


use Yjtec\PanoEdit\Model\File;
use Yjtec\PanoEdit\Repositories\Contracts\FileInterface;
use Yjtec\Repo\Repository;

class FileRepository extends Repository implements FileInterface
{
    public function model()
    {
        return File::class;
    }

    /**
     * 添加文件
     * @param $data
     * @param $appId
     * @param $userId
     * @param $classifyId
     * @param $type
     * @param $category
     * @param $applyType
     * @return mixed
     */
    public function create($data,$appId,$userId,$classifyId = 0,$type = 1,$category = 1,$applyType = 1)
    {
        $data['user_id']        = $userId;
        $data['app_id']         = $appId;
        $data['classify_id']    = $classifyId;
        $data['type']           = $type;
        $data['category']       = $category;
        $value['apply_type']    = $applyType;
        $res = $this->model->create($data);
        return $res;
    }

    /**
     * 批量插入文件
     * @param $data
     * @param $appId
     * @param $userId
     * @param $classifyId
     * @param $type
     * @param $dcategory
     * @param int $applyType
     * @return mixed
     */
    public function insert($data,$appId,$userId,$classifyId = 0,$type = 1,$category = 1,$applyType = 1)
    {

        foreach ($data as $key=>&$value) {
            $value['app_id']      = $appId;
            $value['user_id']     = $userId;
            $value['classify_id'] = $classifyId;
            $value['type']        = $type;
            $value['category']    = $category;
            $value['apply_type']  = $applyType;
            $value['created_at']  = date('Y-m-d H:i:s');
            $value['updated_at']  = date('Y-m-d H:i:s');
        }

        $res = $this->model->insert($data);
        return $res;
    }

    public function index($where)
    {
        return $this->model->where($where)->first();
    }
}
