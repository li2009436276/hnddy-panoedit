<?php

namespace Yjtec\PanoEdit\Controllers;

use Yjtec\PanoEdit\Repositories\Contracts\Model3dInterface;
use Yjtec\PanoEdit\Repositories\Contracts\Model3dClassifyInterface;
use Yjtec\PanoEdit\Requests\Model3d\Model3dListsRequest;
use Yjtec\PanoEdit\Requests\Model3d\Model3dInfoRequest;
use Yjtec\PanoEdit\Requests\Model3d\ClassifyAddRequest;
use Yjtec\PanoEdit\Requests\Model3d\ClassifyListsRequest;
use Yjtec\PanoEdit\Requests\Model3d\ClassifyEditRequest;
use Yjtec\PanoEdit\Requests\Model3d\ClassifyMoveRequest;
use Illuminate\Http\Request;
use Yjtec\PanoEdit\Resources\BaseResource;
use Yjtec\PanoEdit\Resources\ErrorResource;
use Yjtec\PanoEdit\Resources\Model3d\Model3dListsCollection;
use Yjtec\PanoEdit\Requests\Model3d\Model3dUpdateRequest;


/**
 *
 */
class Model3dController extends Controller
{

    private $model3dInterface;
    private $model3dClassifyInterface;

    function __construct(Model3dInterface $model3dInterface, Model3dClassifyInterface $model3dClassifyInterface)
    {
        $this->model3dInterface = $model3dInterface;
        $this->model3dClassifyInterface = $model3dClassifyInterface;
    }


    /**
     * @OA\Post(
     *     path="/model3d/addClassify",
     *     description="添加分类",
     *     operationId="addClassify",
     *     tags={"Model3d"},
     *     summary="添加分类",
     *     @OA\Parameter( description="分类名称", in="query", name="name", required=true,@OA\Schema(
     *                    type="string"),example="测试分类"),
     *     @OA\Parameter( description="模型类型1=3d模型，2=倾斜摄影", in="query", name="type", required=true,@OA\Schema(
     *                    type="integer"),example="1"),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error",
     *         @OA\JsonContent(ref="#/components/schemas/Error")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="pet response",
     *         @OA\JsonContent(ref="#/components/schemas/Success")
     *     ),
     *
     *     security={
     *         {"ticket": {}}
     *     }
     * )
     */
    public function addClassify(ClassifyAddRequest $request)
    {
        $data = $request->only('user_id', 'name', 'type');

        $res = $this->model3dClassifyInterface->add($data);
        if($res){

            return new BaseResource([]);
        }
        return new ErrorResource(['code' => 'FAIL']);
    }

    /**
     * @OA\Get(
     *     path="/model3d/classifyLists",
     *     description="分类列表",
     *     operationId="classifyLists",
     *     tags={"Model3d"},
     *     summary="分类列表",
     *     @OA\Parameter( description="模型类型1=3d模型，2=倾斜摄影", in="query", name="type", required=true,@OA\Schema(
     *                    type="integer"),example="1"),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error",
     *         @OA\JsonContent(ref="#/components/schemas/Error")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="pet response",
     *         @OA\JsonContent(ref="#/components/schemas/FileClassifyLists")
     *     ),
     *
     *     security={
     *         {"ticket": {}}
     *     }
     * )
     */
    public function classifyLists(Request $request)
    {
        $where['user_id']  = $request->user_id;
        $where['type']      = $request->type;
        if($request->input('date_sort', 'create') == 'update'){
            $orderSort['updated_at'] = 'desc';
        }else{
            $orderSort['created_at'] = 'desc';
        }
        $res = $this->model3dClassifyInterface->lists($where, '*', $orderSort);
        if($res !== false){

            return new BaseResource($res);
        }

        return new ErrorResource(['code' => 'FAIL']);
    }

    /**
     * @OA\Post(
     *     path="/model3d/editClassify",
     *     description="编辑分类",
     *     operationId="editClassify",
     *     tags={"Model3d"},
     *     summary="编辑分类",
     *     @OA\Parameter( description="分类ID", in="query", name="id", required=true,@OA\Schema(
     *                    type="integer"),example="1"),
     *     @OA\Parameter( description="分类名称", in="query", name="name", required=true,@OA\Schema(
     *                    type="string"),example="测试分类"),
     *     @OA\Parameter( description="token", in="header", name="token",  required=true,@OA\Schema(
     *                    type="string"),example="9db5a82adb24e9f3cf4b639ec8c3f434"),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error",
     *         @OA\JsonContent(ref="#/components/schemas/Error")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="pet response",
     *         @OA\JsonContent(ref="#/components/schemas/Success")
     *     ),
     *
     *     security={
     *         {"ticket": {}}
     *     }
     * )
     */
    public function editClassify(ClassifyEditRequest $request)
    {

        $where['id'] = $request->id;
        $where['user_id'] = $request->user_id;
        $data['name'] = $request->name;
        $res = $this->model3dClassifyInterface->update($where, $data);
        if($res){

            return new BaseResource([]);
        }
        return new ErrorResource(['code' => 'FAIL']);

    }

    /**
     * @OA\Get(
     *     path="/model3d/delClassify",
     *     description="删除分类",
     *     operationId="delClassify",
     *     tags={"Model3d"},
     *     summary="删除分类",
     *     @OA\Parameter( description="分类ID", in="query", name="id",  required=true,@OA\Schema(
     *                    type="integer"),example="1"),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error",
     *         @OA\JsonContent(ref="#/components/schemas/Error")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="pet response",
     *         @OA\JsonContent(ref="#/components/schemas/Success")
     *     ),
     *
     *     security={
     *         {"ticket": {}}
     *     }
     * )
     */
    public function delClassify(Request $request)
    {
        if($this->model3dInterface->info(['classify_id' => $request->id])){
            return new ErrorResource(collect(['code' => 'GROUP_EXIST_FILE']));
        }
        $where['user_id'] = $request->user_id;
        $where['id'] = $request->id;
        $res = $this->model3dClassifyInterface->delete($where);
        if($res){

            return new BaseResource([]);
        }
        return new ErrorResource(['code' => 'FAIL']);
    }

    /**
     * @OA\Post(
     *     path="/model3d/moveClassify",
     *     description="移动分类",
     *     operationId="moveClassify",
     *     tags={"Model3d"},
     *     summary="移动分类",
     *     @OA\Parameter( description="文件ID", in="query", name="ids[]",required=true,@OA\Schema(
     *                    type="string"),example="2"),
     *     @OA\Parameter( description="分类ID", in="query", name="classify_id",required=true,@OA\Schema(
     *                    type="integer"),example=1),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error",
     *         @OA\JsonContent(ref="#/components/schemas/Error")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="pet response",
     *         @OA\JsonContent(ref="#/components/schemas/Success")
     *     ),
     *
     *     security={
     *         {"ticket": {}}
     *     }
     * )
     */
    public function moveClassify(ClassifyMoveRequest $request)
    {

        $res = $this->model3dInterface->moveClassify($request->ids, $request->classify_id);
        if($res){

            return new BaseResource([]);
        }
        return new ErrorResource(['code' => 'FAIL']);
    }

    /**
     * @OA\Get(
     *     path="/model3d/lists",
     *     description="3d模型列表",
     *     operationId="Modeld3dLists",
     *     tags={"Model3d"},
     *     summary="3d模型列表",
     *     @OA\Parameter( description="分页", in="query", name="page", required=true,@OA\Schema( type="integer")),
     *     @OA\Parameter( description="分页数", in="query", name="page_size",  @OA\Schema( type="integer")),
     *     @OA\Parameter( description="分类ID", in="query", name="classify_id",  @OA\Schema( type="string")),
     *     @OA\Parameter( description="搜索", in="query", name="name",  @OA\Schema( type="string")),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error",
     *         @OA\JsonContent(ref="#/components/schemas/Error")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="pet response",
     *         @OA\JsonContent(ref="#/components/schemas/Model3dLists"),
     *     ),
     *
     *     security={
     *         {"ticket": {}}
     *     }
     * )
     */
    public function lists(Model3dListsRequest $model3dListsRequest)
    {
        if($model3dListsRequest->input('date_sort', 'create') == 'update'){
            $orderSort['updated_at'] = 'desc';
        }else{
            $orderSort['created_at'] = 'desc';
        }
        $res = $this->model3dInterface->lists($model3dListsRequest->all());

        if($res){

            return new Model3dListsCollection($res);
        }

        return new ErrorResource([]);
    }

    /**
     * @OA\Get(
     *     path="/model3d/info",
     *     description="3d模型详情",
     *     operationId="Modeld3dInfo",
     *     tags={"Model3d"},
     *     summary="3d模型详情",
     *     @OA\Parameter( description="模型ID", in="query", name="id", required=true,@OA\Schema( type="integer")),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error",
     *         @OA\JsonContent(ref="#/components/schemas/Error")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="pet response",
     *         @OA\JsonContent(ref="#/components/schemas/Model3dLists"),
     *     ),
     *
     *     security={
     *         {"ticket": {}}
     *     }
     * )
     */
    public function info(Model3dInfoRequest $model3dInfoRequest)
    {

        $res = $this->model3dInterface->info(['id' => $model3dInfoRequest->id]);
        if($res){

            return new BaseResource($res);
        }

        return new ErrorResource(['code' => 'FAIL']);
    }

    /**
     * @OA\Put(
     *     path="/model3d/update",
     *     description="3d模型更新",
     *     operationId="Modeld3dUpdate",
     *     tags={"Model3d"},
     *     summary="3d模型更新",
     *     @OA\Parameter( description="模型ID", in="query", name="id", required=true,@OA\Schema( type="integer")),
     *     @OA\Parameter( description="名称", in="query", name="name", @OA\Schema( type="string")),
     *     @OA\Parameter( description="封面", in="query", name="thumb", @OA\Schema( type="string")),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error",
     *         @OA\JsonContent(ref="#/components/schemas/Error")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="pet response",
     *         @OA\JsonContent(ref="#/components/schemas/Success")
     *     ),
     *
     *     security={
     *         {"ticket": {}}
     *     }
     * )
     */
    public function update(Model3dUpdateRequest $request){

        $where['id']        = $request->id;
        $where['user_id']   = $request->user_id;

        $data['id'] = $request->id;
        if ($request->name) {

            $data['name'] = $request->name;
        }

        if ($request->thumb) {

            $data['thumb'] = $request->thumb;
        }

        $res = $this->model3dInterface->update($where,$data);

        if($res){

            return new BaseResource([]);
        }

        return new ErrorResource([]);
    }

    /**
     * @OA\Delete (
     *     path="/model3d/delete",
     *     description="模型删除",
     *     operationId="model3dDelete",
     *     tags={"Model3d"},
     *     summary="模型删除",
     *     @OA\Parameter( description="模型ID", in="query", name="ids[]",required=true,@OA\Schema( type="string"),example="1"),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error",
     *         @OA\JsonContent(ref="#/components/schemas/Error")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="pet response",
     *         @OA\JsonContent(ref="#/components/schemas/Success")
     *     ),
     *
     *     security={
     *         {"ticket": {}}
     *     }
     * )
     */
    public function delete(Request $request){

        $where['user_id'] = $request->user_id;

        $res = $this->model3dInterface->delete($where,$request->ids);

        if($res){

            return new BaseResource([]);
        }

        return new ErrorResource(['code' => 'FAIL']);
    }
}

?>