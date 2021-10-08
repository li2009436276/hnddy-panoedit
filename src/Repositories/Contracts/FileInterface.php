<?php


namespace Yjtec\PanoEdit\Repositories\Contracts;


interface FileInterface
{
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
    public function create($data,$appId,$userId, $classifyId = 0,$type = 1,$category = 1,$applyType = 1);

    /**
     * 批量插入文件
     * @param $data
     * @param $appId
     * @param $userId
     * @param $classifyId
     * @param $type
     * @param $category
     * @param $applyType
     * @return mixed
     */
    public function insert($data,$appId,$userId,$classifyId = 0,$type = 1,$category = 1,$applyType = 1);



    public function index($where);

}
