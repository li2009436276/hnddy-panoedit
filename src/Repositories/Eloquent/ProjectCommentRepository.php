<?php


namespace Yjtec\PanoEdit\Repositories\Eloquent;

use Yjtec\Repo\Repository;
use Yjtec\PanoEdit\Model\ProjectComment;
use Yjtec\PanoEdit\Repositories\Contracts\ProjectCommentInterface;

class ProjectCommentRepository extends Repository implements ProjectCommentInterface
{

    public function model()
    {
        return ProjectComment::class;
    }

    /**
     * 添加评论
     *
     * @param $data
     *
     * @return mixed
     */
    public function add($data)
    {
        $res = ProjectComment::create( $data );
        return $res;
    }

    /**
     * 获取前一百个评论
     * @param array  $where
     * @param int    $pageSize
     * @param string $field
     * @return mixed
     */
    public function lists($where = [], $pageSize = 100, $field = '*')
    {
        $res = $this->model->where( $where )
            ->select( $field )
            ->orderBy( 'created_at', 'desc' )
            ->limit($pageSize)
            ->get();
        return $res;
    }


    /**
     * 删除评论
     *
     * @param $where
     *
     * @return mixed|void
     */
    public function delete($where)
    {
        $res = ProjectComment::where( $where )->delete();
        return $res;
    }

    public function getUserProjectCommentList($where, $pageSize = 100)
    {
        return $this->model
            ->whereHas( 'project', function ($query) use ($where){
                $query->where( $where );
            } )
            ->with( 'project' )
            ->orderBy( 'created_at', 'desc' )
            ->paginate( $pageSize );
    }
}