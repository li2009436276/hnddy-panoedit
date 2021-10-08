<?php


namespace Yjtec\PanoEdit\Services\Radar;


use Yjtec\PanoEdit\Services\XmlService;

class RadarParent
{

    private $projectId      = 0;    //项目ID
    private $data           = [];   //当前数据
    private $sceneIds       = [];   //场景ID对应关系
    private $radarInterface = null; //沙盘
    static $xmlService      = null;

    public function __construct($projectId,$data = [],$sceneIds = [])
    {
        $this->projectId    = $projectId;
        $this->data         = $data;
        $this->sceneIds     = $sceneIds;
        $this->radarInterface = resolve('Yjtec\PanoEdit\Repositories\Contracts\RadarContainerInterface');
    }

    /************************************* 保存 ***************************/
    public function saveRadar(){

        $res = $this->radarInterface->saveEditRadarContainer($this->projectId,$this->sceneIds,$this->data);
        return $res;
    }

    /************************************ 获取数据 ************************/
    public function editData(){

        $res = $this->radarInterface->radarInfo(['foreign_keys_id' => $this->projectId]);
        if ($res) {

            $data[$res['front_key']]['container']         = json_decode($res['container'],true);
            $data[$res['front_key']]['bg_img']            = json_decode($res['bg_img'],true);
            $data[$res['front_key']]['spots']             = $res['spots'];

            return $data;
        }

        return $res;
    }

    /************************************ 生成xml ************************/

    public function makeXml($dom,$isCache,$cacheData){

        if (!self::$xmlService) {

            self::$xmlService = new XmlService($dom);
        }

        if (!$isCache) {

            $cacheData = $this->radarInterface->radarInfo(['foreign_keys_id' => $this->projectId]);
        }

        if ($cacheData) {

            $this->addRadar($cacheData);
        }

        return $cacheData;
    }

    /**
     * 添加平面导航
     * @param $radarInfo
     * @return mixed
     */
    private function addRadar($radarInfo){

        $krpnaoNode = self::$xmlService->getElementsByTagName("krpano")[0];

        //容器
        $bgImgInfo                      = json_decode($radarInfo['bg_img'],true);
        $containerLayer                 = self::$xmlService->addLayer($krpnaoNode,json_decode($radarInfo['container'],true));
        $bgImgInfo['url']               = \Storage::url(env('APP_ENV').$bgImgInfo['url']);
        $bgImgLayer                     = self::$xmlService->addLayer($containerLayer,$bgImgInfo);

        $this->addRadarPlugin($bgImgLayer);
        if (!empty($radarInfo['spots'])) {

            foreach ($radarInfo['spots'] as $key=>$value) {

                $spotData['onclick']   = 'loadscene(scene_'.$value['scene_id'].')';
                $spotData['edge']      = $value['align'];
                $spotData['url']       = \Storage::url(env('APP_ENV').env('BLUE_POINT_DEFAULT'));
                $spotData['x']         = $value['x'] - 11;
                $spotData['y']         = $value['y'] - 11;
                $spotData['name']      = $value['name'];
                $spotData['angle']     = $value['angle'];
                $spotData['align']     = $value['align'];

                self::$xmlService->addLayer($bgImgLayer,$spotData);
            }
        }

        $radarEventNode = self::$xmlService->createElement('events');
        self::$xmlService->setAttribute($radarEventNode,['name'=>'raderevent','onremovepano'=>'set(plugin[radar].visible,false);set(plugin[activespot].visible,false);','keep'=>'true']);
        self::$xmlService->appendChild($krpnaoNode,$radarEventNode);

    }

    /**
     * 添加沙盘插件
     */
    private function addRadarPlugin($bgImgLayer){

        $radar = [
            'name'      => "radar",
            'url'       => \Storage::url("/krpano/plugins/radar.swf"),
            'alturl'    => \Storage::url("/krpano/plugins/radar.js"),
            'zorder'    => "1",
            'align'     => "topleft",
            'edge'      => "center",
            'heading'   => "0",
            'editmode'  => "true",
            'parent'    => "mapbar",
            'x'         => 0,
            'y'         => 0,
            'linecolor' => "0",
            'fillcolor' => "0xFF9900",
            'scale'     => "0.5",
            'visible'   => "false",
        ];

        $radarPluginNode = self::$xmlService->createElement('plugin');
        self::$xmlService->setAttribute($radarPluginNode,$radar);
        self::$xmlService->appendChild($bgImgLayer,$radarPluginNode);

        $activeSpotRadar = [
            'name'      => "activespot",
            'url'       => \Storage::url(env('APP_ENV').env('RED_POINT_DEFAULT')),
            'keep'      => "true",
            'align'     => "center",
            'edge'      => "center",
            'x'         => 0,
            'y'         => 0,
            'visible'   => "false",
            'zorder'    => "6",
        ];

        $activeSpotRadarNode = self::$xmlService->createElement('plugin');
        self::$xmlService->setAttribute($activeSpotRadarNode,$activeSpotRadar);
        self::$xmlService->appendChild($bgImgLayer,$activeSpotRadarNode);

    }

    /************************************ 生成xml结束 ************************/
}