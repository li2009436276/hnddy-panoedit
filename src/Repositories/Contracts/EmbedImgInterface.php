<?php


namespace Yjtec\PanoEdit\Repositories\Contracts;


interface EmbedImgInterface
{

    /**
     * 批量添加图片
     * @param $data
     * @return mixed
     */
    public function insert($data);

    /**
     * 删除图片
     * @param $where
     * @return mixed
     */
    public function delete($where);

    /**
     * 添加或编辑
     * @param $data
     * @param $embedId
     * @return mixed
     */
    public function saveEditEmbedImg($embedId,$data);
}