<?php


namespace Yjtec\PanoEdit\Services\Effect;


use Yjtec\PanoEdit\Services\PublicService;

class EffectService extends PublicService
{

    protected $data           = [];       //存储传递过来的数据
    protected $sceneId        = 0;        //当前场景ID
    protected $effectParent   = null;

    public function __construct($data = [],$sceneId = 0)
    {
        $this->data         = $data;
        $this->sceneId      = $sceneId;

        $this->effectParent = new EffectParent($data,$sceneId);
    }

    /****************************************************** 生成xml *****************************************/

    /**
     * 获取xml
     * @param $dom DOMDocument 对象
     * @param $sceneNode
     */
    public function makeXml($dom,$sceneNode)
    {

        $this->effectParent->makeXml($dom,$sceneNode);
    }

    /****************************************************** 生成xml完成 *****************************************/
}