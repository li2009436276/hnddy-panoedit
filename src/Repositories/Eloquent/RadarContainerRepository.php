<?php


namespace Yjtec\PanoEdit\Repositories\Eloquent;


use Yjtec\PanoEdit\Model\RadarContainer;
use Yjtec\PanoEdit\Repositories\Contracts\RadarContainerInterface;
use Yjtec\Repo\Repository;

class RadarContainerRepository extends Repository implements RadarContainerInterface
{

    public function model()
    {
        return RadarContainer::class;
    }

    /**
     * 获取沙盘详情
     * @param array $where
     * @return mixed
     */
    public function radarInfo($where = []){

        $res = $this->model
            ->with('spots')
            ->where($where)
            ->first();

        return $res;
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
     * @param $foreignKeysId
     * @param $scenes
     * @param $data
     * @param $type
     * @return mixed
     */
    public function saveEditRadarContainer($foreignKeysId,$scenes, $data,$type = 1)
    {
        $radarRepository = new RadarRepository($this->app);

        if (!empty($data)) {

            $container['front_key']         = 'sandTable';
            $container['foreign_keys_id']   = $foreignKeysId;

            $data['container']              = !empty($data['container']) ? $data['container'] : array();
            $data['container']              = array_merge($data['container'],config('project_config.radar_container_default'));
            $container['container']         = json_encode($data['container']);

            $data['bg_img']                 = array_merge(['url'=>$data['bgImg']],config('project_config.radar_bg_img_default'));
            $container['bg_img']            = json_encode($data['bg_img']);

            $container['key']               = 1;

            $where['foreign_keys_id']       = $foreignKeysId;
            $where['type']                  = $type;
            $where['key']                   = $container['key'];

            $res = $this->updateOrCreate($where,$container);

            $radarRes = $radarRepository->saveEditRadar($res->id,!empty($data['spots']) ? $data['spots'] : array(),$scenes);

            if ($res && $radarRes !== false) {

                return true;
            }

        } else {

            $where['foreign_keys_id']       = $foreignKeysId;
            $where['type']                  = $type;
            $where['key']                   = 1;

            $res = $this->delete($where);

            $radarWhere['container_id']      = $foreignKeysId;
            $radarRes = $radarRepository->delete($radarWhere);

            if ($res && $radarRes !== false) {

                return true;
            }
        }
    }


}