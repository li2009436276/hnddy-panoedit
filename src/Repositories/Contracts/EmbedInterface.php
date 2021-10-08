<?php


namespace Yjtec\PanoEdit\Repositories\Contracts;


interface EmbedInterface
{

    /**
     * 获取嵌入
     * @param $where
     * @param $field
     * @return mixed|void
     */
    public function lists($where = [],$field = '*');

    /**
     * 删除嵌入
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
    public function saveEditEmbed($sceneId,$data);
}