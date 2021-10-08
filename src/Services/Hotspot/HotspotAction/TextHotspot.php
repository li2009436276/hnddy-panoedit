<?php


namespace Yjtec\PanoEdit\Services\Hotspot\HotspotAction;


class TextHotspot extends HotspotAction
{


    /****************************************************** 生成xml *****************************************/

    /**
     * 生成hotspot属性
     * @param $xmlService
     * @param $sceneNode
     * @param $hotspotNode
     */
    public function setNodeAttribute(){

        //处理action_data必须是数组
        if(!is_array($this->data['action_data'])) $this->data['action_data'] = [];

        //遍历图文列表
        foreach ($this->data['action_data'] as $k=>&$v){
            //去掉加号这个图片
            if (strpos(' '.$v['url'],'/Public/Home/images/qjgl.jpg')) {

                continue;
            }

            if (!empty($v['url'])) {

                $v['url'] = $this->imgUrl($v['url']);
            } else {

                unset($this->data['action_data'][$k]);
            }
        }
        $this->data['onclick']          = 'js(handleHotspotClick('.$this->data['action'].',JSON.stringify('.json_encode($this->data['action_data']).')));';
    }

    /**
     * 处理图片路径  兼容智慧城市
     * @param $path
     */
    private function imgUrl($path){

        if (strpos($path, 'http://') === 0) {
            
            $imgurl = $path;
        } else {
            $imgurl = \Storage::url(env('APP_ENV').$path);
        }

        return $imgurl;
    }
}