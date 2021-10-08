<?php


namespace Yjtec\PanoEdit\Services\ProjectAction;


use Yjtec\PanoEdit\Services\ProjectAction\ActionType\LoadSceneAction;
use Yjtec\PanoEdit\Services\ProjectAction\ActionType\OpenAnimation;
use Yjtec\PanoEdit\Services\ProjectAction\ActionType\PageCoverAction;
use Yjtec\PanoEdit\Services\ProjectAction\ActionType\PromptAction;

class ProjectActionParent
{
    private $projectId;
    private $data;
    private $projectAction;
    public function __construct($projectId,$data)
    {
        $this->projectId = $projectId;
        $this->data         = $data;
        $this->projectAction = resolve('Yjtec\PanoEdit\Repositories\Contracts\ProjectActionInterface');
    }

    /******************************************** 保存 *****************************/

    /**
     * 数据保存
     */
    public function savEditSceneAction(){

        if (!empty($this->data)) {

            $res = $this->projectAction->saveEditAction($this->projectId,$this->data);
            if ($res === false) {

                return false;
            }
        }

        return true;
    }

    /******************************************** 数据获取 *****************************/
    /**
     * 获取编辑数据
     * @return mixed
     */
    public function editData(){

        $res = new \stdClass();

        foreach ($this->data as $key=>$value){
            $name = $value['action_classify']['name'];
            $res->$name = json_decode($value['value'],true);
        }

        return $res;
    }

    /******************************************* 详情 ******************************/

    public function info(){

        $projectAction = [];
        if (!empty($this->data)) {

            foreach ($this->data as $key=>$value){

                switch ($value['action_id']) {

                    case 2 : {

                        $projectAction[$value['action_classify']['name']] = (new PromptAction($this->projectId,$value))->info();
                        break;
                    }

                     case 3: {

                         $projectAction[$value['action_classify']['name']] = (new PageCoverAction($this->projectId,$value))->info();
                         break;
                     }
                    case 4: {

                        $projectAction[$value['action_classify']['name']] = (new LoadSceneAction($this->projectId,$value))->info();
                        break;
                    }
                }
            }
        }

        return $projectAction;
    }

    /******************************************** 生成xml *****************************/
    public function makeXml($krpanoNode){

        if (!empty($this->data)) {

            foreach ($this->data as $key=>$value){

                switch ($value['action_id']) {

                    case 1: (new OpenAnimation($this->projectId,$value))->makeXml($krpanoNode);

                    /*case 2 : (new PromptAction($this->projectId,$value))->makeXml($krpanoNode);
                        break;

                   case 3: (new PageCoverAction($this->projectId,$value))->makeXml($krpanoNode);
                        break;*/

                }

            }
        }
    }
}