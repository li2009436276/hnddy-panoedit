<?php

namespace Yjtec\PanoEdit\Resources\Rings;


use Yjtec\PanoEdit\Resources\BaseCollection;

class RingsListsCollection extends BaseCollection
{
    public function toArray($request)
    {
        return [
            'data' => RingsListsResource::collection($this)
        ];
    }
}