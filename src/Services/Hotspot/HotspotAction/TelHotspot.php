<?php


namespace Yjtec\PanoEdit\Services\Hotspot\HotspotAction;


class TelHotspot extends HotspotAction
{


    /**
     * 获取点击切换场景事件
     */
    public function onclick(){

        if (!empty($this->data['actionData']['phone'])) {

            $this->returnData['onclick'] = 'openurl(tel:'.$this->data['actionData']['phone'].');';
        }

        return $this;
    }

    /************************************************* 生成XML ****************************************************/
    public function setNodeAttribute(){

        if (!empty($this->data['action_data']['phone'])) {

            $this->data['onclick'] = 'js(handleHotspotClick('.$this->data['action'].',JSON.stringify('.json_encode($this->data['action_data']).')));';
        }
    }
}