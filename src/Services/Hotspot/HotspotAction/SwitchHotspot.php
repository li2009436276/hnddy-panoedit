<?php


namespace Yjtec\PanoEdit\Services\Hotspot\HotspotAction;


class SwitchHotspot extends HotspotAction
{

    /**
     * 热点展示时所需要的数据
     */
    public function actionData(){

        if (!empty($this->data['actionData']['sceneId'])) {

            $sceneId = $this->getSceneIdByFrontId($this->data['actionData']['sceneId']);
            if ($sceneId) {

                $this->returnData['action_data'] = json_encode(['scene_id'=>$sceneId]);
            }
        }
    }

    /************************************************* 生成XML ****************************************************/
    public function setNodeAttribute(){

        //去除原onclick数据
        unset($this->data['onclick']);
        if (!empty($this->data['action_data']) && !empty($this->data['action_data']['scene_id'])) {

            //如果场景是字符串则场景xml是缓存生成的
            $this->data['onclick'] = 'js(handleHotspotClick('.$this->data['action'].',JSON.stringify({"scene_name":"scene_'.$this->data['action_data']['scene_id'].'"})));';
            if (!empty($this->data['action_data']['cache_id'])) {

                $this->data['onclick'] .= 'js(handleHotspotClick('.$this->data['action'].',JSON.stringify({"scene_name":"scene_'.$this->data['action_data']['cache_id'].'"})));';
            }
        }

    }
}