<?php


namespace Yjtec\PanoEdit\Repositories\Eloquent;


use Yjtec\PanoEdit\Model\HotspotPolygonsPoint;
use Yjtec\PanoEdit\Repositories\Contracts\HotspotPolygonsPointInterface;
use Yjtec\Repo\Repository;

class HotspotPolygonsPointRepository extends Repository implements HotspotPolygonsPointInterface
{

    public function model()
    {
        return HotspotPolygonsPoint::class;
    }

    /**
     * 点详情
     * @param array $where
     * @param string $field
     * @return mixed
     */
    public function info($where = [], $field = '*')
    {
        $res = $this->model
            ->where($where)
            ->select($field)
            ->first();
        return $res;
    }

    /**
     * 点列表
     * @param array $where
     * @param string $field
     * @return mixed
     */
    public function lists($where = [], $field = '*')
    {
        $res = $this->model
            ->where($where)
            ->select($field)
            ->get();
        return $res;
    }

    /**
     * 编辑点
     * @param $where
     * @param $data
     * @return mixed
     */
    public function edit($where, $data)
    {
        $res = $this->model
            ->where($where)
            ->update($data);
        return $res;
    }

    /**
     * 删除点
     * @param $where
     * @return mixed
     */
    public function delete($where)
    {
        $res = $this->model
            ->where($where)
            ->delete();
        return $res;
    }

    /**
     * 修改或创建
     * @param $hotspotId
     * @param $data
     * @return mixed
     */
    public function insert($hotspotId, $data)
    {
        //删除原先的点
        $delWhere['hotspot_id'] = $hotspotId;
        $res = $this->delete($delWhere);

        if ($res === false) {

            return false;
        }

        foreach ($data as $key=>&$value){

            $value['hotspot_id'] = $hotspotId;
            $value['created_at'] = date('Y-m-d H:i:s');
            $value['updated_at'] = date('Y-m-d H:i:s');
            unset($value['id']);
        }

        $res = $this->model->insert($data);
        if ($res) {

            return true;
        }

        return false;
    }
}