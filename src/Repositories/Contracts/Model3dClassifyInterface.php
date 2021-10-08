<?php 

namespace Yjtec\PanoEdit\Repositories\Contracts;


interface Model3dClassifyInterface
{
	//删除分类
	public function delete($where);

	/**
     * 分类列表
     * @param $where
     * @param string $field
     * @return mixed
     */
    public function lists($where, $field = '*', $orderSort = ['created_at' => 'desc']);
}

?>