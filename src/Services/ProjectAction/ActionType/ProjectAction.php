<?php


namespace Yjtec\PanoEdit\Services\ProjectAction\ActionType;


use Yjtec\PanoEdit\Services\XmlService;

class ProjectAction
{
    protected $projectId;
    protected $data;
    static $xmlService      = NULL;     //生成xml所需
    public function __construct($projectId,$data)
    {
        $this->projectId = $projectId;
        $this->data = $data;
    }

    /******************************************* 详情 ******************************/

    /**
     * 详情
     * @return mixed
     */
    public function info(){

        return $this->elementInfo();
    }

    /**
     * 详情转数组
     * @return array|mixed
     */
    public function elementInfo(){

        if (!empty($this->data) && !empty($this->data['value'])) {

            return json_decode($this->data['value'],true);
        }

        return [];
    }

    /**************************************** 生成xml *****************************/

    /**
     * 创建Xml
     */
    public function makeXml($krpanoNode){

        if (!self::$xmlService) {

            self::$xmlService = new XmlService();
        }

        //生成节点
        $this->createElement($krpanoNode);
    }

    /**
     * 生成节点
     */
    public function createElement($krpanoNode){

    }
}