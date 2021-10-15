<?php 

namespace Yjtec\PanoEdit\Repositories\Eloquent;

use Yjtec\PanoEdit\Model\Model3dFile;
use Yjtec\PanoEdit\Repositories\Contracts\Model3dFileInterface;
use Yjtec\Repo\Repository;

/**
 * 
 */
class Model3dFileRepository extends Repository implements Model3dFileInterface
{
	
	public function model()
    {
        return Model3dFile::class;
    }

    //批量导入
    public function insert($data){
    	return $this->model->insert($data);
    }

    public function delete($where)
    {
        return $this->model->where($where)->delete();
    }
}
?>