<?php


namespace Yjtec\PanoEdit\Services\Embed;

use Yjtec\PanoEdit\Repositories\Eloquent\EmbedRepository;
use Yjtec\PanoEdit\Services\PublicService;
use Yjtec\PanoEdit\Services\XmlService;

class EmbedService extends PublicService
{

    protected $sceneId                      = 0;        //当前场景ID
    protected $sceneArray                   = 0;        //当前项目下所有场景，数据格式是   [ 前端ID => 数据库ID ]

    protected $data                         = [];       //存储传递过来的数据

    protected $returnDataArray              = [];       //嵌入数据格式化后数组

    public $is_cache=0;//是否是缓存

    public function __construct($data = [],$sceneId = 0,$sceneArray = [])
    {
        $this->data         = $data;
        $this->sceneId      = $sceneId;
        $this->sceneArray   = $sceneArray;
    }

    /**
     * 格式化数据
     */
    public function dataFormat(){

        $embedParent = new EmbedParent($this->sceneId,$this->sceneArray);
        foreach ($this->data as $key=>$value){

            $embed = $embedParent->format($value);
            $embed['attribute']['key'] = $key + 1;
            $this->returnDataArray[] = $embed;

            //$this->returnDataArray[] = $embedParent->format($value);
        }
    }

    /**
     * 返回格式化后的数据
     * @return array
     */
    public function getFormatData(){

        return $this->returnDataArray;
    }

    /**************************************** 处理返回编辑数据 ***************************************/
    public function editData(){

        $embedParent = new EmbedParent($this->sceneId,$this->sceneArray);
        foreach ($this->data as $key=>$value){

            $this->returnDataArray[] = $embedParent->editData($value);
        }
    }

    /**
     * 返回编辑所要数据
     * @return array
     */
    public function getEditData(){

        return $this->returnDataArray;
    }

    /******************************************** 处理返回编辑数据结束 **************************************/


    /****************************************************** 生成xml *****************************************/

    /**
     * 获取xml
     * @param $dom DOMDocument 对象
     * @param $sceneId
     * @param $sceneNode
     */
    public function makeXml($dom,$sceneId,$sceneNode)
    {

        $embedArray       = $this->selectEmbed($sceneId);

        if ($embedArray) {

            if (!empty($embedArray)) {

                //添加插件
                $this->addPlugin($dom);

                //添加嵌入热点
                $embedParent = new EmbedParent($this->sceneId, $this->sceneArray);
                foreach ($embedArray as $key=>&$value) {

                    $embedParent->makeXml($value,$sceneNode);
                }
            }
        }
    }

    /**
     * 获取嵌入
     * @param $sceneId
     * @return mixed
     */
    private function selectEmbed($sceneId)
    {
        $embed = [];
        if ($this->is_cache==0 && empty($this->data)) {
            $where['scene_id']  = $sceneId;

            $embedRepository = new EmbedRepository(app());
            $embedList = $embedRepository->lists($where);
            if ($embedList->isNotEmpty()) {
                $embed = $embedList->toArray();
            }
            return $embed;
        }

        return $this->data;

    }


    /**
     * 添加插件
     * @param $dom
     */
    private function addPlugin($dom){

        static $plugin = 0;

        //应用插件
        if (!$plugin) {

            //先启用$dom
            $xmlService = new XmlService($dom);
            $xmlService->addXmlPlugin('embed/embed');
            $xmlService->addXmlPlugin('embed/videoplayer');
            $plugin = 1;
        }
    }

    /****************************************************** 生成xml结束**************************************/
}