<?php


namespace Yjtec\PanoEdit\Services\Effect\EffectType;


use Yjtec\PanoEdit\Services\XmlService;

class PublicEffect
{

    protected $data         = [];       //传过来的数据
    private $sceneId        = 0;        //当前场景ID

    static $xmlService      = NULL;     //生成xml所需
    protected $sceneNode    = NULL;     //场景节点

    public function __construct($data,$sceneId)
    {
        $this->data         = $data;
        $this->sceneId      = $sceneId;
    }

    /**************************************** 生成xml ****************************************************/

    /**
     * 创建Xml
     * @return object
     */
    public function makeXml($dom,$sceneNode)
    {

        if (!self::$xmlService) {

            self::$xmlService = new XmlService($dom);
        }

        //当前场景节点
        $this->sceneNode = $sceneNode;

        $this->createElement();

    }

    /**
     * 生成特效
     * @return null
     */
    public function createElement(){

    }
}