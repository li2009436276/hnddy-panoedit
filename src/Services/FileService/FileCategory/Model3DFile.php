<?php 
	
	namespace Yjtec\PanoEdit\Services\FileService\FileCategory;

	use Yjtec\PanoEdit\Services\FileService\Upload\FileArray;
	use Yjtec\PanoEdit\Services\FileService\Upload\FileSingle;
	use Yjtec\PanoEdit\Events\UnzipEvent;
	/**
	 * 
	 */
	class Model3DFile extends FileParent
	{
		private $id = 0;
		public function __construct($dir,$id = 0)
		{
			parent::__construct($dir);
			$this->id = $id;
		}

		/**
	     * 获取文件保存路径
	     * @param $file
	     * @return mixed
	     */
	    public function getPath($file)
	    {
	        $ext =  $file->getClientOriginalExtension();

	        return parent::createPath($ext);
	    }

	    /**
	    * 上传3d模型
	    *
	    */
	    public function upload($file){

	    	//事件解压
	    	if (is_array($file)) {

	            $fileArray = new FileArray($this,$file,'public');
	            $files = $fileArray->upload();

	        } else {

	            $fileSingle = new FileSingle($this,$file,'public');
	            $files = [$fileSingle->upload()];
	        }
			$files = $this->unzip($files);
	        return $files;
	    }

	    /**
	    * 解压3d模型
	    *
	    */
	    public function unzip($files){

	    	$userId 	= request()->user_id;
	    	$classifyId = !empty(request()->classify_id) ? request()->classify_id : 0; 
	    	$model3Interface = resolve('Yjtec\PanoEdit\Repositories\Contracts\Model3dInterface');

	    	foreach ($files as $key => &$value) {

	    	    if (empty($this->id)) {

                    $data['user_id'] = $userId;
                    $data['path']	 = $value['path'].'3d';
                    $data['name']	 = $value['name'];
                    $data['classify_id'] = $classifyId;
                    $res = $model3Interface->add($data);

                    if ($res) {

                        $this->id = $res->id;
                    }
                }

	    		if ($this->id) {

	    			$value['id'] = $this->id;

	    			event(new UnzipEvent($userId,$res->id,$value['path']));
	    		}
	    	}

	    	return $files;
	    }
	}

?>