<?php


namespace Yjtec\PanoEdit\Repositories\Eloquent;


use Yjtec\PanoEdit\Model\SceneAction;
use Yjtec\PanoEdit\Repositories\Contracts\SceneActionInterface;
use Yjtec\Repo\Repository;

class SceneActionRepository extends Repository implements SceneActionInterface
{
    public function model()
    {
        return SceneAction::class;
    }

    /**
     * 功能action删除
     * @param $where
     * @return mixed
     */
    public function delete($where)
    {
        $res = $this->model->where($where)->delete();
        return $res;
    }

    /**
     * 获取功能列表
     * @param array $where
     * @param string $field
     * @return mixed
     */
    public function lists($where = [], $field = '*')
    {
        $res = $this->model
            ->where($where)
            ->whereIn('action_id',[1,4])
            ->select($field)
            ->get();
        return $res;
    }

    /**
     * 获取总条数
     * @param array $where
     * @return mixed
     */
    public function countNum($where = [])
    {
        $res = $this->model->where($where)->count();
        return $res;
    }

    /**
     * 删除不在数组内的记录
     * @param $where
     * @param $array
     * @return mixed
     */
    public function deleteNotInArray($where,$array)
    {
        $res = $this->model->where($where)->whereNotIn('key',$array)->delete();
        return $res;
    }

    /**
     * 编辑或添加action功能
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
     * 功能编辑
     * @param $sceneId
     * @param $actionId
     * @param $data
     * @return mixed|void
     */
    public function saveEditAction($sceneId, $actionId,$data)
    {

        $delIdNotInArray                = [];
        foreach ($data as $key=>$value) {

            $where['scene_id']          = $sceneId;
            $where['action_id']         = $actionId;
            $where['key']               = $key;

            //先获取信息value信息
            if ( !((!empty($value['musicUrl']) && strpos(' '.$value['musicUrl'],'/audio')) || (!empty($value['imageurl']) && strpos(' '.$value['imageurl'],'/image')))) {

                $info = $this->model->where($where)->first();
                $info['value'] = json_decode($info['value'],true);
                if (!empty($info['value']['domain'])) {

                    $value['domain']    = $info['value']['domain'];
                }

            }


            $actionData['scene_id']     = $sceneId;
            $actionData['action_id']    = $actionId;
            $actionData['key']          = $key;
            $actionData['value']        = json_encode($value);

            $delIdNotInArray[]          = $key;

            $res = $this->updateOrCreate($where,$actionData);
            if ($res === false) {

                return false;
            }
        }

        //删除多余的action
        $count = $this->countNum(['scene_id'=>$sceneId,'action_id'=>$actionId]);

        if ($count > count($data)) {
            $delWhere['scene_id']           = $sceneId;
            $delWhere['action_id']          = $actionId;
            $delRes = $this->deleteNotInArray($delWhere,$delIdNotInArray);
            if ($delRes === false) {

                return false;
            }

        }

        return true;
    }
}