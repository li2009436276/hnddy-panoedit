<?php


namespace Yjtec\PanoEdit\Services\Hotspot;

use Yjtec\PanoEdit\Repositories\Eloquent\HotspotRepository;
use Yjtec\PanoEdit\Repositories\Eloquent\HotspotPolygonsRepository;
use Yjtec\PanoEdit\Services\Hotspot\HotspotOrdinary;
use Yjtec\PanoEdit\Services\Hotspot\HotspotParent;
use Yjtec\PanoEdit\Services\Hotspot\HotspotPolygons;
use Yjtec\PanoEdit\Services\PublicService;
use Yjtec\PanoEdit\Services\XmlService;

class HotspotService extends PublicService
{

    protected $sceneId                      = 0;        //当前场景ID
    protected $sceneArray                   = 0;        //当前项目下所有场景，数据格式是   [ 前端ID => 数据库ID ]

    protected $data                         = [];       //存储传递过来的数据

    protected $hotspotOrdinaryArray         = [];       //普通热点数据格式化数组
    protected $hotspotPolygonsArray         = [];       //多边形热点数据格式化数组


    public function __construct($data = [],$sceneId = 0,$sceneArray = [])
    {
        $this->data         = $data;
        $this->sceneId      = $sceneId;
        $this->sceneArray   = $sceneArray;
    }

    /****************************************************** 格式化保存数据 *****************************************/

    /**
     * 格式化数据
     */
    public function dataFormat(){

        foreach ($this->data as $key=>$value) {

            if ($value['iconType'] == 3) {                                                                              //多边形热点

                $hotspotPolygons = new HotspotPolygons($value,$this->sceneId,$this->sceneArray);

                $formatRes = $hotspotPolygons->format();
                if (!empty($formatRes)) {

                    $this->hotspotPolygonsArray[] = $formatRes;
                }

            } else {

                if(isset($value['url'])){
                    if(!empty($value['url'])){
                        $hotspotOrdinary = new HotspotOrdinary($value, $this->sceneId, $this->sceneArray);                        //普通热点
                        $this->hotspotOrdinaryArray[] = $hotspotOrdinary->format();
                    }
                }
            }
        }

    }

    /****************************************************** 格式化保存数据结束 *****************************************/
    
    /****************************************************** 保存热点数据 *****************************************/
    public function saveHotspot(){

        //格式化热点数据
        $this->dataFormat();

        //处理普通热点
        $hotspotRepository = new HotspotRepository(app());
        $res = $hotspotRepository->updateOrCreate($this->hotspotOrdinaryArray,$this->sceneId);
        if (!$res) {

            return false;
        }

        //处理多边形热点
        $hotspotPolygonsRespository = new HotspotPolygonsRepository(app());
        $res = $hotspotPolygonsRespository->updateOrCreate($this->hotspotPolygonsArray,$this->sceneId);
        if (!$res) {

            return false;
        }
            
        
        return true;

    }

    /**
     * 天空地面遮罩
     * @param $scenedId
     * @param $data
     * @return mixed
     */
    public function savEditMaskHospot() 
    {
        $hotspotRepository = new HotspotRepository(app());
        $res = $hotspotRepository->savEditMaskHospot($this->data,$this->sceneId);
        if (!$res) {

            return false;
        }
        return true;                
    }


    /****************************************************** 保存热点数据结束 *****************************************/


    /****************************************************** 生成xml *****************************************/

    /**
     * 获取热点xml
     */
    public function makeXml($projectId,$sceneIndex){

        $hotspot['hotspot'] = [];
        $hotspot['mask'] = [];

        //获取场景ID
        $sceneId = $this->getSceneId($projectId,$sceneIndex);

        //普通热点
        $hotspotOrdinaryArray = $this->selectHotspotOrdinary($sceneId);
        if ($hotspotOrdinaryArray) {

            if ($hotspotOrdinaryArray) {
                foreach ($hotspotOrdinaryArray as $key=>&$value) {

                    $hotspotOrdinary = new HotspotOrdinary($value,$this->sceneId,$this->sceneArray);                        //普通热点
                    $hotspot['hotspot'][] = $hotspotOrdinary->makeXml();
                }
            }
        }
        //获取多边形热点，生成xml
        $hotspotPolygonsArray = $this->selectHotspotPolygons($sceneId);
        if ($hotspotPolygonsArray) {

            foreach ($hotspotPolygonsArray as $key=>&$value) {

                $hotspotPolygons = new HotspotPolygons($value, $this->sceneId, $this->sceneArray);
                $hotspot['hotspot'][] = $hotspotPolygons->makeXml();
            }
        }

        //遮照
        $hotspot['mask'] = $this->getMask($sceneId);

        return $hotspot;
    }

    /**
     * 获取普通热点
     * @param $sceneId
     * @return mixed
     */
    private function selectHotspotOrdinary($sceneId)
    {
        $hotspot = [];
        if (empty($this->data)) {

            $where['scene_id']  = $sceneId;

            $hotspotRepository = new HotspotRepository(app());
            $hotspotList = $hotspotRepository->lists($where);
            if ($hotspotList->isNotEmpty()) {
                $hotspot = $hotspotList->toArray();
            }
            return $hotspot;
        }

        $hotspot = $this->data['hotspot'] ?? [];
        return $hotspot;

    }

    /**
     * 获取多边形热点
     * @param $sceneId
     * @return mixed
     */
    private function selectHotspotPolygons($sceneId)
    {
        $hotspot = [];
        if (empty($this->data)) {

            $where['scene_id']  = $sceneId;

            $hotspotPolygonsRepository = new HotspotPolygonsRepository(app());
            $hotspotList = $hotspotPolygonsRepository->lists($where);
            if ($hotspotList->isNotEmpty()) {
                $hotspot = $hotspotList->toArray();
            }
            return $hotspot;
        }

        $hotspot = $this->data['hotspot_polygons'] ?? [];
        return $hotspot;
    }

    /**
     * 处理遮照
     * @param $sceneId
     * @return array|mixed
     */
    public function getMask($sceneId){

        $hotspot = [];
        if (empty($this->data)) {

            $where['scene_id']  = $sceneId;

            $hotspotRepository = new HotspotRepository(app());
            $hotspotList = $hotspotRepository->maskLists($where,['id','url','title','action_data','img','icon_type']);
            if ($hotspotList->isNotEmpty()) {

                foreach ($hotspotList as $key=>$value){
                    $maskData = [];
                    if(!empty($value['action_data'])){
                        $actionData = json_decode($value['action_data'], true);
                        if(isset($actionData['scale'])){
                            $maskData['scale'] = $actionData['scale'];
                        }
                        if(isset($actionData['distorted'])){
                            $maskData['distorted'] = $actionData['distorted'];
                        }
                    }
                    $hotspot[$key] = $maskData;
                    $hotspot[$key]['id']    = $value['id'];
                    $hotspot[$key]['url']    = hotspotUrlComposer($value['url']);
                    $hotspot[$key]['atv']    = $value['atv'];
                    $hotspot[$key]['ath']    = $value['ath'];
                    
                }
            }
            return $hotspot;
        }

        $hotspot = $this->data['mask'] ?? [];
        return $hotspot;
    }

    /**
     * 因为是异步，sceneId可能是个字符串
     * @param $project
     * @param $sceneIndex
     */
    private function getSceneId($project,$sceneIndex){

        if (empty($this->data)) {

            $sceneInterface = resolve('Yjtec\PanoEdit\Repositories\Contracts\SceneInterface');
            $sceneInfo = $sceneInterface->index(['project_id'=>$project,'key'=>$sceneIndex]);
            if (!empty($sceneInfo)){

                return $sceneInfo['id'];
            }
        }

        return $this->sceneId;
    }
}