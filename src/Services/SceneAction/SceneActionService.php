<?php


namespace Yjtec\PanoEdit\Services\SceneAction;


use Yjtec\PanoEdit\Repositories\Eloquent\SceneActionRepository;
use Yjtec\PanoEdit\Services\PublicService;
use lib\app\app;
use Yjtec\PanoEdit\Services\XmlService;

class SceneActionService extends PublicService
{
    protected $sceneId                      = 0;        //当前场景ID
    protected $sceneArray                   = 0;        //当前项目下所有场景，数据格式是   [ 前端ID => 数据库ID ]

    protected $data                         = [];       //存储传递过来的数据

    protected $returnDataArray              = [];       //嵌入数据格式化后数组

    private $sceneActionParent              = null;     //场景功能

    public function __construct($data = [],$sceneId = 0,$sceneArray = [])
    {
        $this->data         = $data;
        $this->sceneId      = $sceneId;
        $this->sceneArray   = $sceneArray;

        $this->sceneActionParent = new SceneActionParent($this->sceneId,$this->sceneArray);
    }

    /**
     * 数据保存
     */
    public function savEditSceneAction(){

        if ($this->data) {

            //格式化数据
            $this->dataFormat();
            foreach ($this->returnDataArray as $key=>$value){

                 $res = $this->sceneActionParent->savEditSceneAction($value);
                 if ($res === false) {

                     return false;
                 }
            }

        }
       return true;
    }

    /**
     * 格式化数据
     */
    public function dataFormat(){


        foreach ($this->data as $key=>$value){

            $this->returnDataArray[] =  $this->sceneActionParent->format($key,$value);
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


        foreach ($this->data as $key=>$value){

            $this->returnDataArray = $this->sceneActionParent->editData($value);
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
     * @param $sceneId
     * 
     */
    public function makeXml()
    {
        if (empty($this->data)) {
            $ActionList       = $this->selectAction(['scene_id'=>$this->sceneId],['scene_id','action_id','value','key']);
            if ($ActionList->isNotEmpty()) {
                $data = $ActionList->toArray();
            }
        } else {

            $data = $this->data['scene_action'];
        }
        
        if (!empty($data)) {
            
            return $this->sceneActionParent->makeXml($data);
            
        }

        return [];
    }

    /**
     * 获取场景功能
     * @param $field
     * @param $where
     * @return mixed
     */
    private function selectAction($where,$field = '*'){

        $embedRepository = new SceneActionRepository(app());
        return $embedRepository->lists($where,$field);
    }

    /****************************************************** 生成xml结束**************************************/
}