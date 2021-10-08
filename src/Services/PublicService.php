<?php
/**
 * Created by PhpStorm.
 * User: hd
 * Date: 2019-04-01
 * Time: 19:32
 */

namespace Yjtec\PanoEdit\Services;


class PublicService
{
    public $ErrorCode;

    public $ErrorMsg;

    public function getErrorCode(){
        return $this->ErrorCode;
    }
    public function getErrorMsg(){
        return $this->ErrorMsg;
    }
}