<?php


namespace Yjtec\PanoEdit\Services\Hotspot\HotspotAction;


use Yjtec\Emoji\Emoji;

class RichTextHotspot extends HotspotAction
{

    /************************************************* 数据保存 ****************************************************/
    public function actionData(){

        if (!empty($this->data['actionData']) && isset($this->data['actionData']['uuid'])) {

            if ( !empty($this->data['actionData']['htmlContent'])) {

                $htmlContent = Emoji::encode($this->data['actionData']['htmlContent']);
                unset($this->data['actionData']['htmlContent']);
            } else {

                $htmlContent = '';
            }

            $richTextInterface = resolve('Yjtec\PanoEdit\Repositories\Contracts\RichTextInterface');
            if (!empty($this->data['actionData']['richTextId'])) {

                $richTextInterface->update(['id'=>$this->data['actionData']['richTextId']],['uuid'=>$this->data['actionData']['uuid'],'content'=>$htmlContent]);
            } else {

                $richText =  $richTextInterface->index(['uuid'=>$this->data['actionData']['uuid']]);
                if ($richText) {

                    $richTextRes = $richTextInterface->update(['uuid'=>$this->data['actionData']['uuid']],['uuid'=>$this->data['actionData']['uuid'],'content'=>$htmlContent]);
                    if ($richTextRes) {
                        
                        $this->data['actionData']['richTextId'] = $richText->id;
                    }
                } else {

                    if ($htmlContent) {

                        $richTextRes = $richTextInterface->add(['uuid'=>$this->data['actionData']['uuid'],'content'=>$htmlContent]);

                        if ($richTextRes) {

                            $this->data['actionData']['richTextId'] = $richTextRes->id;
                        }
                    }
                }

            }

            $this->returnData['action_data'] = json_encode($this->data['actionData']);
        }
    }

    /************************************************* 生成XML ****************************************************/
    public function setNodeAttribute(){

        //判断视频是否存在
        if (!empty($this->data['action_data'])) {

            if (empty($this->data['action_data']['htmlContent']) && !empty($this->data['action_data']['richTextId'])) {

                $richTextInterface = resolve('Yjtec\PanoEdit\Repositories\Contracts\RichTextInterface');
                $res = $richTextInterface->index(['id'=>$this->data['action_data']['richTextId']]);
                if ($res) {

                    $this->data['action_data']['htmlContent'] = $res->content;
                }
            }


            return $this->data['onclick'] = 'js(handleHotspotClick('.$this->data['action'].',JSON.stringify('.json_encode($this->data['action_data']).')));';
        }
    }
}