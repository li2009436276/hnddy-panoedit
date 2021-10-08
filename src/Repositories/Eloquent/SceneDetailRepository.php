<?php


namespace Yjtec\PanoEdit\Repositories\Eloquent;


use Yjtec\PanoEdit\Model\SceneDetail;
use Yjtec\PanoEdit\Repositories\Contracts\SceneDetailInterface;
use Yjtec\Repo\Repository;

class SceneDetailRepository extends Repository implements SceneDetailInterface
{
    public function model()
    {
        return SceneDetail::class;
    }


    /**
     * 更新或创建细节
     * @param $sceneId
     * @param $data
     * @return mixed
     */
    public function updateOrCreate($sceneId,$data){

        $detailIndex = 1;
        foreach ($data as $key=>$value) {

            $item['key']                = $detailIndex;
            $item['scene_id']           = $sceneId;
            $item['url']                = $value['url'];
            $item['title']              = $value['title'];
            $item['fov']                = $value['fov'];
            $item['ath']                = $value['ath'];
            $item['atv']                = $value['atv'];

            $where['scene_id']          = $sceneId;
            $where['key']               = $detailIndex;

            $detailIndex++;
            $res = $this->model->updateOrCreate($where,$item);
            if ($res === false) {

                return false;
            }

        }

        //删除多余layer
        $count = $this->model
            ->where(['scene_id'=>$sceneId])
            ->count();

        if ($count > $detailIndex - 1 ) {

            //删除多余的layer
            $delWhere[]                 = ['key','>',$detailIndex - 1];
            $delWhere['scene_id']       = $sceneId;
            $delRes = $this->model
                ->where($delWhere)
                ->delete();
            if ($delRes === false) {

                return false;
            }
        }

        return true;
    }

    /**
     * 查询场景细节
     * @param array $where
     * @param string $field
     * @return mixed
     */
    public function lists($where = [],$field = '*'){

        $res = $this->model
            ->where($where)
            ->select($field)
            ->orderBy('key','asc')
            ->get();

        return $res;
    }
}