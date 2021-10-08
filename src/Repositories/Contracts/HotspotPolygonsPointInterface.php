<?php


namespace Yjtec\PanoEdit\Repositories\Contracts;


interface HotspotPolygonsPointInterface
{
    /**
     * 添加点
     * @param $data
     * @return mixed
     */
    public function add($data);

    /**
     * 点详情
     * @param array $where
     * @param string $field
     * @return mixed
     */
    public function info($where = [], $field = '*');

    /**
     * 点列表
     * @param array $where
     * @param string $field
     * @return mixed
     */
    public function lists($where = [],$field = '*');

    /**
     * 编辑点
     * @param $where
     * @param $data
     * @return mixed
     */
    public function edit($where,$data);

    /**
     * 删除点
     * @param $where
     * @return mixed
     */
    public function delete($where);

    /**
     * 修改或创建
     * @param $hotspotId
     * @param $data
     * @return mixed
     */
    public function insert($hotspotId,$data);
}