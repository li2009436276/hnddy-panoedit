<?php


namespace Yjtec\PanoEdit\Repositories\Eloquent;


use Yjtec\PanoEdit\Model\Hotspot;
use Yjtec\PanoEdit\Repositories\Contracts\HotspotInterface;
use Yjtec\PanoEdit\Services\Hotspot\HotspotService;
use Yjtec\Repo\Repository;

class HotspotRepository extends Repository implements HotspotInterface
{
    public function model()
    {
        return Hotspot::class;
    }

    /**
     * 删除热点
     * @param $where
     * @return mixed|void
     */
    public function delete($where)
    {
        $res = $this->model->where($where)->delete();
        return $res;
    }

    /**
     * 获取热点列表
     * @param array $where
     * @param string $field
     * @return mixed
     */
    public function lists($where = [], $field = '*')
    {
        $res = $this->model
            ->where($where)
            ->whereNotIn('type',[12,13])
            ->get();

        return $res;
    }

    /**
     * 查询遮照
     * @param array $where
     * @param string $field
     * @return mixed
     */
    public function maskLists($where = [], $field = '*')
    {
        $res = $this->model
            ->where($where)
            ->whereIn('type',[12,13])
            ->get();

        return $res;
    }

    /**
     * 编辑或创建热点
     * @param $data
     * @param $sceneId
     * @return mixed
     */
    public function updateOrCreate($data, $sceneId)
    {

        //排序热点
        $updateRes = $this->sortHotspot($sceneId);
        if (!$updateRes) {

            return false;
        }

        foreach ($data as $key=>$value) {

            $value['key']           = $key + 1;

            $where['scene_id']      = $sceneId;
            $where['key']           = $key + 1;
            //导致热点重复
//            $where['type']          = $value['action'];

            $res = $this->model->updateOrCreate($where,$value);
            if ($res === false) {

                return false;
            }
        }

        //删除多余hotspot
        $count = $this->model->where(['scene_id'=>$sceneId])->count();

        if ($count > count($data)) {
            $delWhere[]                 = ['key','>',count($data)];
            $delWhere['scene_id']       = $sceneId;
            $delWhere[]                 = ['type','!=', 12];
            $delWhere[]                 = ['type','!=', 13];
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


                $updateRes = $this->model->where('id',$value['id'])->update(['key'=>($key + 1)]);
                if ($updateRes === false) {

                    return false;
                }
            }
        }

        return true;
    }

    /**
     * 天空地面遮罩
     * @param $scenedId
     * @param $data
     * @return mixed
     */
    public function savEditMaskHospot($data,$scenedId)
    {
        //处理天空遮罩
        $delWhereIn = [12,13];
        foreach ($data as $key=>$hotspotMask) {
            if(!empty($hotspotMask) && is_array($hotspotMask)){
                $actionData = json_encode($hotspotMask);
                if ($key == 'sky') {
                    $type  = 12;
                    $hotspotMask = array_merge(config('config.mask_sky_url'),['url'=>$hotspotMask['url']]);
                    unset($delWhereIn[0]);
                }
                if($key == 'ground'){
                    $type  = 13;
                    $hotspotMask = array_merge(config('config.mask_ground_url'),['url'=>$hotspotMask['url']]);
                    unset($delWhereIn[1]);
                }
                $hotspotMask['key']       = 1;
                $hotspotMask['action_data'] = $actionData;
    
                $where['scene_id']  = $scenedId;
                $where['type']      = $type;
    
                $res = $this->model->updateOrCreate($where,$hotspotMask);
                if ($res === false) {
        
                    return false;
                }
            }
        }

        if (count($delWhereIn) > 0) {

            $res = $this->model
                ->where(['scene_id'=>$scenedId])
                ->whereIn('type',$delWhereIn)
                ->delete();
            if ($res === false) {

                return false;
            }
        }

        return true;
    }
}