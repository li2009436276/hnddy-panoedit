<?php


namespace Yjtec\PanoEdit\Repositories\Contracts;


interface SceneGroupInterface
{
    /**
     * 批量插入数据
     * @param $data
     * @return mixed
     */
    public function insert($data);
}