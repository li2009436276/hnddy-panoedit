<?php


namespace Yjtec\PanoEdit\Controllers;


use Yjtec\PanoEdit\Repositories\Contracts\FileInterface;
use Yjtec\PanoEdit\Requests\FileUpload\UploadFileRequest;
use Yjtec\PanoEdit\Resources\BaseResource;
use Yjtec\PanoEdit\Resources\ErrorResource;
use Yjtec\PanoEdit\Services\FileService\FileService;

class FileUploadController extends Controller
{

    private $fileInterface = null;
    public function __construct(FileInterface $fileInterface)
    {
        $this->fileInterface = $fileInterface;
    }

    /**
     * @OA\Post(
     *     path="/fileUpload/uploadFile",
     *     description="上传文件",
     *     tags={"FileUpload"},
     *     @OA\RequestBody(ref="#/components/requestBodies/UploadFileRequest"),
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
    public function uploadFile(UploadFileRequest $request){

        $fileService = new FileService();
        $res = $fileService->upload($request->file('file'),$request->bucket,$request->apply_type,$request->id);

        if ($res) {

            return new BaseResource($res);
        }

        return new ErrorResource(['code' => 'FAIL']);

    }
}