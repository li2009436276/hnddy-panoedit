<?php


namespace Yjtec\PanoEdit\Services\Effect\EffectType;


class SystemEffect extends PublicEffect
{
    /**************************************** 生成xml ****************************************************/

    /**
     * 处理属性字段
     * @return mixed
     */
    public function createElement(){


        if (empty($this->data['imageurl']) || $this->data['imageurl'] == '/image/snow/') {

            return ;
        }

        //添加插件
        $attribute = [ 'name'=>"snow", 'zorder'=>"1",'url'=>\Storage::url("krpano/plugins/snow.swf"),'alturl'=>\Storage::url("krpano/plugins/snow.js"), 'floor'=>"0.7",'onloaded'=>$this->sceneNode->getAttribute('name')."_effect();"];
        $pluginNode = self::$xmlService->createElement('plugin');
        self::$xmlService->setAttribute($pluginNode,$attribute);

        $this->sceneNode->appendChild($pluginNode);

        $this->addEffectAction();

    }

    /**
     * 特效action
     * @param $node
     * @param $effect
     */
    private function addEffectAction(){

        $effectActionNode = self::$xmlService->createElement('action');
        $effectActionNode->setAttribute('name',$this->sceneNode->getAttribute('name').'_effect');

        $effectSpeed = config('project_config.effect_speed')[$this->data['effect_size'] -1];
        $effectSpeed['imageurl']     = '"'.\Storage::url(env('APP_ENV').$this->data['imageurl']).'"';

        $nodeValue              = 'set(plugin[snow].mode,image); set(plugin[snow].blendmode,     normal);';
        foreach ($effectSpeed as $key=>$value) {

            $nodeValue .= 'set(plugin[snow].'.$key.', '.$value.');';
        }

        $effectActionNode->nodeValue = $nodeValue;
        $this->sceneNode->appendChild($effectActionNode);
    }

    /**************************************** 生成xml结束 ****************************************************/
}