<?php


namespace Yjtec\PanoEdit\Services\Hotspot;

class HotspotOrdinary extends HotspotParent
{

    /**
     * 格式化数据
     * @return array
     */
    public function format()
    {

        $this->formatData = parent::format();

        $this->setUrl();
        $this->setImg();
        return $this->formatData;
    }


    /**
     * 设置热点图片
     */
    public function setUrl(){

        //兼容智慧城市
        if (strpos(' '.$this->data['url'],'http')) {

        } else if (strpos(' '.$this->data['url'],'/case') || (strpos(' '.$this->data['url'],'/Public') > 0) ) {

            $this->data['url'] = '/image'.$this->data['url'];
        }

        $this->formatData['url']    = $this->data['url'];
    }

    /**
     * 设置img数据
     */
    public function setImg(){

        if ($this->data['img']['type'] == 2) {
            
            //获取图片宽高
            $imgRes             = getImgWhComposer(hotspotUrlComposer($this->data['url']),$this->data['url']);

            $this->data['img']['imgwidth']  = $imgRes[0];
            $this->data['img']['imgheight'] = $imgRes[1];
            if (empty($this->data['img']['fwidth'])) {

                $this->data['img']['fwidth']  = $imgRes[0];
                $this->data['img']['fheight'] = $imgRes[0];
            }

        }

        $this->formatData['img'] = json_encode($this->data['img']);
    }

    /*****************************************************生成xml********************************************************/

    /**
     * 追加热点节点
     * @param $sceneNode 父节点 $sceneNode
     */
    public function makeXml(){

        $hotspotNode = parent::makeXml();

        //处理热点图片属性
        $hotspotNode['url']    = hotspotUrlComposer($hotspotNode['url']);

        //处理序列图
        if (!empty($hotspotNode['img']) && $hotspotNode['img']['type'] == 2 && empty($hotspotNode['img']['imgwidth'])) {

            $imgRes                 = getImgWhComposer($hotspotNode['url'], $this->data['url']);
//            $data['fwidth']         = $imgRes[0];
//            $data['fheight']        = $imgRes[0];
            $data['fwidth']         =isset($hotspotNode['img']['fwidth'])?$hotspotNode['img']['fwidth']:$imgRes[0];
            $data['fheight']        =isset($hotspotNode['img']['fheight'])?$hotspotNode['img']['fheight']:$imgRes[0];
            $data['imgwidth']       = $imgRes[0];
            $data['imgheight']      = $imgRes[1];

            $hotspotNode['img'] = array_merge($hotspotNode['img'],$data);
        }

        return $hotspotNode;
    }


}
