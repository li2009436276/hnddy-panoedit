<?php

namespace Yjtec\PanoEdit\Repositories\Eloquent;

use Yjtec\PanoEdit\Model\Model3d;
use Yjtec\PanoEdit\Repositories\Contracts\Model3dInterface;
use Yjtec\Repo\Repository;

/**
 *
 */
class Model3dRepository extends Repository implements Model3dInterface
{

    public function model()
    {
        return Model3d::class;
    }

    //3d模型列表
    public function lists($param, $orderSort = ['created_at' => 'desc'])
    {

        $where['user_id'] = $param['user_id'];
        $where['status'] = 1;


        if(!empty($param['classify_id'])){

            $where['classify_id'] = $param['classify_id'];
        }else{
            $where['classify_id'] = 0;
        }

        $param['page_size'] = !empty($param['page_size']) ? $param['page_size'] : 10;

        $model = $this->model
            ->where($where);
        foreach($orderSort as $key => $value){
            $model->orderBy($key, $value);
        }
        if(!empty($param['name'])){
            $model = $model->where('name', 'like', '%' . $param['name'] . '%');
        }

        $res = $model->paginate($param['page_size']);
        return $res;
    }

    //模型详情
    public function info($where)
    {

        $res = $this->model
            ->where($where)
            ->with('files')
            ->first();
        return $res;
    }

    //移动分组
    public function moveClassify($ids, $classifyId)
    {
        $res = $this->model->whereIn('id', $ids)->update(['classify_id' => $classifyId]);
        return $res;
    }

    //删除模型
    public function delete($where,$whereIn = [])
    {

        $model = $this->model->where($where);
        if (!empty($whereIn)) {

            $model = $model->whereIn('id',$whereIn);
        }
        $res = $model->delete();
        return $res;
    }
}

?>