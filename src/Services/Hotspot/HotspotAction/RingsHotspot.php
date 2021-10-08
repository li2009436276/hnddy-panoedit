<?php


namespace Yjtec\PanoEdit\Services\Hotspot\HotspotAction;


use  Yjtec\PanoEdit\Repositories\Eloquent\RingsRepository;

class RingsHotspot extends HotspotAction
{

    /**
     * 热点展示时所需要的数据
     */
    public function actionData(){

        $this->returnData['action_data'] = json_encode($this->data['actionData']);

        if (config('pano_edit.project') == 'vryun') {

            //记录环物路径
            $this->saveRingsPath($this->data['actionData']);
        }

    }

    /**
     * 保存环物热点
     * @param $data
     * @return mixed
     */
    public function saveRingsPath($data){

        $rings = new RingsRepository(app());
        return $rings->create($data);
    }

    /************************************************* 生成XML ****************************************************/
    public function setNodeAttribute(){

        //判断图片是否存在
        if (!empty($this->data['action_data']) && !empty($this->data['action_data']['imgNumber'])) {

            //环物所需属性
            $this->data['ringsimgurl']      = \Storage::url(env('APP_ENV').$this->data['action_data']['imgUrl']);
            $this->data['ringsimgtotal']    = $this->data['action_data']['imgNumber'];
            $this->data['ringsimgid']       = '1';
            $this->data['ringsimgtype']     = $this->data['action_data']['imgType'];
            $this->data['ringsimgtitle']    = $this->data['title'];
            $this->data['ringsurl']         = $this->data['action_data']['jumpUrl'];
            $this->data['ringsurltitle']    = $this->data['action_data']['jumpUrlTitle'];
            $this->data['ringsdescribe']    = $this->data['action_data']['describe'];
            $this->data['ringsurltitle']    = $this->data['action_data']['jumpUrlTitle'];
            $this->data['ringsurljumptype'] = empty($this->data['action_data']['jumpType']) ? '_self' : '_blank';
            $this->data['onclick']          = 'ringsbuild();';

        }

    }
}