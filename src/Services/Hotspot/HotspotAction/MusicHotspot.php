<?php


namespace Yjtec\PanoEdit\Services\Hotspot\HotspotAction;


class MusicHotspot extends HotspotAction
{


    /****************************************************** 生成xml *****************************************/

    /**
     * 生成hotspot属性
     * @param $xmlService
     * @param $sceneNode
     * @param $hotspotNode
     */
    public function setNodeAttribute()
    {
        if (!empty($this->data['action_data']['musicUrl'])) {

            $this->data['action_data']['id'] = $this->data['id'];
            $this->data['action_data']['musicUrl'] = $this->musicPath($this->data['action_data']['musicUrl']);
            $this->data['onclick'] = 'js(handleHotspotClick('.$this->data['action'].',JSON.stringify('.json_encode($this->data['action_data']).')));';
        }

    }

    /**
     * 处理音乐路径  兼容智慧城市
     * @param $path
     * @return mixed
     */
    private function musicPath($path){

        if (count(explode('.360vrsh.com', $path)) == 1) {
            $musicUrl = \Storage::url(env('APP_ENV').$path);
        } else {
            $musicUrl = $path;
        }

        return $musicUrl;
    }
}