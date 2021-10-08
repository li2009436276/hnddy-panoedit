<?php


namespace Yjtec\PanoEdit\Repositories\Eloquent;


use Yjtec\PanoEdit\Model\ProjectSceneGroup;
use Yjtec\PanoEdit\Repositories\Contracts\SceneGroupInterface;
use Yjtec\Repo\Repository;

class SceneGroupRepository extends Repository implements SceneGroupInterface
{
    public function model()
    {
        return ProjectSceneGroup::class;
    }

    /**
     * 批量插入数据
     * @param $data
     * @return mixed
     */
    public function insert($data)
    {
        return $this->model->insert($data);
    }
}