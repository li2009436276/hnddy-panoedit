<?php


namespace Yjtec\PanoEdit\Repositories\Contracts;


interface ProjectInterface
{
    /**
     * 创建项目
     * @param $data
     * @return mixed
     */
    public function create($data);


    /**
     * 项目添加
     * @param $data
     * @param $panorama
     * @return mixed
     */
    public function add($data,$panorama);

    /**
     * 项目列表
     * @param $where
     * @param $pageSize
     * @param string $field
     * @param $orderFields
     * @return mixed
     */
    public function lists($where,$pageSize = 10, $field = '*',$orderFields = NULL);


    /**
     * 项目列表
     * @param $where
     * @param $pageSize
     * @param string $field
     * @param $orderFields
     * @return mixed
     */
    public function getLists($user_id, $field = '*',$orderFields = NULL);


    /**
     * 项目列表
     * @param $where
     * @param $pageSize
     * @param string $field
     * @return mixed
     */
    public function userProjectLists($where, $pageSize = 10, $field = '*');

    /**
     * 增加字段值
     * @param $where
     * @param $field
     * @param $num
     * @return mixed
     */
    public function increment($where,$field,$num);

    /**
     * 单独获取项目信息
     * @param $where
     * @param $field
     * @return mixed
     */
    public function getInfo($where,$field = '*');


    /**
     * 获取项目信息
     * @param $where
     * @param $field
     * @return mixed
     */
    public function getInfoMultiple($where,$field = '*');


    /**
     * 获取项目信息
     * @param $where
     * @param string $field
     * @return mixed
     */
    public function info($where,$field = '*');

    /**
     * 生成xml用
     * @param $where
     * @param string $field
     * @return mixed
     */
    public function getInfoForXml($where,$field = '*');

    /**
     * 项目编辑
     * @param $where
     * @param $data
     * @return mixed
     */
    public function edit($where,$data);

    /**
     * 移动分组
     * @param $ids
     * @param $groupId
     * @return mixed
     */
    public function moveGroup($ids, $groupId);

    /**
     * 项目删除
     * @param $where
     * @return mixed
     */
    public function delete($where);

    /**
     * 回收站
     * @param $where
     * @param int $pageSize
     * @param string $field
     * @return mixed
     */
    public function projectRecycle($where,$pageSize = 10,$field = '*');

    /**
     * whereIn查询
     * @param $searchField
     * @param $where
     * @param string $field
     * @return mixed
     */
    public function whereInCount($searchField,$where,$field = '*');

    /**
     * 获取project的统计数据
     * @return mixed
     */
    public function getStatisticsData();

    /**
     * 获取project的统计历史数据
     * @return mixed
     */
    public function getStatisticsHistoryData();

    /**
     * 返回项目信息，供应用模板
     * @param $where
     * @return mixed
     */
    public function projectForUseTemplate($where);

    /**
     * 删除xml
     * @param $id
     * @return mixed
     */
    public function deleteXml($id);
}