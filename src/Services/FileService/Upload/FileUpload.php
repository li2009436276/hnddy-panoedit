<?php


namespace Yjtec\PanoEdit\Services\FileService\Upload;


class FileUpload
{

    protected $disk         = null;   //上传驱动
    protected $returnData   = [];     //返回数据
    protected $uploadFile   = null;   //上传分文
    protected $fileCategory = null;   //要上传文件分类

    public function __construct($fileCategory,$uploadFile,$disk = '')
    {
        $this->fileCategory = $fileCategory;
        $this->uploadFile   = $uploadFile;
        $this->disk         = $disk ? $disk : env('STORAGE_DISK','public');
    }
}