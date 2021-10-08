<?php


namespace Yjtec\PanoEdit\Repositories\Contracts;


interface ProjectActionInterface
{
    /**
     * 添加功能
     * @param $data
     * @return mixed
     */
    public function add($data);

    /**
     * 获取功能信息
     * @param array $where
     * @param string $field
     * @return mixed
     */
    public function info($where = [],$field = '*');

    /**
     * 编辑功能
     * @param $where
     * @param $data
     * @return mixed
     */
    public function edit($where,$data);

    /**
     * 功能列表
     * @param array $where
     * @param string $field
     * @return mixed
     */
    public function lists($where = [],$field = '*');

    /**
     * 删除功能
     * @param $where
     * @return mixed
     */
    public function delete($where);

    /**
     * 获取条数
     * @param array $where
     * @return mixed
     */
    public function countNum($where = []);

    /**
     * 编辑或添加action功能
     * @param $where
     * @param $data
     * @return mixed
     */
    public function updateOrCreate($where,$data);

    /**
     * 保存功能编辑
     * @param $projectId
     * @param $data
     * @return mixed
     */
    public function saveEditAction($projectId,$data);
}