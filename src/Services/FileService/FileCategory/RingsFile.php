<?php


namespace Yjtec\PanoEdit\Services\FileService\FileCategory;


/*use Yjtec\PanoEdit\Services\FileService\Upload\FileArray;
use Yjtec\PanoEdit\Services\FileService\Upload\FileSingle;*/

use MC;
class RingsFile extends FileParent
{
    private $type = 1;
    public function __construct($dir,$type = 1)
    {
        parent::__construct($dir);

        $this->type = $type;
    }


    /**
     * 获取文件保存路径
     * @param $file
     * @return mixed
     */
    public function getPath($file)
    {

        $uuid = request()->get('uuid');

        if (!$uuid) {

            tne('UUID_NOT_EXIST');
        }

        $path = $this->getDatePath().'/'.substr(md5($uuid), 8, 16);

        return $path.'/'.$file->getClientOriginalName();
    }

}