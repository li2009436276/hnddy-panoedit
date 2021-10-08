<?php


namespace Yjtec\PanoEdit\Controllers;

use Yjtec\PanoEdit\Repositories\Contracts\ProjectInterface;
use Yjtec\PanoEdit\Resources\BaseCollection;
use Yjtec\PanoEdit\Repositories\Contracts\ProjectCommentInterface;
use Yjtec\PanoEdit\Requests\ProjectComment\CommentAddRequest;
use Illuminate\Http\Request;

class ProjectCommentController extends Controller
{

    private $projectCommentInterface;
    private $projectInterface;
    public function __construct(ProjectCommentInterface $projectCommentInterface,ProjectInterface $projectInterface){

        $this->projectCommentInterface = $projectCommentInterface;
        $this->projectInterface = $projectInterface;
    }

    /**
     * @OA\Post(
     *     path="/project/addComment",
     *     description="说一说",
     *     operationId="addComment",
     *     tags={"Project"},
     *     summary="点赞",
     *     @OA\Parameter( description="项目ID", in="query", name="project_id",required=true, @OA\Schema( type="string"),example="67"),
     *     @OA\Parameter( description="场景ID", in="query", name="scene_id",required=true, @OA\Schema( type="string"),example="67"),
     *     @OA\Parameter( description="x轴坐标", in="query", name="ath",required=true, @OA\Schema( type="string"),example="0.00000000"),
     *     @OA\Parameter( description="y轴坐标", in="query", name="atv",required=true, @OA\Schema( type="string"),example="0.00000000"),
     *     @OA\Parameter( description="昵称", in="query", name="nickanme",required=true, @OA\Schema( type="string"),example="哈哈哈哈哈"),
     *     @OA\Parameter( description="头像", in="query", name="headimage",required=true, @OA\Schema( type="string"),example="/headimg/1112233445/"),
     *     @OA\Parameter( description="token", in="header", name="token",  required=true,@OA\Schema( type="string"),example="9db5a82adb24e9f3cf4b639ec8c3f434"),
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
     *         {"ticket": {}}
     *     }
     * )
     */
    public function addComment(CommentAddRequest $request)
    {
        //请求新token
        $userInfo = $this->getUserInfo();

        $data = $request->only(['user_id', 'project_id', 'scene_id', 'ath', 'atv', 'title']);
        $data['nickname'] = !empty($userInfo['user_nickname']) ? $userInfo['user_nickname'] : $request->user_nickname;
        $data['url'] = !empty($userInfo['user_head_img']) ? $userInfo['user_head_img'] : (!empty($request->user_head_img) ? $request->user_head_img : env('HEAD_IMG_DEFAULT'));
        $res = $this->projectCommentInterface->add($data);
        if ($res) {
            //删除xml缓存
            event(new XmlCacheEvent(['id' => $request->project_id], 2));

            unset($data['user_id']);
            $data['id'] = $res->id;
            $data['url'] = \Storage::url(env('APP_ENV') . $data['url']);
            tne('SUCCESS', $data);
        }

        tne('FAIL');
    }

    /**
     * @OA\Get(
     *     path="/project/commentLists",
     *     description="获取评论列表",
     *     operationId="commentLists",
     *     tags={"Project"},
     *     summary="获取评论列表",
     *     @OA\Parameter( description="项目ID", in="query", name="project_id", @OA\Schema( type="string"),example="67"),
     *     @OA\Parameter( description="场景id", in="query", name="scene_id", @OA\Schema( type="integer"),example=1),
     *     @OA\Parameter( description="当前页码", in="query", name="page",required=true, @OA\Schema( type="integer"),example="1"),
     *     @OA\Parameter( description="每页显示条数", in="query", name="page_size", @OA\Schema( type="integer"),example="10"),
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
     *         {"ticket": {}}
     *     }
     * )
     */
    public function commentLists(Request $request)
    {
        if (!empty($request->project_id)) {

            $where['project_id'] = $request->project_id;
        }

        if (!empty($request->scene_id)) {

            $where['scene_id'] = $request->scene_id;
        }

        $pageSize = !empty($request->page_size) ? $request->page_size : 10;
        $res = $this->projectCommentInterface->lists($where, $pageSize,['scene_id','nickname','url','title','ath','atv']);
        return new BaseCollection($res);
    }

    /**
     * @OA\Get(
     *     path="/project/delComment",
     *     description="删除评论",
     *     operationId="delComment",
     *     tags={"Project"},
     *     summary="删除评论",
     *     @OA\Parameter( description="评论ID", in="query", name="id",required=true, @OA\Schema( type="string"),example="1"),
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
     *         {"ticket": {}}
     *     }
     * )
     */
    public function delComment(Request $request)
    {
        $where['id'] = $request->id;
        $res = $this->projectCommentInterface->delete($where);
        if ($res) {
            $this->projectInterface->deleteXml($request->id);

            tne('SUCCESS', $res);
        }

        tne('FAIL');
    }

    /**
     * @OA\Get(
     *     path="/project/getUserProjectCommentList",
     *     description="获取用户ID评论列表",
     *     operationId="getUserProjectCommentList",
     *     tags={"Project"},
     *     summary="获取评论列表",
     *     @OA\Parameter( description="用户ID", in="query", name="user_id",required=true, @OA\Schema( type="string"),example="1"),
     *     @OA\Parameter( description="当前页码", in="query", name="page",required=true, @OA\Schema( type="integer"),example="1"),
     *     @OA\Parameter( description="每页显示条数", in="query", name="page_size", @OA\Schema( type="integer"),example="10"),
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
     *         {"ticket": {}}
     *     }
     * )
     */
    public function getUserProjectCommentList(Request $request)
    {
        $where = [['user_id',$request->user_id]];
        if($request->input('search_value')){
            $where[] = ['title', 'like', "%{$request->input('search_value')}%"];
        }
        return new BaseCollection($this->projectCommentInterface->getUserProjectCommentList($where, $request->input
        ('page_size', 10)));
    }
}