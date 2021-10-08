<?php


namespace Yjtec\PanoEdit\Services\FileService\FileCategory;


use Yjtec\PanoEdit\Services\FileService\Upload\FileArray;
use Yjtec\PanoEdit\Services\FileService\Upload\FileSingle;

class FileParent
{

    private $disk;
    private $rootDir;   //文件存储根目录
    private $path;      //文件保存完整路径

    public function __construct($dir)
    {
        $this->rootDir = $dir;
    }

    /**
     * 生成文件名称
     * @return string
     */
    public function createFileName(){
        $str = 'abcdefghijklmnopqrstuvwxyz123456789';
        return date('His').substr(str_shuffle($str),4,7);
    }

    /**
     * 返回日期目录
     * @return string
     */
    public function getDatePath(){

        return  $this->rootDir.'/'.date('Y').'/'.date('m').'/'.date('d');
    }

    /**
     * 返回对应的oss地址
     * @param string $ext
     * @param string $path
     * @param string $fileName
     * @return mixed
     */
    public function createPath($ext = '',$path = '',$fileName = ''){

        //加上日期目录
        $this->path = $this->getDatePath();

        //加上自定目录
        $this->path = $path ? $this->path.$path.'/' : $this->path.'/';

        //加上文件名称
        if (!$fileName) {

            $this->path = $this->path.$path.$this->createFileName().'.'.$ext;
        } else {

            $this->path = $this->path.$path.$fileName.'.'.$ext;
        }

        return $this->path;
    }

    /**
     * 上传文件
     * @param $file
     * @return mixed
     */
    public function upload($file){

        if (is_array($file)) {

            $fileArray = new FileArray($this,$file);
            $files = $fileArray->upload();        

        } else {

            $fileSingle = new FileSingle($this,$file);
            $files =  $fileSingle->upload();
        }

        return $this->storeFile($files,request());
    }

    /**
    * 存储文件到file表
    *
    *
    */
    public function storeFile($files,$request){

        
        $userId     = $request->user_id;
        $appId      = $request->app_id ? : 1;
        $type       = $request->type;
        $category   = $request->category ? : 1;
        $classifyId = $request->classify_id ? $request->classify_id : 0;
        $applyType  = $request->apply_type ? : 1;

        $fileInterface = resolve('Yjtec\PanoEdit\Repositories\Contracts\FileInterface');

        if (count($files) == count($files, 1)) {

            $addRes =  $fileInterface->create($files,$appId,$userId,$classifyId,$type,$category,$applyType);

            $files['url'] = \Storage::url(env('APP_ENV').$files['path']);
        } else {

            $addRes =  $fileInterface->insert($files,$appId,$userId,$classifyId,$type,$category,$applyType);
            foreach ($files as &$value) {

                $value['url'] = \Storage::url(env('APP_ENV').$value['path']);
            }
        }

        return $files;

    }
}