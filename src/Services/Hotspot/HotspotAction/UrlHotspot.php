<?php


namespace Yjtec\PanoEdit\Services\Hotspot\HotspotAction;


class UrlHotspot extends HotspotAction
{

    /**
     * 获取点击切换场景事件
     */
    public function onclick(){

        if (!empty($this->data['actionData']['url'])) {

            $openType = (!empty($this->data['actionData']['openType']) && $this->data['actionData']['openType'] == 1) ? '_self' : '_black';

            $this->returnData['onclick'] = 'openurl('.$this->data['actionData']['url'].','.$openType.');';
        }
        return $this;
    }

    /************************************************* 生成XML ****************************************************/
    public function setNodeAttribute(){

        if (!empty($this->data['action_data']['url'])) {

            $this->data['onclick'] = 'js(handleHotspotClick('.$this->data['action'].',JSON.stringify('.json_encode($this->data['action_data']).')));';
        }
    }

}