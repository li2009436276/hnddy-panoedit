<?php


namespace Yjtec\PanoEdit\Services\Hotspot;

use Yjtec\PanoEdit\Services\Hotspot\HotspotAction\ArticleHotspot;
use Yjtec\PanoEdit\Services\Hotspot\HotspotAction\IframeHotspot;
use Yjtec\PanoEdit\Services\Hotspot\HotspotAction\JsCallHotspot;
use Yjtec\PanoEdit\Services\Hotspot\HotspotAction\MusicHotspot;
use Yjtec\PanoEdit\Services\Hotspot\HotspotAction\PicHotspot;
use Yjtec\PanoEdit\Services\Hotspot\HotspotAction\RichTextHotspot;
use Yjtec\PanoEdit\Services\Hotspot\HotspotAction\RingsHotspot;
use Yjtec\PanoEdit\Services\Hotspot\HotspotAction\SwitchHotspot;
use Yjtec\PanoEdit\Services\Hotspot\HotspotAction\TelHotspot;
use Yjtec\PanoEdit\Services\Hotspot\HotspotAction\TextHotspot;
use Yjtec\PanoEdit\Services\Hotspot\HotspotAction\UrlHotspot;
use Yjtec\PanoEdit\Services\Hotspot\HotspotAction\VideoHotspot;

class HotspotParent
{

    protected $sceneId        = 0;          //当前场景
    protected $sceneArray     = [];         //当前项目下所有场景，数据格式是   [ 前端ID => 数据库ID ]
    protected $data           = [];         //需要格式的数据

    protected $formatData     = [];         //格式化数据

    public function __construct($data,$sceneId,$sceneArray)
    {
        $this->data         = $data;
        $this->sceneId      = $sceneId;
        $this->sceneArray   = $sceneArray;
    }

    /**
     * 格式化数据
     * @return array
     */
    public function format()
    {

        switch ($this->data['action']) {

            //切换场景热点
            case 1: {

                $attribute = (new SwitchHotspot($this->data,$this->sceneId,$this->sceneArray))
                    ->format()
                    ->getReturnData();
                break;
            }

            //切换场景热点
            case 2: {

                $attribute = (new UrlHotspot($this->data,$this->sceneId,$this->sceneArray))
                    ->format()
                    ->getReturnData();
                break;
            }

            //环物
            case 7: {

                $attribute = (new RingsHotspot($this->data,$this->sceneId,$this->sceneArray))
                    ->format()
                    ->getReturnData();
                break;
            }

            //视频热点
            case 8: {

                $attribute = (new VideoHotspot($this->data,$this->sceneId,$this->sceneArray))
                    ->format()
                    ->getReturnData();
                break;
            }

            //弹出网页
            case 9: {

                $attribute = (new IframeHotspot($this->data,$this->sceneId,$this->sceneArray))
                    ->format()
                    ->getReturnData();
                break;
            }

            //富文本热点
            case 10 :{

                $attribute = (new ArticleHotspot($this->data,$this->sceneId,$this->sceneArray))
                    ->format()
                    ->getReturnData();
                break;
            }

            //单独调用js方法热点,通用热点
            case 11 :{

                $attribute = (new JsCallHotspot($this->data,$this->sceneId,$this->sceneArray))
                    ->format()
                    ->getReturnData();
                break;
            }

            //VR云富文本
            case 14 :{

                $attribute = (new RichTextHotspot($this->data,$this->sceneId,$this->sceneArray))
                    ->format()
                    ->getReturnData();
                break;
            }

            //根据项目设置不同的热点
            default : {

                $attribute =  config('pano_edit.project') == 'vryun' ? $this->setVryunHotspot() : $this->setQjHotspot();
            }
        }

        return $attribute;
    }

    /**
     * 因为vryun与全景智慧城不一样暂时这样处理
     * 设置vryun热点
     */
    public function setVryunHotspot(){

        switch ($this->data['action']){

            //切换场景热点
            case 3: {

                $attribute = (new TelHotspot($this->data,$this->sceneId,$this->sceneArray))
                    ->format()
                    ->getReturnData();
                break;
            }

            //图片热点
            case 4: {

                $attribute = (new PicHotspot($this->data,$this->sceneId,$this->sceneArray))
                    ->format()
                    ->getReturnData();
                break;
            }

            //图文热点
            case 5: {

                $attribute = (new TextHotspot($this->data,$this->sceneId,$this->sceneArray))
                    ->format()
                    ->getReturnData();
                break;
            }

            //音乐热点
            case 6: {

                $attribute = (new MusicHotspot($this->data,$this->sceneId,$this->sceneArray))
                    ->format()
                    ->getReturnData();
                break;
            }
        }

        return $attribute;
    }

    /**
     * 因为vryun与全景智慧城不一样暂时这样处理
     * 设置全景智慧城热点
     */
    public function setQjHotspot(){

        switch ($this->data['action']){

            //图片热点
            case 3: {

                $attribute = (new PicHotspot($this->data,$this->sceneId,$this->sceneArray))
                    ->format()
                    ->getReturnData();
                break;
            }

            //图文热点
            case 4: {

                $attribute = (new TextHotspot($this->data,$this->sceneId,$this->sceneArray))
                    ->format()
                    ->getReturnData();
                break;
            }

            //音乐热点
            case 5: {

                $attribute = (new MusicHotspot($this->data,$this->sceneId,$this->sceneArray))
                    ->format()
                    ->getReturnData();
                break;
            }

            //电话热点
            case 6: {

                $attribute = (new TelHotspot($this->data,$this->sceneId,$this->sceneArray))
                    ->format()
                    ->getReturnData();
                break;
            }

        }

        return $attribute;
    }


    /*****************************************************生成xml********************************************************/

    /**
     * 追加热点节点
     * @return mixed
     */
    public function makeXml(){

        $hotspotNode = NULL;

        switch ($this->data['action']) {

            //切换场景热点
            case 1: {

                $hotspotNode = (new SwitchHotspot($this->data,$this->sceneId,$this->sceneArray))->makeXml();
                break;
            }

            //切换场景热点
            case 2: {

                $hotspotNode = (new UrlHotspot($this->data,$this->sceneId,$this->sceneArray))->makeXml();
                break;
            }

            //环物热点
            case 7: {
                $hotspotNode = (new RingsHotspot($this->data,$this->sceneId,$this->sceneArray))->makeXml();
                break;
            }

            //热点视频
            case 8: {

                $hotspotNode = (new VideoHotspot($this->data,$this->sceneId,$this->sceneArray))->makeXml();
                break;
            }

            //弹出网页
            case 9 : {

                $hotspotNode = (new IframeHotspot($this->data,$this->sceneId,$this->sceneArray))->makeXml();
                break;
            }

            //富文本热点
            case 10 : {
                $hotspotNode = (new ArticleHotspot($this->data,$this->sceneId,$this->sceneArray))->makeXml();
                break;
            }

            //单独调用js方法热点,通用热点
            case 11 : {

                $hotspotNode = (new JsCallHotspot($this->data,$this->sceneId,$this->sceneArray))->makeXml();
                break;
            }

            //VR云富文本
            case 14 : {

                $hotspotNode = (new RichTextHotspot($this->data,$this->sceneId,$this->sceneArray))->makeXml();
                break;
            }

            //根据项目设置不同的热点
            default : {

                $hotspotNode = config('pano_edit.project') == 'vryun' ? $this->makeVryunXml() : $this->makeQjXml();
            }
        }

        return $hotspotNode;
    }


    /**
     * 因为vryun与全景智慧城不一样暂时这样处理
     * 生成vryun 的 Xml
     */
    private function makeVryunXml(){

        switch ($this->data['action']){

            //切换场景热点
            case 3: return (new TelHotspot($this->data,$this->sceneId,$this->sceneArray))->makeXml();

            //图片热点
            case 4: return (new PicHotspot($this->data,$this->sceneId,$this->sceneArray))->makeXml();

            //图文热点
            case 5: return (new TextHotspot($this->data,$this->sceneId,$this->sceneArray))->makeXml();

            //音乐热点
            case 6: return (new MusicHotspot($this->data,$this->sceneId,$this->sceneArray))->makeXml();

        }
    }

    /**
     * 因为vryun与全景智慧城不一样暂时这样处理
     * 设置全景智慧城热点
     */
    public function makeQjXml(){

        switch ($this->data['action']){

            //图片热点
            case 3:  return (new PicHotspot($this->data,$this->sceneId,$this->sceneArray))->makeXml();

            //图文热点
            case 4: return (new TextHotspot($this->data,$this->sceneId,$this->sceneArray))->makeXml();

            //音乐热点
            case 5: return (new MusicHotspot($this->data,$this->sceneId,$this->sceneArray))->makeXml();

            //电话热点
            case 6: return (new TelHotspot($this->data,$this->sceneId,$this->sceneArray))->makeXml();

        }
    }

    /*****************************************************生成xml结束********************************************************/
}