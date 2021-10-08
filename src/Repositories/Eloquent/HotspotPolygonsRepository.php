<?php


namespace Yjtec\PanoEdit\Repositories\Eloquent;


use Yjtec\PanoEdit\Model\HotspotPolygons;
use Yjtec\PanoEdit\Repositories\Contracts\HotspotPolygonsInterface;
use Yjtec\PanoEdit\Services\Hotspot\HotspotService;
use Yjtec\Repo\Repository;

class HotspotPolygonsRepository extends Repository implements HotspotPolygonsInterface
{

    public function model()
    {

        return HotspotPolygons::class;
    }

    /**
     * 添加多边形热点
     * @param $data
     * @return mixed
     */
    public function add($data)
    {
        $res = HotspotPolygons::create($data);
        return $res;
    }

    /**
     * 查询多边形热点信息
     * @param array $where
     * @param string $field
     * @return mixed
     */
    public function info($where = [], $field = '*')
    {
        $res = HotspotPolygons::where($where)
            ->with('point')
            ->select($field)
            ->first();
        return $res;
    }

    /**
     * 多边形热点列表
     * @param array $where
     * @param string $field
     * @return mixed
     */
    public function lists($where = [], $field = '*')
    {
        $res = HotspotPolygons::where($where)
            ->with('point')
            ->select($field)
            ->get();
        return $res;
    }

    /**
     * 多边形热点编辑
     * @param $where
     * @param $data
     * @return mixed
     */
    public function edit($where, $data)
    {
        $res = HotspotPolygons::where($where)
            ->update($data);
        return $res;
    }

    /**
     * 删除多边形热点
     * @param $where
     * @return mixed
     */
    public function delete($where)
    {
        $res = HotspotPolygons::where($where)->delete();

        return $res;
    }

    /**
     * 更新或删除
     * @param $data
     * @param $sceneId
     * @return bool|mixed
     */
    public function updateOrCreate($data,$sceneId)
    {

        //排序热点
        $updateRes = $this->sortHotspot($sceneId);
        if (!$updateRes) {

            return false;
        }

        foreach ($data as $key=>$value) {

            $value['attribute']['key'] = $key + 1;

            $where['key']       = $key + 1;
            $where['scene_id']  = $value['attribute']['scene_id'];
            $res = $this->model->updateOrCreate($where,$value['attribute']);

            if ($res === false) {

                return false;
            }

            //添加点
            $hospotPointRepository = new HotspotPolygonsPointRepository(app());
            $pointRes = $hospotPointRepository->insert($res->id,$value['point']);
            if (!$pointRes) {

                return false;
            }
        }

        $count = HotspotPolygons::where((['scene_id'=>$sceneId]))->count();

        if (count($data) < $count) {

            $delWhere['scene_id'] = $sceneId;
            $delWhere[]           = ['key','>',count($data)];

            $delRes = $this->delete($delWhere);
            if ($delRes === false) {

                return false;
            }
        }

        return true;
    }

    /**
     * 先对以前进行排序
     * @param $sceneId
     * @return bool
     */
    private function sortHotspot($sceneId){

        $where['scene_id']       = $sceneId;
        $where[]                 = ['type','!=', 12];
        $where[]                 = ['type','!=', 13];

        $res = $this->model->where($where)->orderBy('key','asc')->get();
        if ($res) {

            $hotspotArray = $res->toArray();
            foreach ($hotspotArray as $key=>$value) {

                $sort = $key + 1;

                $updateRes = $this->model->where('id',$value['id'])->update(['key'=>$sort]);
                if ($updateRes === false) {

                    return false;
                }
            }
        }

        return true;
    }

}