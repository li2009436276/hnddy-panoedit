<?php


namespace Yjtec\PanoEdit\Services\FileService\Upload;


use Illuminate\Support\Facades\Storage;

class FileSingle extends FileUpload
{

    /**
     * 上传文件
     * @return array
     */
    public function upload(){

        $saveName = $this->fileCategory->getPath($this->uploadFile).$this->uploadFile->getClientOriginalExtension();

        $res = Storage::disk($this->disk)->put(
            env('APP_ENV').$saveName,
            file_get_contents($this->uploadFile->getRealPath())
        );

        if ($res) {

            $this->returnData['path'] = $saveName;
            $this->returnData['name'] = str_replace('.'.$this->uploadFile->getClientOriginalExtension(),'',$this->uploadFile->getClientOriginalName());
            $this->returnData['size'] = $this->uploadFile->getClientSize();
            $this->returnData['ext'] = $this->uploadFile->getClientOriginalExtension();
            if (strpos($this->uploadFile->getMimeType(),'image') !== false) {

                $this->returnData['width']     = getimagesize($this->uploadFile->getRealPath())[0];
                $this->returnData['height']    = getimagesize($this->uploadFile->getRealPath())[1];
            }

        }

        return $this->returnData;

    }
}