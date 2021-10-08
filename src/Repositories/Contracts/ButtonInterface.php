<?php


namespace Yjtec\PanoEdit\Repositories\Contracts;


interface ButtonInterface
{
    /**
     * 添加
     * @param $data
     * @return mixed
     */
    public function add($data);

    /**
     * 列表
     * @param array $where
     * @param string $field
     * @return mixed
     */
    public function lists($where = [],$field = '*');

    /**
     * 编辑
     * @param $where
     * @param $data
     * @return mixed
     */
    public function edit($where,$data);

    /**
     * 删除
     * @param $where
     * @return mixed
     */
    public function delete($where);

    /**
     * 编辑或新增
     * @param $where
     * @param $data
     * @return mixed
     */
    public function updateOrCreate($where,$data);

    /**
     * 保存编辑
     * @param $foreignKeysId
     * @param $eventData
     * @param $type
     * @param $data
     * @return mixed
     */
    public function saveEditButton($foreignKeysId,$data,$eventData,$type = 1);

}