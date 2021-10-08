<?php


namespace Yjtec\PanoEdit\Services\Embed\EmbedType;


class PicEmbed extends PublicEmbed
{

    /**
     * 图片嵌入所需数据
     */
    public function actionData(){

        $actionData = $this->data['actionData'];
        unset($actionData['img']);
        $this->returnData['action_data'] = json_encode($actionData);
    }

    /**
     * 处理图片
     * @return mixed
     */
    public function actionDataImg()
    {
        if (!empty($this->data['actionData']) && !empty($this->data['actionData']['img'])) {

            return $this->data['actionData']['img'];
        }

        return [];
    }

    /**************************************** 处理返回编辑数据 ***************************************/

    /**
     * 返回actionData数据
     */
    public function editActionData(){

        $this->returnData['actionData'] = json_decode($this->data['action_data'],true);
        if ($this->data['img']) {

            $this->returnData['actionData']['img'] = $this->data['img'];
        }
    }

    /**************************************** 生成xml ****************************************************/

    /**
     * 处理属性字段
     * @return mixed
     */
    public function dataFormatForXml(){

        $actionData = json_decode($this->data['action_data'],true);
        if(!isset($actionData['time'])){//如果参数未设置
            $actionData['time']=3;//默认为3
        }

        $data = [
            'name'      =>  'hotspot_embed_'.$this->data['id'],
            'url'       => !empty($this->data['img']) ? \Storage::url(env('APP_ENV').$this->data['img'][0]['url']) : '',
            'frame'     => '0',
            'lastframe' => count($this->data['img']),
            'onloaded'  => 'carousel_animate();',
            'ath'       => $this->data['ath'],
            'atv'       => $this->data['atv'],
            'rx'        => $this->data['rx'],
            'ry'        => $this->data['ry'],
            'rz'        => $this->data['rz'],
            'scale'     => $this->data['scale'],
            'distorted' => 'true',
            'carouseltime' => $actionData['time']
        ];

        return $data;
    }

    /**
     * 图片嵌入
     * @param $node
     */
    public function imgForXml($node){

        if (!empty($this->data['img'])) {

            foreach ($this->data['img'] as $key=>$value){

                $data['name']  = 'embed_img_'.$value['id'];
                $data['url']   = \Storage::url(env('APP_ENV').$value['url']);
                $embedNode = self::$xmlService->createElement('embed_carousel_img');

                self::$xmlService->setAttribute($embedNode,$data);

                self::$xmlService->appendChild($node,$embedNode);
            }
        }

    }

    /**************************************** 生成xml结束 ************************************************/
}