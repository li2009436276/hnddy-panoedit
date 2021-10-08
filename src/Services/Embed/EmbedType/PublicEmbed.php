<?php


namespace Yjtec\PanoEdit\Services\Embed\EmbedType;


use Yjtec\PanoEdit\Services\XmlService;

class PublicEmbed
{
    protected $data         = [];       //传过来的数据
    protected $returnData   = [];       //返回的数据

    private $key            = 1;        //当前排序
    private $sceneId        = 0;        //当前场景ID
    private $sceneArray     = [];       //项目所有场景

    static $xmlService      = NULL;     //生成xml所需
    protected $sceneNode    = NULL;     //场景节点

    public function __construct($data,$key,$sceneId,$sceneArray)
    {
        $this->data         = $data;
        $this->key          = $key;
        $this->sceneId      = $sceneId;
        $this->sceneArray   = $sceneArray;
    }

    /**
     * 初始化已有数据
     */
    public function format(){

        $this->returnData['scene_id']       = $this->sceneId;
        $this->returnData['key']            = $this->key;
        $this->returnData['type']           = $this->data['type'];
        $this->returnData['ath']            = $this->data['locationData']['ath'];
        $this->returnData['atv']            = $this->data['locationData']['atv'];
        $this->returnData['rx']             = !empty($this->data['locationData']['rx']) ? $this->data['locationData']['rx'] : '0';
        $this->returnData['ry']             = !empty($this->data['locationData']['ry']) ? $this->data['locationData']['ry'] : '0';
        $this->returnData['rz']             = !empty($this->data['locationData']['rz']) ? $this->data['locationData']['rz'] : '0';
        $this->returnData['scale']          = !empty($this->data['locationData']['scale']) ? $this->data['locationData']['scale'] : 1.00;
        $this->returnData['edit_type']      = !empty($this->data['locationData']['edit_type']) ? $this->data['locationData']['edit_type'] : 1;

        //如果actionData有数据是要存储数据的
        if (!empty($this->data['actionData'])) {

            $this->actionData();
        }

        return $this;

    }

    /**
     * 热点展示时所需要的数据
     */
    public function actionData(){

        $this->returnData['action_data'] = json_encode($this->data['actionData']);
    }

    /**************************************** 处理返回编辑数据 ***************************************/
    public function editDataFormat(){

        $this->returnData['id']             = randomStr(10);
        $this->returnData['type']           = $this->data['type'];
        $this->returnData['locationData']   = [
            'ath'   => $this->data['ath'],
            'atv'   => $this->data['atv'],
            'rx'    => $this->data['rx'],
            'ry'    => $this->data['ry'],
            'rz'    => $this->data['rz'],
            'scale' => $this->data['scale'],
            'edit_type' => $this->data['edit_type'],
        ];

        $this->editActionData();

        return $this;
    }

    /**
     * 返回actionData数据
     */
    public function editActionData(){

        $this->returnData['actionData'] = !empty($this->data['action_data']) ? json_decode($this->data['action_data'],true) : new \stdClass();
    }

    /**************************************** 处理返回编辑数据结束 ***************************************/

    /**************************************** 生成xml ****************************************************/

    /**
     * 创建Xml
     * @return object
     */
    public function makeXml($sceneNode){

        if (!self::$xmlService) {

            self::$xmlService = new XmlService();
        }

        //当前场景节点
        $this->sceneNode = $sceneNode;

        $hotsportNode = self::$xmlService->createElement('hotspot');

        //处理属性字段
        $data = $this->dataFormatForXml();

        //热点普通属性
        self::$xmlService->setAttribute($hotsportNode,$data);

        //增加当前节点
        self::$xmlService->appendChild($sceneNode,$hotsportNode);
        return $hotsportNode;
    }

    /**
     * 处理属性字段
     * @return mixed
     */
    public function dataFormatForXml(){
        return $this->data;
    }

    /**************************************** 生成xml结束 ************************************************/

    /**
     * 返回格式化后的数据
     * @return array
     */
    public function getReturnData(){

        return $this->returnData;
    }
}