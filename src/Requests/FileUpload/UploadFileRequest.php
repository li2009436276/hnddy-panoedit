<?php


namespace Yjtec\PanoEdit\Requests\FileUpload;
use Yjtec\PanoEdit\Model\FileClassify;
use Yjtec\PanoEdit\Model\Model3dClassify;


use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\RequestBody(
 *     request="UploadFileRequest",
 *     description="上传文件",
 *     required=true,
 *     @OA\MediaType(
 *       mediaType="multipart/form-data",
 *       @OA\Schema(
 *          @OA\Property(description="file（单个或多个文件）",property="file[]",type="file"),
 *          @OA\Property(description="类型：1：图片，2：音频：3：视频",property="type",type="integer",example=1),
 *          @OA\Property(description="图片应用类型：1：普通图，2：环物图，3D环物/倾斜摄影",property="apply_type",type="integer",example=1),
 *          @OA\Property(description="分类ID",property="classify_id",type="integer",example=1),
 *          @OA\Property(description="图片静/动：1：静，2：动，3：icon，4：默认图片，5：特效图",property="category",type="integer",example=1),
 *        )
 *    ),
 * )
 */
class UploadFileRequest extends FormRequest
{
    public function authorize(){

       return true;
    }

    public function rules(){

        return [
            'file'          => 'required|array',
            'apply_type'    => 'nullable|integer|min:1',
            'type'          => 'required|integer|min:1|max:5',
            'classify_id'   => 'nullable|integer|min:0',
            'category'      => 'nullable|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'file.required'     => '文件不能为空',

            'apply_type.required' => '文件应用类型不能为空',
            'apply_type.integer'  => '文件应用类型错误',
            'apply_type.min'      => '文件应用类型最小为1',

            'type.required'     => '文件类型不能为空',
            'type.integer'      => '文件类型错误',
            'type.min'          => '文件类型最小为1',
            'type.max'          => '文件类型最大为3',

        ];
    }

    /**
     * 验证文件
     * @param $validator
     */
    public function withValidator($validator) {
        $validator->after(function ($validator) {
            if(empty($validator->errors()->all()) && !empty($this->file('file'))){

                //验证分类是否存在
                if (!empty($this->classify_id)) {

                    $classifyInfo = $this->getClassify($this->classify_id,$this->type);
                }

                //获取其中一个文件判断文件类型
                $file       = $this->file('file') ;
                if (empty($file)) {

                    tne('FILE_NULL');
                }

                //文件类型
                $this->apply_type = !empty($this->apply_type) ? $this->apply_type : 1;

                //判断文件类型
                foreach ($file as $key=>$value) {

                    $mimeType   = $value->getMimeType();

                    switch ($this->type) {

                        case 1: {       //图片文件

                            if (strpos($mimeType,'image') !== false) {

                                $this->bucket = config('env.user_img_bucket');
                            }
                            break;
                        }
                        case 2: {       //音频文件

                            if ($mimeType == 'audio/mpeg' || $mimeType == 'application/octet-stream') {

                                $this->bucket = config('env.user_audio_bucket');
                            }
                            break;
                        }
                        case 3: {       //视频文件

                            if ($mimeType =='video/mp4') {

                                $this->bucket = config('env.user_video_bucket');
                            }
                            break;
                        }
                        case 5: {       //3d模型 

                            if ($mimeType == 'application/zip') {
                                
                                $this->apply_type = 3;    
                                $this->bucket = config('env.user_model3d_bucket');
                                
                            }
                            break;
                        }
                    }
                }

                if (empty($this->bucket)) {

                    tne('FILE_TYPE_ERROR');
                }

            }
        });
    }

    /**
     * 判断分类是否存在
     * @param $id
     * @param null $type
     * @return mixed
     * @throws \App\Exceptions\ApiException
     */
    public function getClassify($id,$type = NULL) {
        
        $where['id']        = $id;
        $where['user_id']   = $this->user_id;

        if ($type == 5) {   //3d模型文件
           
          $classifyInfo = Model3dClassify::where($where)->first();;
        } else {            //普通素材文件

            if (!empty($type)) {

                $where['type']  = $type;
            }

            $classifyInfo               = FileClassify::where($where)->first();
        }
        
        if (empty($classifyInfo)) {

            tne('CLASSIFY_NOT_EXIST');
        }

        return $classifyInfo;
    }
}