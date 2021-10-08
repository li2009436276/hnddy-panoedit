<?php


namespace Yjtec\PanoEdit\Services\FileService\FileCategory;


/*use Yjtec\PanoEdit\Services\FileService\Upload\FileArray;
use Yjtec\PanoEdit\Services\FileService\Upload\FileSingle;*/

class OrdinaryFile extends FileParent
{


    public function __construct($dir)
    {
        parent::__construct($dir);

    }

    /**
     * 获取文件保存路径
     * @param $file
     * @return mixed
     */
    public function getPath($file)
    {
        $ext =  $file->getClientOriginalExtension();

        return parent::createPath($ext);
    }
}