<?php


namespace Yjtec\PanoEdit\Repositories\Contracts;


interface SceneActionInterface
{

    /**
     * 功能action删除
     * @param $where
     * @return mixed
     */
    public function delete($where);

    /**
     * 获取功能action列表
     * @param array $where
     * @param string $field
     * @return mixed
     */
    public function lists($where = [],$field = '*');

    /**
     * 获取总条数
     * @param array $where
     * @return mixed
     */
    public function countNum($where = []);

    /**
     * 删除不在数组内的记录
     * @param $where
     * @param $array
     * @return mixed
     */
    public function deleteNotInArray($where,$array);

    /**
     * 编辑或添加action功能
     * @param $where
     * @param $data
     * @return mixed
     */
    public function updateOrCreate($where,$data);

    /**
     * 功能保存
     * @param $sceneId
     * @param $actionId
     * @param $data
     * @return mixed
     */
    public function saveEditAction($sceneId,$actionId,$data);
}