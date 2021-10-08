<?php


namespace Yjtec\PanoEdit\Repositories\Eloquent;


use Yjtec\PanoEdit\Model\Embed;
use Yjtec\PanoEdit\Repositories\Contracts\EmbedInterface;
use Yjtec\PanoEdit\Services\Embed\EmbedService;
use Yjtec\Repo\Repository;

class EmbedRepository extends Repository implements EmbedInterface
{

    public function model()
    {
        return Embed::class;
    }

    /**
     * @param $where
     * @param $field
     * @return mixed|void
     */
    public function lists($where = [],$field = '*')
    {
        $res = $this->model->where($where)
            ->with('img')
            ->select($field)
            ->get();
        return $res;
    }

    /**
     * 删除嵌入
     * @param $where
     * @return mixed
     */
    public function delete($where){

        $res = $this->model
            ->where($where)
            ->delete();
        return $res;
    }
    /**
     * 添加或编辑
     * @param $data
     * @param $sceneId
     * @return mixed
     */
    public function saveEditEmbed($sceneId,$data) {

        //处理数据格式
        $service = new EmbedService($data,$sceneId,[]);
        $service->dataFormat();
        $data = $service->getFormatData();

        foreach ($data as $key=>$value) {

            $where['key']       = $value['attribute']['key'];
            $where['scene_id']  = $value['attribute']['scene_id'];

            $res = $this->model->updateOrCreate($where, $value['attribute']);
            if ($res === false) {

                return false;
            }

            //添加图片
            if (!empty($value['img'])) {

                $embedImgRepository = new EmbedImgRepository(app());
                $imgRes = $embedImgRepository->saveEditEmbedImg($res->id, $value['img']);
                if (!$imgRes) {

                    return false;
                }
            }
        }

        $count = $this->model->where((['scene_id'=>$sceneId]))->count();

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


}