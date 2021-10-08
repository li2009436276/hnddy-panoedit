<?php


namespace  Yjtec\PanoEdit\Repositories\Contracts;


interface RingsInterface
{

    /**
     * 环物图列表
     * @param $where
     * @param $pageSize
     * @param string $field
     * @param array $sortField
     * @param array $search
     * @return mixed
     */
    public function lists($where, $pageSize, $field = '*', $sortField = [], $search = []);

    /**
     * 修改环物图
     * @param $where
     * @param $saveData
     * @return mixed
     */
    public function edit($where, $saveData);

    /**
     * 创建环物
     * @param $data
     * @return mixed
     */
    public function create($data);

}
