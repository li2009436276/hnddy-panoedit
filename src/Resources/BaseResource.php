<?php


namespace Yjtec\PanoEdit\Resources;


use Illuminate\Http\Resources\Json\Resource;

class BaseResource extends Resource
{

    public function toArray($request)
    {
        return $this->resource;
    }

    public function with($request){

        list($code,$msg) = config("code.SUCCESS");
        return [
            'errcode' => $code,
            'errmsg' => $msg
        ];

    }
}