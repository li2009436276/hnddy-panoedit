<?php


namespace Yjtec\PanoEdit\Repositories\Eloquent;


use Yjtec\PanoEdit\Model\ProjectAction;
use Yjtec\PanoEdit\Model\ProjectActionClassify;
use Yjtec\PanoEdit\Repositories\Contracts\ProjectActionInterface;
use Yjtec\Repo\Repository;

class ProjectActionRepository extends Repository  implements ProjectActionInterface
{
    public function model()
    {
        return ProjectAction::class;
    }

    /**
     * 添加功能
     * @param $data
     * @return mixed
     */
    public function add($data)
    {
        $res = ProjectAction::create($data);
        return $res;
    }

    /**
     * 获取功能信息
     * @param array $where
     * @param string $field
     * @return mixed
     */
    public function info($where = [], $field = '*')
    {
        $res = ProjectAction::where($where)
            ->select($field)
            ->first();
        return $res;
    }

    /**
     * 编辑功能
     * @param $where
     * @param $data
     * @return mixed
     */
    public function edit($where, $data)
    {
        $res = ProjectAction::where($where)
            ->update($data);
        return $res;
    }

    /**
     * 功能列表
     * @param array $where
     * @param string $field
     * @return mixed
     */
    public function lists($where = [], $field = '*')
    {
        $res = ProjectAction::where($where)
            ->select($field)
            ->get();
        return $res;
    }

    /**
     * 删除功能
     * @param $where
     * @return mixed
     */
    public function delete($where)
    {
        $res = ProjectAction::where($where)
            ->delete();
        return $res;
    }

    /**
     * 获取条数
     * @param array $where
     * @return mixed
     */
    public function countNum($where = []) {

        $res = ProjectAction::where($where)->count();
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
        $res = ProjectAction::updateOrCreate($where,$data);
        return $res;
    }

    /**
     * 保存功能编辑
     * @param $projectId
     * @param $data
     * @return mixed
     */
    public function saveEditAction($projectId, $data)
    {

        if ($data) {

            //设置排序
            $this->sortAction($projectId);

            $actionArray = $this->getActionClassify();

            $index = 1;
            foreach ($data as $key=>$value) {

                $where['project_id']        = $projectId;
                $where['key']               = $index;

                $actionData['project_id']   = $projectId;
                $actionData['key']          = $index;
                $actionData['value']        = json_encode($value);
                $actionData['action_id']    = $actionArray[$key];

                $res = $this->updateOrCreate($where,$actionData);
                if ($res === false) {

                    return false;
                }
                $index++;
            }

            //删除多余的action
            $count = $this->countNum(['project_id'=>$projectId]);

            if ($count > count($data)) {
                $delWhere[]                     = ['key','>',count($data)];
                $delWhere['project_id']         = $projectId;
                $delRes = $this->delete($delWhere);
                if ($delRes === false) {

                    return false;
                }

            }

        }

        return true;
    }

    /**
     * 获取功能分类
     * @return mixed
     */
    private function getActionClassify(){

        $res = ProjectActionClassify::get();
        $actionArray = [];
        foreach ($res as $key=>$value){

            $actionArray[$value['name']] = $value['id'];
        }

        return $actionArray;
    }

    /**
     * 先对以前进行排序
     * @param $projectId
     * @return bool
     */
    private function sortAction($projectId){

        $where['project_id']       = $projectId;

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