<?php


namespace Yjtec\PanoEdit\Services\FileService;


use Yjtec\PanoEdit\Services\FileService\FileCategory\OrdinaryFile;
use Yjtec\PanoEdit\Services\FileService\FileCategory\RingsFile;
use Yjtec\PanoEdit\Services\FileService\FileCategory\Model3DFile;
use Yjtec\PanoEdit\Services\PublicService;

class FileService extends PublicService
{
    /**
     * @param $file
     * @param $rootDir
     * @return array
     */
    public function upload($file,$rootDir,$applyType,$id = 0)
    {

        //获取图片应用类型
        switch ($applyType) {

            case 1 : {  //普通图片

                $ordinaryFile = new OrdinaryFile($rootDir);
                return $ordinaryFile->upload($file);
            }

            case 2: {   //环物图片

                $ordinaryFile = new RingsFile($rootDir);
                return $ordinaryFile->upload($file);
            }

            case 3: {   //3d环物/倾斜摄影

                $model3d = new Model3DFile($rootDir,$id);
                return $model3d->upload($file);
            }
        }
    }
}