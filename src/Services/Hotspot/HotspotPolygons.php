<?php


namespace Yjtec\PanoEdit\Services\Hotspot;

class HotspotPolygons extends HotspotParent
{

    /**
     * 格式化数据
     * @return array
     */
    public function format()
    {
        $this->formatData['attribute'] = parent::format();
        $this->setPoint();
        $this->setImg();

        return $this->formatData;
    }

    /**
     * 设置多边形点
     */
    public function setPoint(){

        //去掉点
        if (!empty($this->data) && !empty($this->data['img']) && !empty($this->data['img']['point'])) {

            $this->formatData['point'] = $this->data['img']['point'];
        }
    }

    /**
     * 设置img数据
     */
    public function setImg(){

        //去掉点
        if (!empty($this->data) && !empty($this->data['img']) && !empty($this->data['img']['point'])) {

            unset($this->data['img']['point']);
            $this->formatData['attribute']['img']            = json_encode($this->data['img']);
        } else {

            $this->formatData = [];
        }


    }


    /*****************************************************生成xml********************************************************/

    /**
     * 追加热点节点
     */
    public function makeXml(){

        //生成xml
        $hotspotNode = parent::makeXml();
        $hotspotNode['img']['point'] = $this->data['point'];
        return $hotspotNode;
    }

}