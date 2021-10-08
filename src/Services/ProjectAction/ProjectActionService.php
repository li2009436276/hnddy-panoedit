<?php


namespace Yjtec\PanoEdit\Services\ProjectAction;


use Yjtec\PanoEdit\Repositories\Contracts\ProjectActionInterface;
use Yjtec\PanoEdit\Services\XmlService;

class ProjectActionService
{
    private $projectActionParent;

    public function __construct($projectId,$data = [])
    {
        $this->projectActionParent = new ProjectActionParent($projectId,$data);
    }

    /******************************************** 保存 *****************************/

    /**
     * 保存数据
     */
    public function saveEditProjectAction(){

        return $this->projectActionParent->savEditSceneAction();
    }


    /******************************************** 数据获取 *****************************/
    /**
     * 格式化数据
     */
    public function editData(){

        return $this->projectActionParent->editData();
    }

    /******************************************* 详情 ******************************/

    public function info(){

        return $this->projectActionParent->info();
    }

    /******************************************** 生成xml *****************************/
    /**
     * 获取xml
     * @param $dom DOMDocument 对象
     * @param $krpanoNode
     */
    public function makeXml($dom,$krpanoNode){

        //先启用$dom
        $xmlService = new XmlService($dom);

        $this->projectActionParent->makeXml($krpanoNode);
    }
}