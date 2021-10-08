<?php


namespace Yjtec\PanoEdit\Services\Effect;


use Yjtec\PanoEdit\Services\Effect\EffectType\SunLight;
use Yjtec\PanoEdit\Services\Effect\EffectType\SystemEffect;

class EffectParent
{
    private $data       = [];     //所需数据
    private $sceneId    = 0;      //当前场景ID

    public function __construct($data,$sceneId)
    {

        $this->data     = $data;
        $this->sceneId  = $sceneId;
    }


    /**************************************** 生成xml ************************************************/

    /**
     * 追加节点
     * @param $data
     * @param $sceneNode 父节点 $sceneNode
     */
    public function makeXml($dom,$sceneNode){

        $embedNode = NULL;

        if (!empty($this->data) && !empty($this->data['type'])) {

            switch ($this->data['type']) {

                //系统图片
                case 'system':
                    //自定义图片
                case 'custom': {

                    (new SystemEffect($this->data,$this->sceneId))->makeXml($dom,$sceneNode);
                    break;
                }

                //太阳光
                case 'sunlight': {
                    (new SunLight($this->data,$this->sceneId))->makeXml($dom,$sceneNode);
                    break;
                }

            }
        }


    }

    /**************************************** 生成xml结束 *******************************************/
}