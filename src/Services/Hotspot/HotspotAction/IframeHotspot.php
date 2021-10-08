<?php


namespace Yjtec\PanoEdit\Services\Hotspot\HotspotAction;


class IframeHotspot extends HotspotAction
{
    /************************************************* 生成XML ****************************************************/
    public function setNodeAttribute(){

        //判断视频是否存在
        if (!empty($this->data['action_data'])) {

            //判断渲染
            $this->data['onclick']          = 'looktohotspot(get(name),get(view.fov)); js(handleHotspotClick('.$this->data['action'].',JSON.stringify('.json_encode($this->data['action_data']).')));';

        }
    }
}