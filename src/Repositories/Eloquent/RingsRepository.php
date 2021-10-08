<?php


namespace  Yjtec\PanoEdit\Repositories\Eloquent;


use  Yjtec\PanoEdit\Model\Rings;
use  Yjtec\PanoEdit\Repositories\Contracts\RingsInterface;
use App\Repositories\Rpc\Eloquent\UserRepository;
use Yjtec\Repo\Repository;

/**
 * Class FileRepository
 * @property Rings $model
 * @package App\Repositories\Eloquent
 */
class RingsRepository extends Repository implements RingsInterface
{

    public function model()
    {
        return Rings::class;
    }

    /**
     * npc后台列表
     * @param array $where
     * @param int $pageSize
     * @param string $field
     * @param array $sortField
     * @param array $search
     * @return mixed
     */
    public function lists($where, $pageSize = 10, $field = '*', $sortField = [], $search = [])
    {
        $query = $this->model
            ->select($field)
            ->when(!empty($search), function ($query) use ($search) {
                foreach ($search as $k => $v) {
                    if (!empty($k) && !empty($v)) {
                        switch ($k){
                            case 'phone':
                                $userRepository = resolve(UserRepository::class);
                                $res = $userRepository->getUserListByPhone($v);
                                $query->whereIn('user_id', $res['data']);
                                break;
                            case 'title':
                                $query->where('title', 'like', '%'.$v.'%');
                                break;
                            case 'user_id':
                                $query->where('user_id', '=', $v);
                                break;
                        }
                    }
                }
            })
            ->where($where);
        //排序
        if (!empty($sortField)) {
            foreach ($sortField as $k => $v) {
                $query = $query->orderBy($k, $v);
            }
        }
        $res = $query
            ->orderBy('status', 'asc')
            ->orderBy('id', 'desc')
            ->latest()
            ->withTrashed()
            ->paginate($pageSize);

        return $res;
    }


    /**
     * 编辑文件
     * @param $where
     * @param $data
     * @return mixed
     */
    public function edit($where, $data)
    {
        $res =$this->model->where($where)
            ->update($data);
        return $res;
    }

    /**
     * 创建环物
     * @param $data
     * @return mixed
     */
    public function create($data)
    {

        //存储环物路径
        if (is_array($data) && !empty($data['imgNumber']) && $data['imgNumber'] > 0) {

            $where['path']      = $data['imgUrl'];
            $where['user_id']   = request()->user_id;

            $res = $this->model->where($where)->first();
            if (collect($res)->isEmpty()) {

                $saveData = [
                    'user_id' => request()->user_id,
                    'path' => $data['imgUrl'],
                    'thumb' => $data['imgUrl'].'1.'.$data['imgType'],
                    'num' => $data['imgNumber'],
                    'status' => 1,
                    'ext' => $data['imgType'],
                ];

                $res = $this->model->create($saveData);

                return $res;
            }
        }

        return true;
    }
}
