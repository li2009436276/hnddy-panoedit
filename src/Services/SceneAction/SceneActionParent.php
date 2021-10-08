<?php


namespace Yjtec\PanoEdit\Services\SceneAction;


use Yjtec\PanoEdit\Services\SceneAction\ActionType\MusicAction;
use Yjtec\PanoEdit\Services\SceneAction\ActionType\RollAction;
use Yjtec\PanoEdit\Services\XmlService;

class SceneActionParent
{
    protected $sceneId        = 0;          //当前场景
    protected $sceneArray     = [];         //当前项目下所有场景，数据格式是   [ 前端ID => 数据库ID ]

    private $formatData       = [];         //格式化后数组

    public function __construct($sceneId,$sceneArray)
    {
        $this->sceneId      = $sceneId;
        $this->sceneArray   = $sceneArray;
    }

    /**
     * 保存数据
     * @param $data
     * @return mixed
     */
    public function savEditSceneAction($data){

        switch ($data['key']) {
            case 'musicBg'      : break;                   //背景音乐
            case 'musicExplain' : break;                   //解说
            case 'layer'        : break;                   //layer
            case 'effect'       : break;                   //特效
            case 'roll'         : {                        //滚动字幕
                $res      = (new RollAction($data,$this->sceneId,$this->sceneArray))
                    ->savEditSceneAction();
                break;
            }
        }

        return $res;

    }

    /**
     * 格式化数据
     * @param $key
     * @param $data
     * @return array
     */
    public function format($key,$data)
    {

        switch ($key) {
            case 'musicBg'      : break;                   //背景音乐
            case 'musicExplain' : break;                   //解说
            case 'layer'        : break;                   //layer
            case 'effect'       : break;                   //特效
            case 'roll'         : {                        //滚动字幕
                $this->formatData         = (new RollAction($data,$this->sceneId,$this->sceneArray))
                    ->format()
                    ->getReturnData();
                break;
            }
        }

        return $this->formatData;
    }

    /**************************************** 处理返回编辑数据 ***************************************/

    /**
     * 返回编辑数据
     * @param $data
     * @return array
     */
    public function editData($data){

        switch ($data['key']) {
            case 'musicBg'      : break;                   //背景音乐
            case 'musicExplain' : break;                   //解说
            case 'layer'        : break;                   //layer
            case 'effect'       : break;                   //特效
            case 'roll'         : {                        //滚动字幕
                $this->formatData         = (new RollAction($data,$this->sceneId,$this->sceneArray))
                    ->editDataFormat()
                    ->getReturnData();
                break;
            }
        }

        return $this->formatData;
    }
    /**************************************** 处理返回编辑数据结束 ***************************************/

    /****************************************************** 生成xml *****************************************/
    /**
     * 追加节点
     * @param $data
     * @param $sceneNode 父节点 $sceneNode
     */
    public function makeXml($data){


        //添加滚动字幕
        $actionData    = [];

        foreach ($data as $key=>$value) {

            switch ($value['key']) {

                case 'layer':           break;
                case 'musicBg':
                {

                }
                case 'musicExplain':
                {

                    $actionData = array_merge($actionData, (new MusicAction($value, $this->sceneId, $this->sceneArray))->makeXml());
                    break;
                }
                case 'roll':
                {
                    $actionData['rolltxt'] = (new RollAction($value, $this->sceneId, $this->sceneArray))->makeXml();
                    break;
                }
            }
        }

        return $actionData;

    }
    /****************************************************** 生成xml结束**************************************/
}