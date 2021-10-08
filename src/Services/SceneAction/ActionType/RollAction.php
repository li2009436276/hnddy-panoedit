<?php


namespace Yjtec\PanoEdit\Services\SceneAction\ActionType;


use Yjtec\PanoEdit\Repositories\Eloquent\SceneActionRepository;

class RollAction extends SceneAction
{
    protected $key         = 'roll';
    protected $actionId    = 4;

    /**
     * 保存数据
     */
    public function savEditSceneAction(){

        $sceneActionRepository = new SceneActionRepository(app());
        if (!empty($this->data['value']) && !empty($this->data['value']['text'])) {

            return $sceneActionRepository->saveEditAction($this->data['scene_id'],$this->data['action_id'],[$this->key=>$this->data['value']]);
        }

        return $sceneActionRepository->delete(['key'=>$this->data['key'],'scene_id'=>$this->data['scene_id']]);
    }

    /**
     * 格式化value
     */
    public function valueFormat(){

        return [
            'css'       =>  $this->data['css'],
            'text'      => !empty($this->data['text']) ? $this->data['text'] : '',
            'height'    => $this->data['height'],
            'bgcolor'   => $this->data['bgcolor'],
            'bgalpha'   => $this->data['bgalpha'],
            'speed'     => $this->data['speed'],
            'interval_time' => $this->data['interval_time']
        ];
    }

    /**************************************** 生成xml ****************************************************/

    /**
     * 处理属性字段
     * @param $node
     * @return mixed
     */
    public function dataFormatForXml(){

        $data                      = json_decode($this->data['value'],true);


        if (!empty($data) && !empty($data['text'])) {

            return json_decode($this->data['value'],'true');

        }

       return [];
    }

    /**************************************** 生成xml结束 ************************************************/
}