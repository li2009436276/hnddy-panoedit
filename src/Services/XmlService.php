<?php


namespace Yjtec\PanoEdit\Services;

class XmlService extends PublicService
{
    static $dom = null;

    public function __construct($dom = NULL)
    {
        if ($dom && (!self::$dom)) {

            self::$dom = $dom;
        }

        if (!self::$dom) {

            self::$dom  = new \DOMDocument('1.0', 'utf-8');
        }

    }

    /**
     *
     * 获取dom对象节点
     * @return \DOMDocument|null
     */
    public function getElementsByTagName($name){

        return self::$dom->getElementsByTagName($name);
    }

    /**
     * 根据ID获取节点
     * @param $id
     * @return \DOMElement|null
     */
    public function getElementId($id){

        return self::$dom->getElementById($id);
    }

    /**
     * 创建Xml节点
     * @param $name
     * @return object
     */
    public function createElement($name){

        return self::$dom->createElement($name);
    }

    /**
     * 绑定父子关系
     * @param $parent
     * @param $children
     */
    public function appendChild($parent,$children) {

        $parent->appendChild($children);
    }

    /**
     * 设置属性
     * @param $node
     * @param $attributes
     */
    public function setAttribute($node,$attributes){

        foreach ($attributes as $key=>$value) {

            if (is_array($value)) {

                $this->setAttribute($value);
            }

            $node->setAttribute($key,$value);
        }
    }

    /**
     * 获取节点属性
     * @param $node
     * @param $attribute
     * @return mixed
     */
    public function getAttribute($node,$attribute){

        return $node->getAttribute($attribute);
    }

    /**
     * 设置节点值
     * @param $node
     * @param $value
     */
    public function setNodeValue($node,$value){

        $node->nodeValue = $value;
    }

    /**
     * 添加layer标签
     * @param $node
     * @param $attribute
     * @return object
     */
    public function addLayer($node,$attribute){
        $layerNode = $this->createElement('layer');
        $this->setAttribute($layerNode,$attribute);
        $this->appendChild($node,$layerNode);
        return $layerNode;
    }

    /**
     * 添加插件
     * @param $plugin
     */
    public function addXmlPlugin($plugin){

        $krpnao = $this->getElementsByTagName("krpano")[0];

        $includeNode = self::$dom->createElement('include');

        if (env('APP_ENV') != 'local'){

            $pluginUrl = \Storage::url('/krpano/plugin/'.$plugin.'.xml?v='.env('XML_PLUGIN_VERSION','1.0.1'));

            //去除https
            if (request()->header(env('SERVER_TYPE','x-forwarded-proto')) == 'https') {

                $pluginUrl = str_replace('http:','',$pluginUrl);
            }

            $includeNode->setAttribute('url',$pluginUrl);

        } else {

            $includeNode->setAttribute('url',env('LOCAL_XML_PATH').'/krpano/plugin/'.$plugin.'.xml?v='.env('XML_PLUGIN_VERSION','1.0.1'));
        }

        $krpnao->appendChild($includeNode);
    }
}