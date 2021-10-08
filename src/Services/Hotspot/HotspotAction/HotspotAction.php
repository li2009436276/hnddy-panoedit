<?php


namespace Yjtec\PanoEdit\Services\Hotspot\HotspotAction;


use Yjtec\PanoEdit\Services\XmlService;

class HotspotAction
{

    protected $data         = [];       //传过来的数据
    protected $returnData   = [];       //返回的数据

    protected $sceneId      = 0;        //当前场景ID
    private $sceneArray     = [];       //项目所有场景

    static $xmlService      = NULL;     //生成xml所需

    public function __construct($data,$sceneId,$sceneArray)
    {
        $this->data         = $data;
        $this->sceneId      = $sceneId;
        $this->sceneArray   = $sceneArray;
    }

    /**
     * 初始化已有数据
     */
    public function format(){

        $this->returnData['scene_id']       = $this->sceneId;
        $this->returnData['type']           = $this->data['action'];
        $this->returnData['action']         = $this->data['action'];
        $this->returnData['ath']            = $this->data['ath'];
        $this->returnData['atv']            = $this->data['atv'];
        $this->returnData['icon_type']      = $this->data['iconType'];

        $this->returnData['title']          = !empty($this->data['title']) ? $this->data['title'] : '';

        if(isset($this->data['font_size'])){
            $this->returnData['font_size']      = $this->data['font_size'];
        }
        if(isset($this->data['scale'])){
            $this->returnData['scale']          = $this->data['scale'];
        }
        
        //如果actionData有数据是要存储数据的
        $this->actionData();

        //判断是否显示标题
        $this->showTitle();

        return $this;

    }

    /**
     * 热点展示时所需要的数据
     */
    public function actionData(){

        $this->returnData['action_data'] = is_array($this->data['actionData']) ? json_encode($this->data['actionData']) : '';
    }

    /**
     * 是否显示标题
     */
    public function showTitle(){

        if (isset($this->data['titleIsShow'])) {

            $this->returnData['show_title'] =   $this->data['titleIsShow'] == 'true' ? 1 : 0;
        }
    }

    /**
     * 返回格式化后的数据
     * @return array
     */
    public function getReturnData(){

        return $this->returnData;
    }

    /**
     * 获取当前场景ID
     * @return int
     */
    public function getCurrentSceneId(){

        return $this->sceneId;
    }

    /**
     * 根据前端sceneId返回数据库中的ID
     * @param $frontId
     * @return mixed
     */
    public function getSceneIdByFrontId($frontId){

        if (!empty($this->sceneArray) && !empty($this->sceneArray[$frontId])) {

            return $this->sceneArray[$frontId];
        }

        return 0;
    }


    /****************************************************** 生成xml *****************************************/

    /**
     * 创建Xml
     * @return object
     */
    public function makeXml(){

        //处理action_data
        $this->dataForXml();

        //添加hotspotNode热点属性
        $this->setNodeAttribute();

        //是否显示热点标题
        $this->data['showTitle'] = $this->data['show_title'];

        //热点图片类型
        $this->data['iconType']    = $this->data['icon_type'];

        //删除无用数据
        $this->delUselessData();

        return $this->data;
    }

    /**
     * 处理json数据
     */
    public function dataForXml(){

        $this->data['action']      = intval($this->data['action']);
        $this->data['img']         = json_decode($this->data['img'],true);
        $this->data['action_data'] = json_decode($this->data['action_data'],true);//处理标题
        $this->data['title']       = str_replace(',','，',$this->data['title']);
    }

    /**
     * 生成xml
     * @param $xmlService
     * @param $sceneNode
     * @param $hotspotNode
     * @return object
     */
    public function setNodeAttribute(){

    }


    /**
     * 删除无用的数据
     */
    public function delUselessData(){

        //删除无用的数据
        unset(
            $this->data['action'],
            $this->data['action_data'],
            $this->data['icon_type'],
            $this->data['deleted_at'],
            $this->data['created_at'],
            $this->data['updated_at'],
            $this->data['point'],
            $this->data['key'],
            $this->data['scene_id'],
            $this->data['type'],
            $this->data['show_title'],
            $this->data['onloaded'],
        );
    }

}
