<?php


namespace Yjtec\PanoEdit\Services\ProjectAction\ActionType;

class LoadSceneAction extends ProjectAction
{
    /******************************************* 详情 ******************************/

    /**
     * 详情
     * @return mixed
     */
    public function elementInfo(){

        if (!empty($this->data) && !empty($this->data['value'])) {

            $loadSceneAction = json_decode($this->data['value'],true);

            return  $loadSceneAction;

        }

        return ['type'=> 2];
    }
}