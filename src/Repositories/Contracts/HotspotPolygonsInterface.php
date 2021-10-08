<?php


namespace Yjtec\PanoEdit\Repositories\Contracts;


interface HotspotPolygonsInterface
{

    /**
     * 添加多边形热点
     * @param $data
     * @return mixed
     */
    public function add($data);

    /**
     * 查询多边形热点信息
     * @param array $where
     * @param string $field
     * @return mixed
     */
    public function info($where = [], $field = '*');

    /**
     * 多边形热点列表
     * @param array $where
     * @param string $field
     * @return mixed
     */
    public function lists($where = [], $field = '*');

    /**
     * 多边形热点编辑
     * @param $where
     * @param $data
     * @return mixed
     */
    public function edit($where, $data);

    /**
     * 删除多边形热点
     * @param $where
     * @return mixed
     */
    public function delete($where);

    /**
     * 添加或编辑
     * @param $data
     * @param $sceneId
     * @return mixed
     */
    public function updateOrCreate($data,$sceneId);
}