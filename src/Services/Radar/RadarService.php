<?php


namespace Yjtec\PanoEdit\Services\Radar;


use Yjtec\PanoEdit\Services\PublicService;

class RadarService extends PublicService
{

    private $radarParent = null;
    public function __construct($projectId,$data = [],$sceneIds = [])
    {

        $this->radarParent  = new RadarParent($projectId,$data,$sceneIds);
    }

    /************************************* 保存 ***************************/
    public function saveRadar(){

        return $this->radarParent->saveRadar();
    }

    /******************************************** 数据获取 *****************************/
    public function editData(){

        return $this->radarParent->editData();
    }

    /******************************************* 生成xml ******************************/
    public function makeXml($dom,$isCache,$cacheData){

        return $this->radarParent->makeXml($dom,$isCache,$cacheData);
    }
    /******************************************* 生成xml结束 *****************************/

}