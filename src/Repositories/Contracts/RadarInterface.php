<?php


namespace Yjtec\PanoEdit\Repositories\Contracts;


interface RadarInterface
{
    /**
     * 功能平面导航删除
     * @param $where
     * @return mixed
     */
    public function delete($where);

    /**
     * 编辑或添加平面导航
     * @param $where
     * @param $data
     * @return mixed
     */
    public function updateOrCreate($where,$data);

    /**
     * 保存平面导航编辑数据
     * @param $containerId
     * @param $data
     * @param $scenes
     * @return mixed
     */
    public function saveEditRadar($containerId,$data,$scenes);
}