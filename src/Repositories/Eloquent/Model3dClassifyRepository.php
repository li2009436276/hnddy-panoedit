<?php 

namespace Yjtec\PanoEdit\Repositories\Eloquent;

use Yjtec\PanoEdit\Model\Model3d;
use Yjtec\PanoEdit\Model\Model3dClassify;
use Yjtec\PanoEdit\Repositories\Contracts\Model3dClassifyInterface;
use Yjtec\Repo\Repository;
use DB;

class Model3dClassifyRepository extends Repository implements Model3dClassifyInterface
{

	public function model()
    {
        return Model3dClassify::class;
    }

    //删除
    public function delete($where){

    	DB::beginTransaction();
        $updateRes = Model3d::where(['classify_id'=>$where['id']])->update(['classify_id'=>0]);
        $res = $this->model->where($where)->delete();

        if ($updateRes !== false && $res) {

            DB::commit();
            return true;
        }

        DB::rollback();
        return false;
    }

    /**
     * 分类列表
     * @param $where
     * @param string $field
     * @return mixed
     */
    public function lists($where, $field = '*', $orderSort = ['created_at' => 'desc'])
    {
        $res = Model3dClassify::where($where)
            ->select($field);

        foreach($orderSort as $key => $value){
            $res->orderBy($key, $value);
        }
        $res = $res
            ->get()
            ->toArray();

        foreach ($res as $key=>&$value) {

            $value['count'] = Model3d::where(['classify_id'=>$value['id'],['status',1],'user_id'=>$where['user_id']])->count();
        }

        $default[0]['id'] = 0;
        $default[0]['name']        = '默认分类';
        $default[0]['count']  = Model3d::where(['classify_id'=>0,['status',1],'user_id'=>$where['user_id']])->count();

        $res = array_merge($default,$res);

        return $res;
    }
}

?>