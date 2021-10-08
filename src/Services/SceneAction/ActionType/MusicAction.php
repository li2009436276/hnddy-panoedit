<?php


namespace Yjtec\PanoEdit\Services\SceneAction\ActionType;


class MusicAction extends SceneAction
{
    /**************************************** 生成xml ****************************************************/

    /**
     * 处理属性字段
     * @param $node
     * @return mixed
     */
    public function dataFormatForXml(){

        $data                      = json_decode($this->data['value'],true);

        if (!empty($data) && !empty($data['musicUrl'])) {

            //查询按钮是否展示
            $musicUrl     = \Storage::url(env('APP_ENV').$data['musicUrl']);
            $play         = !empty($data['defaultPlay']) ? 1: 0;
            $volume       = !empty($data['volume']) ? $data['volume']: 100;

            return [$this->data['key']=>['url'=>$musicUrl,'loop'=>$data['loop'],'autoplay'=>$play,'volume'=>$volume]];
        }

        return [];
    }
}