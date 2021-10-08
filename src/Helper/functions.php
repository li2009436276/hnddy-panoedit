<?php

use Yjtec\Support\Str;

/**
 * 获取图片宽高
 * @param $url
 * @return array
 */
function getImgWhComposer($url, $path = null){

    $imgWh = [];
    try {
        if(!empty($path)){
            $file = resolve('Yjtec\PanoEdit\Repositories\Contracts\FileInterface');
            $fileInfo = $file->index(['path' => $path, 'type' => 1]);
            if(collect($fileInfo)->isNotEmpty()){
                $imgWh[]    = $fileInfo['width'];
                $imgWh[]    = $fileInfo['height'];
            }
        }
    }catch (Exception $exception){
        logger('获取图片存储宽高失败', [$exception]);
    }
    if(empty($imgWh)){
        $res        = getimagesize($url);
        $imgArray   = explode(' ',$res[3]);
        $imgWh[]    = explode('"',$imgArray[0])[1];
        $imgWh[]    = explode('"',$imgArray[1])[1];
    }
    return $imgWh;
}

/**
 * rgba 转十六进制
 * @param $int
 * @return string
 */
function integerToHex($int){

    $hex = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F');

    $one  = $int % 16;
    $two  = ($int / 16);
    return $hex[$two].$hex[$one];

}

/**
 * 获取随机字符串
 * @param $length
 * @return string
 */
function randomStr($length) {
    $returnStr='';
    $pattern = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
    for($i = 0; $i < $length; $i ++) {
        $returnStr .= $pattern {mt_rand ( 0, 51 )}; //生成php随机数
    }
    return $returnStr;
}

/**
 * 匹配样式
 * @param $data
 * @return mixed
 */
function strCss($data){
    foreach ($data as $key=>$value){

        if ($key != Str::cc2($key)) {
            $data[Str::cc2($key)] = $value;
            unset($data[$key]);
        }
    }
    return $data;
}

/**
 * 节点属性值为多值
 * @param $data
 * @return string
 */
function attributeString($data){

    $str = '';

    foreach ($data as $key=>$value){
        $str .= $key.':'.$value.';';
    }

    return $str;
}



/**
 * 获取oss路径
 * @param $path
 * @return array
 */
function getStorageUrl($path){

    return ['path' => $path, 'url' => \Storage::url($path)];
}

/**
 * 处理热点图片,兼容智慧城市
 * @param $url
 * @return string
 */
function hotspotUrlComposer($url){

    //处理图片问题
    if(strpos(' '.$url,'http://') > 0 || strpos(' '.$url,'https://') > 0){

    } else if (strpos(' '.$url,'/case') || (strpos(' '.$url,'/Public') > 0) ) {

        //主要为了保持文件夹都在img下
        $url   = 'https://pano.360vrsh.com'.substr($url,6);
    } else  {

        $url = \Storage::url(env('APP_ENV').$url);
    }

    return $url;
}
