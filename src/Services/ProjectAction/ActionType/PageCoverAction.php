<?php


namespace Yjtec\PanoEdit\Services\ProjectAction\ActionType;


class PageCoverAction extends ProjectAction
{

    /******************************************* 详情 ******************************/

    /**
     * 详情
     * @return mixed
     */
    public function elementInfo(){

        if (!empty($this->data) && !empty($this->data['value'])) {

            $pageCover = json_decode($this->data['value'],true);

            $pageCover['app_img'] = !empty($pageCover['app_img']) ? getStorageUrl(env('APP_ENV').$pageCover['app_img'])['url']: '';
            $pageCover['pc_img']  = !empty($pageCover['pc_img']) ? getStorageUrl(env('APP_ENV').$pageCover['pc_img'])['url'] : '';
            $pageCover['bg_img']  = !empty($pageCover['bg_img']) ? getStorageUrl(env('APP_ENV').$pageCover['bg_img'])['url'] : '';

            return  $pageCover;

        }

        return ['app_img'=> '','pc_img' => ''];
    }

    /************************************** 生成xml ***************************/

    /**
     * 生成节点
     * @return mixed
     */
    public function createElement($krpanoNode){
        
    }
}