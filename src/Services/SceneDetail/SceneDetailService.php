<?php


namespace Yjtec\PanoEdit\Services\SceneDetail;


class SceneDetailService
{

    private $sceneId;
    private $data;
    public function __construct($data = [],$sceneId = 0)
    {
        $this->sceneId = $sceneId;
        $this->data = $data;
    }

    /**
     * 返回生成xml项目
     * @return array|array[]
     */
    public function makeXml($projectId,$sceneIndex){

        //获取场景ID
        $sceneId = $this->getSceneId($projectId,$sceneIndex);

        $details = [];
        if (empty($this->data)) {

            $sceneDetailInterface = resolve('Yjtec\PanoEdit\Repositories\Contracts\SceneDetailInterface');
            $res = $sceneDetailInterface->lists(['scene_id'=>$sceneId],['id','title','url','fov','ath','atv']);
            if ($res) {

                $details = $res->toArray();
            }
        } else {

            $details =  !empty($this->data['details']) ? $this->data['details'] : [];
        }

        return ['details'=>$details];
    }

    /**
     * 因为是异步，sceneId可能是个字符串
     * @param $project
     * @param $sceneIndex
     */
    private function getSceneId($project,$sceneIndex){

        if (empty($this->data)) {

            $sceneInterface = resolve('Yjtec\PanoEdit\Repositories\Contracts\SceneInterface');
            $sceneInfo = $sceneInterface->index(['project_id'=>$project,'key'=>$sceneIndex]);
            if (!empty($sceneInfo)){

                return $sceneInfo['id'];
            }
        }

        return $this->sceneId;
    }
}