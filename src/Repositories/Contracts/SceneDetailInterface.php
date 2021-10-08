<?php


namespace Yjtec\PanoEdit\Repositories\Contracts;


interface SceneDetailInterface
{
    /**
     * 更新或创建细节
     * @param $sceneId
     * @param $data
     * @return mixed
     */
    public function updateOrCreate($sceneId,$data);

    /**
     * 查询场景细节
     * @param array $where
     * @param string $field
     * @return mixed
     */
    public function lists($where = [],$field = '*');
    
}