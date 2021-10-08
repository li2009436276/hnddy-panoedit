<?php


namespace Yjtec\PanoEdit\Services\Embed\EmbedType;


class VideoEmbed extends PublicEmbed
{
    /**************************************** 生成xml ****************************************************/

    /**
     * 处理属性字段
     * @return mixed
     */
    public function dataFormatForXml(){

        $actionData = json_decode($this->data['action_data'],true);
        if (!empty($actionData)) {

            if ($this->data['edit_type'] == 1) {

                $data = [
                    'name'          =>  'hotspot_embed_'.$this->data['id'],
                    'hotspotType'   => 'video',
                    'alturl'        => \Storage::url(config('pano_edit.krpano_dir').'/plugin/common/video/myvideoplay.js'),
                    'url'           => \Storage::url(config('pano_edit.krpano_dir').'/plugins/videoplayer.swf'),
                    'html5preload'  => 'auto',
                    'autopause'     => 'true',
                    'autoresume'    => 'true',

                    'ath'           => $this->data['ath'],
                    'atv'           => $this->data['atv'],
                    'rx'            => $this->data['rx'],
                    'ry'            => $this->data['ry'],
                    'rz'            => $this->data['rz'],

                    'posterurl'     => !empty($actionData['thumbUrl']) ? \Storage::url(env('APP_ENV').$actionData['thumbUrl']) : \Storage::url(env('APP_ENV').$actionData['videoUrl'].'?x-oss-process=video/snapshot,t_7000,f_jpg,w_0,h_0,m_fast'),
                    'videourl'      => \Storage::url(env('APP_ENV').$actionData['videoUrl']),

                    'distorted'     => 'true',
                    'loop'              => !empty($actionData['loop']) ? 'true': 'false',
                    'volume'            => !empty($actionData['volume']) ? $actionData['volume'] : 1,
                    'pausedonstart'     => !empty($actionData['autoplay']) ? 'false' : 'true',

                    'scale'         => $this->data['scale'],
                    'enabled'       => 'true',
                    'capture'       => 'false',
                    'renderer'      => 'webgl',
                    'onloaded'      => 'setVideoBtn();load_video();',
                    'onclick'       => 'closeVideoo(get(name));',
                ];
            } else {

                $data = [
                    'name'      => 'hotspot_embed_'.$this->data['id'],
                    'url'       => \Storage::url(config('pano_edit.krpano_dir').'/plugins/videoplayer.swf'),
                    'alturl'    => \Storage::url(config('pano_edit.krpano_dir').'/plugin/common/video/myvideoplay.js'),
                    'videourl'  => \Storage::url(env('APP_ENV').$actionData['videoUrl']),
                    'hotspotType'   => 'video',

                    'distorted' => 'true',
                    'onloaded'  => 'calc_pos_from_hfov_yaw_pitch_roll('.$this->data['scale'].', '.$this->data['rx'].', '.$this->data['ry'].', '.$this->data['rz'].');setVideoBtn();',

                    'loop'      => !empty($actionData['loop']) ? 'true': 'false',
                    'volume'            => !empty($actionData['volume']) ? $actionData['volume'] : 1,
                    'pausedonstart'     => !empty($actionData['autoplay']) ? 'false' : 'true',

                    'directionalsound'  => 'true',
                    'capture'           => 'false',
                    'range'             => '200',
                    'onclick'           => 'closeVideoo(get(name));',
                    'visible'           => 'true',
                    'zorder'            => '2'

                ];


                //添加封面
                $this->thumb($actionData);
            }

            return $data;
        }
        return [];
    }

    /**
     * 嵌入视频封面
     * @param $actionData
     */
    private function thumb($actionData){

        $hotsportNode = self::$xmlService->createElement('hotspot');

        $data = [
            'name'      => 'hotspot_embed_thumb_'.$this->data['id'],
            'url'       => !empty($actionData['thumbUrl']) ? \Storage::url(env('APP_ENV').$actionData['thumbUrl']) : \Storage::url(env('APP_ENV').$actionData['videoUrl'].'?x-oss-process=video/snapshot,t_7000,f_jpg,w_0,h_0,m_fast'),
            'zorder'    => '1',
            'distorted' => 'true',
            'enabled'   => 'false',
            'onloaded'  => 'calc_pos_from_hfov_yaw_pitch_roll('.$this->data['scale'].', '.$this->data['rx'].', '.$this->data['ry'].', '.$this->data['rz'].');set(hotspot[hotspot_embed_'.$this->data['id'].'].visible,true)'

        ];

        self::$xmlService->setAttribute($hotsportNode,$data);

        //增加当前节点
        self::$xmlService->appendChild($this->sceneNode,$hotsportNode);
    }

    /**************************************** 生成xml结束 ************************************************/
}