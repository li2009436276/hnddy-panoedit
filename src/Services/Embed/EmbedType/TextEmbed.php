<?php

namespace Yjtec\PanoEdit\Services\Embed\EmbedType;
class TextEmbed extends PublicEmbed
{

    /**************************************** 生成xml ****************************************************/

    /**
     * 处理属性字段
     * @return mixed
     */
    public function dataFormatForXml(){

        $actionData = json_decode($this->data['action_data'],true);

        $data = [
            'name'      =>  'hotspot_embed_'.$this->data['id'],
            'type'      => 'text',
            'url'       => \Storage::url(config('pano_edit.krpano_dir').'/plugins/textfield.swf'),
            'html'      => !empty($actionData['text']) ? $actionData['text'] : '',
            'textarrowimg' => \Storage::url(config('pano_edit.krpano_dir').'/plugin/embed/text_arrow.png'),
            'bgcolor'   => '#000000',
            'ath'       => $this->data['ath'],
            'atv'       => $this->data['atv'],
            'onloaded'  => 'textshowarrow_action();',
            'style'     => 'embedtextstyle'
        ];

        return $data;
    }

    /**************************************** 生成xml结束 ************************************************/
}