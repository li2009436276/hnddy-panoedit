<?php


namespace Yjtec\PanoEdit\Controllers;


use Yjtec\PanoEdit\Requests\Rings\RingsDisableRequest;
use Yjtec\PanoEdit\Requests\Rings\RingsListsRequest;
use Yjtec\PanoEdit\Resources\BaseResource;
use Yjtec\PanoEdit\Resources\ErrorResource;
use Yjtec\PanoEdit\Resources\Rings\RingsListsCollection;
use Yjtec\PanoEdit\Repositories\Contracts\RingsInterface;
use App\Repositories\Rpc\Eloquent\UserRepository;

class RingsController extends Controller
{
    private $rings;
    public function __construct(RingsInterface $rings)
    {
        $this->rings = $rings;
    }

    /**
     * @OA\Get(
     *     path="/npc/ringsLists",
     *     description="npc环物素材列表（后台管理接口）",
     *     operationId="RingsLists",
     *     tags={"Rings"},
     *     summary="npc环物素材列表（后台管理接口）",
     *     @OA\Parameter( description="分页", in="query", name="page", required=true,@OA\Schema( type="integer")),
     *     @OA\Parameter( description="分页数", in="query", name="page_size",  @OA\Schema( type="integer")),
     *     @OA\Parameter( description="筛选字段 title,phone,user_id", in="query", name="search_field",  @OA\Schema( type="string")),
     *     @OA\Parameter( description="筛选内容", in="query", name="search_value",  @OA\Schema( type="string")),
     *     @OA\Parameter( description="排序字段 id,status", in="query", name="sort_field",  @OA\Schema( type="string")),
     *     @OA\Parameter( description="排序方式 asc:正序  desc:倒叙", in="query", name="sort_rule",  @OA\Schema( type="string")),
     *     @OA\Parameter( description="状态：1：正常，2：禁用", in="query", name="rings_status",  @OA\Schema( type="integer")),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error",
     *         @OA\JsonContent(ref="#/components/schemas/Error")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="pet response",
     *         @OA\JsonContent(ref="#/components/schemas/npcRingsLists"),
     *     ),
     *
     *     security={
     *         {"token": {}}
     *     }
     * )
     * @param RingsListsRequest $ringsListsRequest
     * @param UserRepository $userRpcRepository
     * @return RingsListsCollection
     */
    public function lists(RingsListsRequest $ringsListsRequest, UserRepository $userRpcRepository)
    {
        //联表复杂查询条件
        $where = handleWhere($ringsListsRequest, ['status' => 'rings_status']);
        if ($ringsListsRequest->filled('search_field')){
            $search[$ringsListsRequest->search_field] = $ringsListsRequest->search_value;
        }

        $pageSize = $ringsListsRequest->page_size ?? 10;
        //排序
        $sortField = [];
        if($ringsListsRequest->has('sort_field')) {
            $sortField = [$ringsListsRequest->sort_field => $ringsListsRequest->input('sort_rule', 'asc')];
        }

        $field = ['id', 'title', 'user_id', 'thumb', 'path', 'created_at', 'updated_at', 'deleted_at', 'status', 'num', 'ext'];

        $ringsList = $this->rings->lists($where, $pageSize, $field, $sortField, $search ?? []);
        $userId = $ringsList->pluck('user_id')->implode(',');

        //获取用户信息,组合数据
        $userInfo = $userRpcRepository->getUserList($userId, ['id', 'nickname', 'head_img']);
        $userInfo = collect($userInfo['data'])->keyBy('id')->toArray();

        foreach ($ringsList as $k => $v) {
            if (isset($userInfo[$v->user_id])) {
                $v->user = $userInfo[$v->user_id];
            }
        }
        return new RingsListsCollection($ringsList);

    }

    /**
     * @OA\Put(
     *     path="/npc/rings/disable/{id}",
     *     description="npc禁用环物图（后台管理接口）",
     *     operationId="npcRingsDisable",
     *     tags={"Rings"},
     *     summary="npc禁用环物图（后台管理接口）",
     *     @OA\Parameter( description="id", in="path", name="id", required=true,@OA\Schema( type="integer")),
     *     @OA\Parameter( description="状态 正常:1,禁用:2", in="query", name="rings_status", required=true,@OA\Schema( type="integer")),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error",
     *         @OA\JsonContent(ref="#/components/schemas/Error")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="pet response",
     *         @OA\JsonContent(ref="#/components/schemas/Success"),
     *     ),
     *
     *     security={
     *         {"token": {}}
     *     }
     * )
     * @param RingsDisableRequest $disableRequest
     * @param int $id
     * @return BaseResource|ErrorResource
     */
    public function disable(RingsDisableRequest $disableRequest, int $id)
    {
        $res = $this->rings->edit(['id' => $id], ['status' => $disableRequest->rings_status]);
        if (!$res) {
            return new ErrorResource(['code' => 'FAIL']);
        }

        return new BaseResource([]);
    }

}