<?php


namespace Yjtec\PanoEdit\Services\SandTable;


use Yjtec\PanoEdit\Services\XmlService;

class SandTableParent
{

    private $projectId      = 0;    //项目ID
    private $data           = [];   //当前数据
    private $sceneIds       = [];   //场景ID对应关系
    private $sandTableInterface = null; //沙盘

    //layer容器
    private $layers         = [];

    static $xmlService      = null;


    public function __construct($projectId,$data = [],$sceneIds = [])
    {
        $this->projectId    = $projectId;
        $this->data         = $data;
        $this->sceneIds     = $sceneIds;
        $this->sandTableInterface = resolve('Yjtec\PanoEdit\Repositories\Contracts\SandTableInterface');
    }

    /************************************* 保存 ***************************/
    public function saveTable(){

        $res = $this->sandTableInterface->saveEditSandTable($this->projectId,$this->data,$this->sceneIds);
        return $res;
    }

    /************************************ 获取数据 ************************/
    public function editData(){

        $field = ['id', 'scene_id', 'bg_img', 'scene_name', 'heading', 'align', 'x', 'y', 'scene_thumb'];
        if (config('pano_edit.project') == 'yj_pano') {

            array_push($field,'domain');
        }
        
        $res = $this->sandTableInterface->sandTableInfo(['project_id' => $this->projectId],$field);

        return $res;
    }

    /************************************ 生成xml ************************/

    public function makeXml($dom,$isCache,$cacheData,&$bgMap){

        if (!self::$xmlService) {

            self::$xmlService = new XmlService($dom);

        }

        if (!$isCache) {

            $cacheData = $this->sandTableInterface
                ->sandTableInfo(['project_id' => $this->projectId],['id','scene_id','bg_img','scene_name','heading','align','x','y','width','height','scene_thumb'])
                ->toArray();
        }

        if ($cacheData) {

            $this->addSandTable($cacheData,$bgMap);
        }

    }

    /**
     * 添加平面导航
     * @param $sandTableInfo
     * @return mixed
     */
    private function addSandTable($sandTableInfo,&$bgMap){

        $mapAttribute = [
            'name'      => 'map_2',
            'layerType' => 'sandTable',
            'visible'   => 'false',
            'type'      => 'container',
            'zorder'    => '3',
            'width'     => '0',
            'height'    => '0',
            'bgcolor'   => '0xFFFFFF',
            'bgalpha'   => '0'
        ];
        $dom = new \DOMDocument();
        $dom->load(__DIR__.'/../../xml_plugin/sand_table/main.xml');

        //创建各个layer
        $layerNodes = $dom->getElementsByTagName('layer');
        $krpnao = self::$xmlService->getElementsByTagName("krpano")[0];

        foreach ($layerNodes as $key=>$value) {

            $layerNode = self::$xmlService->createElement('layer');
            $this->layers[$value->getAttribute('name')] = $layerNode;

            foreach ($value->attributes as $val) {

                if (strpos(' '.$val->value,'%SWFPATH%')) {
                    $val->value = str_replace('%SWFPATH%','',$val->value);
                    $val->value = \Storage::url(config('pano_edit.krpano_dir').$val->value);
                }
                $layerNode->setAttribute($val->name,$val->value);
            }

        }

        self::$xmlService->appendChild($this->layers['map_container'],$this->layers['map_zooming']);

        self::$xmlService->appendChild($this->layers['map_zooming'],$this->layers['map_narrow_button']);
        self::$xmlService->appendChild($this->layers['map_narrow_button'],$this->layers['map_narrow_icon']);
        self::$xmlService->appendChild($this->layers['map_narrow_button'],$this->layers['map_narrow_title']);

        self::$xmlService->appendChild($this->layers['map_zooming'],$this->layers['map_enlarge_button']);
        self::$xmlService->appendChild($this->layers['map_enlarge_button'],$this->layers['map_enlarge_icon']);
        self::$xmlService->appendChild($this->layers['map_enlarge_button'],$this->layers['map_enlarge_title']);

        self::$xmlService->appendChild($this->layers['map_zooming'],$this->layers['map_close_button']);
        self::$xmlService->appendChild($this->layers['map_close_button'],$this->layers['map_close_icon']);
        self::$xmlService->appendChild($this->layers['map_close_button'],$this->layers['map_close_title']);

        self::$xmlService->appendChild($this->layers['map_container'],$this->layers['map_container_mask']);
        self::$xmlService->appendChild($this->layers['map_container_mask'],$this->layers['scrollarea']);
        self::$xmlService->appendChild($this->layers['scrollarea'],$this->layers['map']);
        self::$xmlService->appendChild($krpnao,$this->layers['map_container']);

//        self::$xmlService->appendChild($krpnao,$this->layers['map_active']);
//        self::$xmlService->appendChild($krpnao,$this->layers['radar']);

        //添加插件
        self::$xmlService->addXmlPlugin('sandtable/map_action');

        $mapIndex = 1;
        foreach ($sandTableInfo as $key=>$value) {

            if (!empty($value['bg_img']) && $value['x'] !== '' && $value['x'] !== null) {
                if (array_key_exists($value['bg_img'],$bgMap) === false) {

                    $mapAttribute['name']   = 'map_'.$mapIndex;
                    $mapAttribute['width']  = $value['width'];
                    $mapAttribute['height'] = $value['height'];

                    $sandMapNode = self::$xmlService->addLayer($this->layers['map'],$mapAttribute);
                    $bgMap[$value['bg_img']] = [$mapIndex,$sandMapNode];
                    $mapIndex++;
                }

                //渲染点
                $spotAttribute['name'] = 'scene_' . $value['scene_id'];

                $spotAttribute['x'] = $value['x'] * ($value['width'] / 400.0);
                $spotAttribute['y'] = $value['y'] * ($value['width'] / 400.0);
                $spotAttribute['heading'] = $value['heading'];

                $spotAttribute['style'] = 'mapstyle';
                $spotAttribute['parent'] = $mapAttribute['name'];

                self::$xmlService->addLayer($bgMap[$value['bg_img']][1], $spotAttribute);

            }

        }

    }


    /************************************ 生成xml结束 ************************/
}