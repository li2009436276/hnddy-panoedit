<?php


namespace Yjtec\PanoEdit\Services\Embed\EmbedType;


class OrderEmbed extends PublicEmbed
{

    /*************************************** 保存 *******************************************************/


    public function actionData(){

        $playTime = $this->data['actionData']['playTime'];

        //获取图片宽高
        $imgRes             = getImgWhComposer(\Storage::url(env('APP_ENV').$this->data['actionData']['url']), $this->data['actionData']['url']);

        $playTime['imgwidth']  = $imgRes[0];
        $playTime['imgheight'] = $imgRes[1];
        if (empty($playTime['fwidth'])) {

            $playTime['fwidth']  = $imgRes[0];
            $playTime['fheight'] = $imgRes[0];
        }

        $this->data['actionData']['playTime'] = $playTime;
        $this->returnData['action_data'] = json_encode($this->data['actionData']);
    }

    /**************************************** 生成xml ****************************************************/

    /**
     * 处理属性字段
     * @return mixed
     */
    public function dataFormatForXml(){

        $actionData = json_decode($this->data['action_data'],true);

        $framewidth  = 0;
        $frameheight = 0;
        $imgwidth    = 0;
        $imgheight   = 0;

        $imgSize = [0,0];
        if (empty($actionData['playTime']['fwidth'])) {

            $imgSize    = !empty($actionData['url']) ? getImgWhComposer(\Storage::url(env('APP_ENV').$actionData['url']), $actionData['url']):[0,0];

            $framewidth  = $imgSize[0];
            $frameheight = $imgSize[0];
            $imgwidth    = $imgSize[0];
            $imgheight   = $imgSize[1];
        } else {

            $framewidth  = $actionData['playTime']['fwidth'];
            $frameheight = $actionData['playTime']['fheight'];
            $imgwidth    = $actionData['playTime']['imgwidth'];
            $imgheight   = $actionData['playTime']['imgheight'];
        }

        $data = [
            'name'          =>  'hotspot_embed_'.$this->data['id'],
            'url'           => \Storage::url(env('APP_ENV').$actionData['url']),
            'crop'          => '0|0|'.$framewidth.'|'.$frameheight,
            'framewidth'    => $framewidth,
            'frameheight'   => $frameheight,
            'imgwidth'      => $imgwidth,
            'imgheight'     => $imgheight,
            'frame'         => 0,
            'frameoff'      => 0,
            'lastframe'     => $actionData['playTime']['total'],
            'ath'           => $this->data['ath'],
            'atv'           => $this->data['atv'],
            'rx'            => $this->data['rx'],
            'ry'            => $this->data['ry'],
            'rz'            => $this->data['rz'],
            'framespeed'    => $actionData['playTime']['pertime'],
            'scale'         => $this->data['scale'],
            'onloaded'      => 'sequence_animate();',
            'distorted' => 'true',
        ];

        return $data;
    }

    /**************************************** 生成xml结束 ************************************************/
}
