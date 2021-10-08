<?php 


namespace Yjtec\PanoEdit\Resources\Model3d;

use Yjtec\PanoEdit\Resources\BaseCollection;

/**
 * 
 */
class Model3dListsCollection extends BaseCollection
{
	
	public function toArray($request)
    {
        return [
            'data' => Model3dListsResource::collection($this)
        ];
    }
}
?>