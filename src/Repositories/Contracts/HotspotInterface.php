<?php


namespace Yjtec\PanoEdit\Repositories\Contracts;


interface HotspotInterface
{

    /**
     * 删除热点
     * @param $where
     * @return mixed
     */
    public function delete($where);

    /**
     * 热点列表
     * @param array $where
     * @param string $field
     * @return mixed
     */
    public function lists($where = [],$field = '*');

    /**
     * 查询遮照
     * @param array $where
     * @param string $field
     * @return mixed
     */
    public function maskLists($where = [], $field = '*');

    /**
     * 编辑或创建热点
     * @param $data
     * @param $sceneId
     * @return mixed
     */
    public function updateOrCreate($data,$sceneId);

    /**
     * 天空地面遮罩
     * @param $scenedId
     * @param $data
     * @return mixed
     */
    public function savEditMaskHospot($data,$scenedId);
}