<?php


namespace Yjtec\PanoEdit\Repositories\Eloquent;


use Yjtec\PanoEdit\Model\EmbedImg;
use Yjtec\PanoEdit\Repositories\Contracts\EmbedImgInterface;
use Yjtec\Repo\Repository;

class EmbedImgRepository extends Repository implements EmbedImgInterface
{

    public function model()
    {
        return EmbedImg::class;
    }

    /**
     * 批量添加图片
     * @param $data
     * @return mixed
     */
    public function insert($data)
    {
        $res = $this->model
            ->insert($data);
        return $res;
    }

    /**
     * 删除图片
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
     * 添加或编辑
     * @param $data
     * @param $embedId
     * @return mixed
     */
    public function saveEditEmbedImg($embedId,$data){
        
        foreach ($data as $key=>&$value){

            $where['key']       = $key + 1;
            $where['embed_id']  = $embedId;

            $item['embed_id']   = $embedId;
            $item['key']        = $key + 1;
            $item['url']        = $value['url'];
            $this->model->updateOrCreate($where,$item);
        }

        $count = $this->model->where((['embed_id'=>$embedId]))->count();

        if (count($data) < $count) {

            $delWhere['embed_id'] = $embedId;
            $delWhere[]           = ['key','>',count($data)];

            $delRes = $this->delete($delWhere);
            if ($delRes === false) {

                return false;
            }
        }

        return true;
    }
}