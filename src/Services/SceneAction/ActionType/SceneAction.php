<?php


namespace Yjtec\PanoEdit\Services\SceneAction\ActionType;


use Yjtec\PanoEdit\Services\XmlService;

class SceneAction
{
    protected $data         = [];       //传过来的数据
    protected $returnData   = [];       //返回的数据

    private $sceneId        = 0;        //当前场景ID
    private $sceneArray     = [];       //项目所有场景

    static $xmlService      = NULL;     //生成xml所需

    public function __construct($data,$sceneId,$sceneArray)
    {
        $this->data         = $data;
        $this->sceneId      = $sceneId;
        $this->sceneArray   = $sceneArray;
    }

    /**
     * 保存数据
     */
    public function savEditSceneAction(){

        return true;
    }

    /**
     * 初始化已有数据
     */
    public function format(){

        $this->returnData['value']               = $this->valueFormat();

        $this->returnData['scene_id']            = $this->sceneId;
        $this->returnData['action_id']           = $this->actionId;
        $this->returnData['key']                 = $this->key;
        return $this;

    }

    /**
     * 格式化value
     * @return array
     */
    public function valueFormat(){

        return $this->data;
    }

    /**************************************** 处理返回编辑数据 ***************************************/
    public function editDataFormat(){

        $this->returnData[$this->data['key']]            = json_decode($this->data['value'],true);
        return $this;
    }

    /**************************************** 处理返回编辑数据结束 ***************************************/


    /**************************************** 生成xml ****************************************************/

    /**
     * 创建Xml
     * @return object
     */
    public function makeXml(){
        //所需属性
        $data = $this->dataFormatForXml();

        return $data;

    }

    /**
     * 处理属性字段
     * @param $node
     * @return mixed
     */
    public function dataFormatForXml(){
        return $this->data;
    }

    /****************************************************** 生成xml结束**************************************/

    /**
     * 返回格式化后的数据
     * @return array
     */
    public function getReturnData(){

        return $this->returnData;
    }
}