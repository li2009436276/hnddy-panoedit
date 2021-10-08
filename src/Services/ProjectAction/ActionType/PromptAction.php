<?php


namespace Yjtec\PanoEdit\Services\ProjectAction\ActionType;


class PromptAction extends ProjectAction
{

    /******************************************* 详情 ******************************/

    /**
     * 详情
     * @return mixed
     */
    public function elementInfo(){

        if (!empty($this->data) && !empty($this->data['value'])) {

            $prompt = json_decode($this->data['value'],true);
            
            $prompt['app_img'] = !empty($prompt['app_img']) ? getStorageUrl(env('APP_ENV').$prompt['app_img'])['url']: '';
            $prompt['pc_img']  = !empty($prompt['pc_img']) ? getStorageUrl(env('APP_ENV').$prompt['pc_img'])['url'] : '';
            $prompt['time']    = isset($prompt['time']) ? $prompt['time'] : '5';
            return $prompt;
        }

        return ['app_img'=> '','pc_img' => '', 'time' => 0];
    }


    /************************************** 生成xml ***************************/

    /**
     * 生成节点
     * @return mixed
     */
    public function createElement($krpanoNode){

        if ($this->data) {

            $data = json_decode($this->data,true);
            if ($data && !empty($data['url']) && !empty($data['time']) > 0) {

                $iconData      = array_merge(config('project_config.start_pic_icon'),['url'=>\Storage::url(env('APP_ENV').$data['url'])]);

                $containerLayer = self::$xmlService->createElement('layer');
                $iconLayer      = self::$xmlService->createElement('layer');

                self::$xmlService->setAttribute($containerLayer,config('project_config.start_pic_container'));
                self::$xmlService->setAttribute($iconLayer,$iconData);

                self::$xmlService->appendChild($containerLayer,$iconLayer);
                self::$xmlService->appendChild($krpanoNode,$containerLayer);
            }
        }
    }
}