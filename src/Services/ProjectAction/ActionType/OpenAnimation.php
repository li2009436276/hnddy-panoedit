<?php


namespace Yjtec\PanoEdit\Services\ProjectAction\ActionType;

class OpenAnimation extends ProjectAction
{
    /************************************** 生成xml ***************************/

    /**
     * 生成节点
     * @return mixed
     */
    public function createElement($krpanoNode){

        static $plugin = 0;

        //应用插件
        if (!$plugin) {

            //先启用$dom
            self::$xmlService->addXmlPlugin('projectaction/open_animation');
            $plugin = 1;
        }


        if ($this->data) {

            $openAnimation = json_decode($this->data['value'],true);

            $settings = $krpanoNode->getElementsByTagName("skin_settings")[0];
            $settings->setAttribute('open_animation_type',$openAnimation['type']);
        }
    }
}