<?php


namespace Yjtec\PanoEdit\Services\Effect\EffectType;


class SunLight extends PublicEffect
{


    /****************************************************** 生成xml *****************************************/

    /**
     * 处理属性字段
     * @return mixed
     */
    public function createElement(){

        //添加插件
        static $plugin = 0;
        static $lensflaresNode = null;
        //应用插件
        if (!$plugin) {

            //先启用$dom
            self::$xmlService->addXmlPlugin('sunlight/core');

            $krpnao = self::$xmlService->getElementsByTagName("krpano")[0];

            $lensflaresNode = self::$xmlService->createElement('lensflares');
            self::$xmlService->setAttribute($lensflaresNode,['name'=>'obj']);
            self::$xmlService->appendChild($krpnao,$lensflaresNode);
            $plugin = 1;
        }

        $this->addItem($lensflaresNode);
    }

    /**
     * 场景添加太阳光
     */
    public function addItem($lensflaresNode){

        $sceneName = $this->sceneNode->getAttribute('name');
        $data = ['name'=> 'flarka_'.$sceneName, 'ath'=>$this->data['ath'], 'atv'=>$this->data['atv'], 'scene'=>$sceneName,'typ'=>'blinkstyle1', 'dust_effect'=>'false'];

        $itemNode = self::$xmlService->createElement('item');
        self::$xmlService->setAttribute($itemNode,$data);
        self::$xmlService->appendChild($lensflaresNode,$itemNode);
    }

    /****************************************************** 生成xml完成 *****************************************/

}