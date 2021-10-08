<?php


namespace Yjtec\PanoEdit\Services\SandTable;


use Yjtec\PanoEdit\Services\PublicService;

class SandTableService extends PublicService
{

    private $sandTable = null;
    public function __construct($projectId,$data = [],$sceneIds = [])
    {

        $this->sandTable  = new SandTableParent($projectId,$data,$sceneIds);
    }

    /************************************* 保存 ***************************/
    public function saveTable(){

        return $this->sandTable->saveTable();
    }

    /******************************************** 数据获取 *****************************/
    public function editData(){

        return $this->sandTable->editData();
    }

    /******************************************* 生成xml ******************************/
    public function makeXml($dom,$isCache,$cacheData,&$bgMap){

        return $this->sandTable->makeXml($dom,$isCache,$cacheData,$bgMap);
    }
    /******************************************* 生成xml结束 *****************************/

}