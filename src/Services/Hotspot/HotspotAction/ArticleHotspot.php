<?php


namespace Yjtec\PanoEdit\Services\Hotspot\HotspotAction;


class ArticleHotspot extends HotspotAction
{
    /************************************************* 生成XML ****************************************************/
    public function setNodeAttribute(){

        if ($this->data['action_data'] && !empty($this->data['action_data']['article_id'])) {
            
            //判断渲染
            $this->data['data-linktitle'] = '';
            $this->data['data-linkurl'] = '';
            $this->data['data-jumptype'] = '_self';
            if (config('pano_edit.project') == 'yj_pano') {
                
                $this->data['data-iframeurl'] = config('env.gateway_url').'/api/pano/hotspot/hotspotArticle/' . $this->data['action_data']['article_id'];
            }

            $this->data['onclick'] = 'looktohotspot(get(name),get(view.fov)); iframelayer_open();';
        }

    }
}