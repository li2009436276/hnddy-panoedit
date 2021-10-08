<?php


namespace Yjtec\PanoEdit\Services\Hotspot\HotspotAction;


class JsCallHotspot extends HotspotAction
{
    /************************************************* 生成XML ****************************************************/
    public function setNodeAttribute(){

        if ($this->data['action_data']) {

            $this->data['onclick'] = 'js(handleHospotClick('.$this->data['action'].',\''.json_encode($this->data['action_data']).'\'));';
        }
    }
    /************************************************* 生成XML结束 ****************************************************/
}