<?php


namespace Yjtec\PanoEdit\Repositories\Eloquent;


use Yjtec\PanoEdit\Model\Radar;
use Yjtec\PanoEdit\Repositories\Contracts\RadarInterface;
use Yjtec\Repo\Repository;

class RadarRepository extends Repository implements RadarInterface
{

    public function model()
    {
        return Radar::class;
    }

    /**
     * 功能平面导航删除
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
     * 编辑或添加平面导航
     * @param $where
     * @param $data
     * @return mixed
     */
    public function updateOrCreate($where, $data)
    {
        $res = $this->model->updateOrCreate($where,$data);
        return $res;
    }

    /**
     * 保存平面导航编辑数据
     * @param $containerId
     * @param $data
     * @param $scenes
     * @return mixed
     */
    public function saveEditRadar($containerId, $data,$scenes)
    {
        $hotspotIndex = 1;
        foreach ($data as $key=>$value) {

            if (!empty($scenes[$value['sceneId']])) {

                $radar['key']               = $hotspotIndex;
                $radar['scene_id']          = $scenes[$value['sceneId']];
                $radar['scene_name']        = $value['name'];
                $radar['container_id']      = $containerId;
                $radar['name']              = 'spot'.$radar['key'];
                $radar['angle']             = $value['angle'];
                $radar['align']             = $value['align'];
                $radar['x']                 = $value['x'];
                $radar['y']                 = $value['y'];
                $radar['url']               = $value['url'];

                $where['container_id']      = $containerId;
                $where['key']               = $radar['key'] ;

                $hotspotIndex++;
                $res = $this->updateOrCreate($where,$radar);
                if ($res === false) {

                    return false;
                }
            }
        }

        //删除多余layer
        $count = $this->model->where(['container_id'=>$containerId])->count();

        if ($count > $hotspotIndex - 1 ) {

            //删除多余的layer
            $delWhere[]                     = ['key','>',$hotspotIndex - 1];
            $delWhere['container_id']       = $containerId;
            $delRes = $this->delete($delWhere);
            if ($delRes === false) {

                return false;
            }
        }

        return true;
    }


}