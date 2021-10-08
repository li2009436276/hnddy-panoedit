<?php


namespace Yjtec\PanoEdit\Repositories\Contracts;


interface RadarContainerInterface
{
    /**
     * 功能平面导航容器删除
     * @param $where
     * @return mixed
     */
    public function delete($where);

    /**
     * 编辑或添加平面导航容器
     * @param $where
     * @param $data
     * @return mixed
     */
    public function updateOrCreate($where,$data);

    /**
     * 保存平面导航容器编辑数据
     * @param $foreignKeysId
     * @param $scenes
     * @param $data
     * @param $type
     * @return mixed
     */
    public function saveEditRadarContainer($foreignKeysId,$scenes,$data,$type = 1);
}