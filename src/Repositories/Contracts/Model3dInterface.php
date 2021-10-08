<?php

namespace Yjtec\PanoEdit\Repositories\Contracts;

interface Model3dInterface
{
    //3d模型列表
    public function lists($parama, $orderSort = ['created_at' => 'desc']);

    //模型详情
    public function info($where);

    //移动分组
    public function moveClassify($ids, $classifyId);

    //删除模型
    public function delete($where,$whereIn = []);
}

?>