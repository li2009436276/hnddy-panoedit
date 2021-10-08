<?php


namespace Yjtec\PanoEdit\Services\Embed;


use Yjtec\PanoEdit\Services\Embed\EmbedType\OrderEmbed;
use Yjtec\PanoEdit\Services\Embed\EmbedType\PicEmbed;
use Yjtec\PanoEdit\Services\Embed\EmbedType\TextEmbed;
use Yjtec\PanoEdit\Services\Embed\EmbedType\VideoEmbed;

class EmbedParent
{
    static $key = 1;          //key主要用户热点排序
    
    protected $sceneId = 0;          //当前场景
    protected $sceneArray = [];         //当前项目下所有场景，数据格式是   [ 前端ID => 数据库ID ]
    
    private $formatData = [];         //格式化后数组
    
    public function __construct($sceneId, $sceneArray)
    {
        $this->sceneId = $sceneId;
        $this->sceneArray = $sceneArray;
    }
    
    
    /**
     * 格式化数据
     *
     * @return array
     */
    public function format($data)
    {
        
        switch ($data['type']){
            
            //文字
            case 1:
            {
                
                $this->formatData['attribute'] = (new TextEmbed($data, self::$key, $this->sceneId, $this->sceneArray))->format()
                                                                                                                      ->getReturnData();
                break;
            }
            
            //图片
            case 2 :
            {
                
                $picEmbed = new PicEmbed($data, self::$key, $this->sceneId, $this->sceneArray);
                
                $this->formatData['attribute'] = $picEmbed->format()
                                                          ->getReturnData();
                
                $this->formatData['img'] = $picEmbed->actionDataImg();
                
                break;
            }
            
            //序列图
            case 3 :
            {
                
                $this->formatData['attribute'] = (new OrderEmbed($data, self::$key, $this->sceneId, $this->sceneArray))->format()
                                                                                                                       ->getReturnData();
                break;
            }
            
            //视频
            case 4 :
            {
                
                $this->formatData['attribute'] = (new VideoEmbed($data, self::$key, $this->sceneId, $this->sceneArray))->format()
                                                                                                                       ->getReturnData();
                break;
            }
        }
        
        self::$key++;
        return $this->formatData;
    }
    
    
    /**************************************** 处理返回编辑数据 ***************************************/
    public function editData($data)
    {
        
        switch ($data['type']){
            
            //文字
            case 1:
            {
                
                $this->formatData = (new TextEmbed($data, self::$key, $this->sceneId, $this->sceneArray))->editDataFormat()
                                                                                                         ->getReturnData();
                break;
            }
            
            //图片
            case 2 :
            {
                
                $picEmbed = new PicEmbed($data, self::$key, $this->sceneId, $this->sceneArray);
                
                $this->formatData = $picEmbed->editDataFormat()
                                             ->getReturnData();
                
                break;
            }
            
            //序列图
            case 3 :
            {
                
                $this->formatData = (new OrderEmbed($data, self::$key, $this->sceneId, $this->sceneArray))->editDataFormat()
                                                                                                          ->getReturnData();
                break;
            }
            
            //视频
            case 4 :
            {
                
                $this->formatData = (new VideoEmbed($data, self::$key, $this->sceneId, $this->sceneArray))->editDataFormat()
                                                                                                          ->getReturnData();
                break;
            }
        }
        
        return $this->formatData;
    }
    
    /**************************************** 处理返回编辑数据结束 ***********************************/
    
    /**************************************** 生成xml ************************************************/
    
    /**
     * 追加节点
     *
     * @param $data
     * @param $sceneNode 父节点 $sceneNode
     */
    public function makeXml($data, $sceneNode)
    {
        
        $embedNode = NULL;
        
        switch ($data['type']){
            
            //文字
            case 1:
            {
                if(!config('env.is_f2e', false)){
                    if(isset($data['action_data'])){
                        if(!empty($data['action_data'])){
                            $embedAction = json_decode($data['action_data'], true);
                            $pattern = '/(\n)+/';
                            $replacement = '[br/]';
                            if(isset($embedAction['text'])){
                                $subject = $embedAction['text'];
                                $embedAction['text'] = preg_filter($pattern, $replacement, $subject)?:$subject;
                                $data['action_data'] = json_encode($embedAction);
                            }
                        }
                    }
                }
                $embedNode = (new TextEmbed($data, self::$key, $this->sceneId, $this->sceneArray))->makeXml($sceneNode);
                break;
            }
            
            //图片
            case 2:
            {
                
                $picEmbed = new PicEmbed($data, self::$key, $this->sceneId, $this->sceneArray);
                $embedNode = $picEmbed->makeXml($sceneNode);
                $picEmbed->imgForXml($embedNode);
                break;
            }
            
            //序列图
            case 3:
            {
                
                $embedNode = (new OrderEmbed($data, self::$key, $this->sceneId, $this->sceneArray))->makeXml($sceneNode);
                break;
            }
            
            //视频
            case 4:
            {
                
                $embedNode = (new VideoEmbed($data, self::$key, $this->sceneId, $this->sceneArray))->makeXml($sceneNode);
                break;
            }
        }
    }
    
    /**************************************** 生成xml结束 *******************************************/
}