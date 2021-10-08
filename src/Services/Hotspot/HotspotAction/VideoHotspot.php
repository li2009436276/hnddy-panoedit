<?php


namespace Yjtec\PanoEdit\Services\Hotspot\HotspotAction;


class VideoHotspot extends HotspotAction
{
    /************************************************* 生成XML ****************************************************/
    public function setNodeAttribute(){

        //判断视频是否存在
        if (!empty($this->data['action_data'])) {

            //判断渲染
            $this->data['data-linktitle']   = $this->data['action_data']['jumpUrlTitle'];
            $this->data['data-linkurl']     = $this->data['action_data']['jumpUrl'];
            $this->data['data-jumptype']    = empty($this->data['action_data']['jumpType']) ? '_self' : '_blank';
            $this->data['data-loop']        = $this->data['action_data']['loop'] == 1 ? 'true' : 'false';
            $this->data['data-pausestart']  = $this->data['action_data']['autoplay'] == 1 ? 'false' : 'true';

            $this->getVideoUrl();
        }

    }

    /**
     * 获取视频地址
     * @param $dataAction
     * @return string|string[]|null
     */
    private function getVideoUrl(){

        switch ($this->data['action_data']['videoType']) {

            case 1: {

                $this->data['action_data']['thumbUrl']  = !empty($this->data['action_data']['thumbUrl']) ? \Storage::url(env('APP_ENV').$this->data['action_data']['thumbUrl']) : \Storage::url('/krpano/plugin/image/defaultVideoThumb.png');
                $this->data['action_data']['videoUrl']    = !empty($this->data['action_data']['videoUrl']) ? \Storage::url(env('APP_ENV').$this->data['action_data']['videoUrl']) : '';

                $this->data['onclick']          = 'looktohotspot(get(name),get(view.fov)); js(handleHotspotClick('.$this->data['action'].',JSON.stringify('.json_encode($this->data['action_data']).')));';
                break;
            }

            case 2: {

                preg_match("/(?<=src=)([^>]*)(?=>)/i",$this->data['action_data']['IframeVideo'],$array);
                if ($array) {

                    $urlArray = explode(' ',$array[0]);
                    if ($urlArray) {

                        $this->data['onclick']          = 'looktohotspot(get(name),get(view.fov)); js(handleHotspotClick('.$this->data['action'].',JSON.stringify('.json_encode($this->data['action_data']).')));';
                        break;
                    }

                }

                $this->data['data-videourl'] = '';
                break;
            }
        }
    }
}