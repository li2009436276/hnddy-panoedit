<?php


namespace Yjtec\PanoEdit\Services\FileService\Upload;


use Illuminate\Support\Facades\Storage;

class FileArray extends FileUpload
{

    /**
     * 上传文件
     * @return array
     */
    public function upload(){

        foreach ($this->uploadFile as $key=>$value) {
            $fileArray = array();
            $saveName = $this->fileCategory->getPath($value);

            $res = Storage::disk($this->disk)->put(
                env('APP_ENV').$saveName,
                file_get_contents($value->getRealPath())
            );

            if ($res) {

                $fileArray['path'] = $saveName;
                $fileArray['name'] = str_replace('.'.$value->getClientOriginalExtension(),'',$value->getClientOriginalName());
                $fileArray['size'] = $value->getClientSize();
                $fileArray['ext']  = $value->getClientOriginalExtension();
                if (strpos($value->getMimeType(),'image') !== false) {

                    $fileArray['width']     = getimagesize($value->getRealPath())[0];
                    $fileArray['height']    = getimagesize($value->getRealPath())[1];
                }

                $this->returnData[] = $fileArray;
            }
        }

        return $this->returnData;
    }
}