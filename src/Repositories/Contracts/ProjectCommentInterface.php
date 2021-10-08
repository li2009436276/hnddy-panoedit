<?php


namespace Yjtec\PanoEdit\Repositories\Contracts;


interface ProjectCommentInterface
{
    /**
     * 添加评论
     * @param $data
     * @return mixed
     */
    public function add($data);

    /**
     * 获取前一百个评论
     * @param array $where
     * @param int $pageSize
     * @param string $field
     * @return mixed
     */
    public function lists($where = [], $pageSize = 100, $field = '*');

    /**
     * 删除评论
     * @param $where
     * @return mixed
     */
    public function delete($where);

    public function getUserProjectCommentList($where, $pageSize = 100);
}