<?php


namespace Yjtec\PanoEdit\Repositories\Eloquent;


use Yjtec\PanoEdit\Model\File;
use Yjtec\PanoEdit\Model\SandTable;
use Yjtec\PanoEdit\Repositories\Contracts\SandTableInterface;
use Yjtec\Repo\Repository;

class SandTableRepository extends Repository implements SandTableInterface
{

    public function model()
    {
        return SandTable::class;
    }

    /**
     * 获取沙盘详情
     * @param $where
     * @param $field
     * @return mixed
     */
    public function sandTableInfo($where,$field = '*'){

        $res = $this->model
            ->where($where)
            ->groupBy('bg_img')
            ->groupBy('id')
            ->select($field)
            ->get();

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
     * @param $projectId
     * @param $data
     * @param $scenes
     * @return mixed
     */
    public function saveEditSandTable($projectId, $data, $scenes)
    {
        $index = 0;
        foreach ($data as $key=>$value) {

            if (!empty($value['scene_id']) && !empty($scenes[$value['scene_id']]) && !empty($value['scene_name'])) {

                ++$index;

                $radar['key']               = $index;
                $radar['scene_id']          = $scenes[$value['scene_id']];
                $radar['scene_name']        = $value['scene_name'];
                $radar['project_id']        = $projectId;
                $radar['heading']           = $value['heading'];
                $radar['align']             = $value['align'];
                $radar['x']                 = $value['x'];
                $radar['y']                 = $value['y'];
                $radar['scene_thumb']       = $value['scene_thumb'];
                $radar['bg_img']            = $value['bg_img'];

                if (config('pano_edit.project') == 'yj_pano') {

                    $radar['domain']            = strpos(' '.$value['scene_thumb'],'/image') ? '' : (!empty($value['domain']) ? $value['domain'] : '' );
                }

                //获取图片的宽高
                $fileInfo = File::where('path',$radar['bg_img'])->withTrashed()->first();
                if (!empty($fileInfo)) {

                    $radar['width']         = $fileInfo['width'];
                    $radar['height']        = $fileInfo['height'];
                }

                $where['project_id']        = $projectId;
                $where['key']               = $radar['key'] ;

                $res = $this->updateOrCreate($where,$radar);
                if ($res === false) {

                    return false;
                }
            }
        }

        //删除多余layer
        $count = $this->model->where(['project_id'=>$projectId])->count();

        if ($count > $index ) {

            //删除多余的layer
            $delWhere[]                     = ['key','>',$index];
            $delWhere['project_id']       = $projectId;
            $delRes = $this->delete($delWhere);
            if ($delRes === false) {

                return false;
            }
        }

        return true;
    }
}