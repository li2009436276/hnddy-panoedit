<?php


namespace Yjtec\PanoEdit\Resources;


use Illuminate\Http\Resources\Json\Resource;

class ErrorResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [

        ];
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function with($request)
    {

        list($code,$msg) = config("code.{$this->resource['code']}");
        return [
            'errcode' => $code,
            'errmsg' => $msg
        ];
    }


}