<?php 
	
	namespace Yjtec\PanoEdit\Listeners;

	use Yjtec\PanoEdit\Events\UnzipEvent;
	use Chumper\Zipper\Zipper;
	use Illuminate\Support\Facades\Log;
	use Yjtec\PanoEdit\Repositories\Contracts\Model3dFileInterface;
	use Yjtec\PanoEdit\Repositories\Contracts\Model3dInterface;
	use Illuminate\Contracts\Queue\ShouldQueue;
	/**
	 * 
	 */
	class UnzipListener //implements ShouldQueue
	{
		private $model3dFileInterface;
		private $model3dInterface;
		public function __construct(Model3dInterface $model3dInterface,Model3dFileInterface $Model3dFileInterface)
		{

			$this->model3dInterface 	= $model3dInterface;
			$this->model3dFileInterface = $Model3dFileInterface;
		}

		/**
        * Handle the event.
        *
        * @param  object  $event
        * @return void
        */
	    public function handle(UnzipEvent $event)
	    {
	        //
	        try{

				$zipper = new Zipper();

	    		$path = \Storage::disk('public')->path(config('env.app_env').$event->path);

	    		//创建解压路径，并解压文件
	    		$makdir = $path.'3d';
	    		\Storage::disk('public')->makeDirectory($makdir);
		        $zipper->make($path)->extractTo($makdir);
		        \Storage::disk('public')->deleteDirectory(config('env.app_env').$event->path.'3d/__MACOSX');		//去除mac文件下无用文件

		        //获取解压后的所有文件
		      	$files = \Storage::disk('public')->allFiles(config('env.app_env').$event->path.'3d');
		      	if (empty($files)) {
		      		
		      		//为空删除3d模型
		      		$this->model3dInterface->delete(['id'=>$event->id]);
		      		tne('MODEL3D_FILE_EMPTY');
		      	}
				
				$sourcePath = '';      	
		      	$fileData = [];
		      	$thumb = '';
		      	foreach ($files as $key => $value) {		      		

		      		$filepathArray = explode('/', $value);
		      		$filename	   = $filepathArray[count($filepathArray) - 1];
		      		$fileArray 	   = explode('.', $filename);
		      		$fileExt	   = $fileArray[count($fileArray)-1];
		      		
		      		//获取文件路径原路径
		      		if ($key == 0) {

		      			unset($filepathArray[count($filepathArray) - 1]);
		      			$sourcePath = \Storage::disk('public')->path(implode('/', $filepathArray));
		      		}

		      		//去除mac文件下无用文件
		      		if ($filename == '.DS_Store') {
		      			
		      			continue;
		      		}

		      		//提取封面图
		      		if ($fileArray[0] == 'thumb') {
		      			
		      			$thumb = $event->path.'3d/'.$filename;
		      			$this->addFile($event->userId,$event->path,$value,$fileExt);
		      			//continue;
		      		} else {

		      			//保存文件名
			      		$fileData[] = [
			      			'model3d_id'	=> $event->id,
			      			'name'			=> $filename,
			      			'ext'			=> $fileExt,
			      			'created_at' 	=> date('Y-m-d H:i:s'),
			      			'updated_at'	=> date('Y-m-d H:i:s'),
			      		];
		      		}
		      	
		      		
		      		//上传文件
		      		//\Storage::disk(config('env.oss_upload'))->put(config('env.app_env').$event->path.'3d/'.$filename,\Storage::disk('public')->get($value));

		      	}

		      	//保存模型文件
		      	$res = $this->model3dFileInterface->insert($fileData);
		      	if ($res) {
		      		
		      		$res = $this->model3dInterface->update(['id'=>$event->id],['status'=>1,'thumb'=>$thumb]);
		      	}		      

		        //解压成功上传oss
				//exec(config('env.ossutil_path').' cp -r '.$sourcePath.' oss://'.config('env.OSS_BUCKET').'/'.config('env.app_env').$event->path.'3d');
				//exec(config('env.ossutil_path').' cp '.$path.' oss://'.config('env.OSS_BUCKET').'/'.config('env.app_env').$event->path.'3d/model3d.zip'); //上传压缩包
		        //\Storage::disk('public')->delete(config('env.app_env').$event->path);
		        //\Storage::disk('public')->deleteDirectory(config('env.app_env').$event->path.'3d');
		        
	        }catch(\Expection $e){

	        	Log::error('解压3D模型文件失败,'.$event->path);
	        }
    		
	    }

	    //添加模型缩略图到素材文件
	    public function addFile($userId,$path,$filepath,$ext){

     		$fileArray 		= getimagesize(\Storage::disk('public')->path($filepath));
     		if (!empty($fileArray['mime']) && strpos($fileArray['mime'],'image') !== false) {
     			$file['width']  = $fileArray[0];
				$file['height'] = $fileArray[1];
				$file['size']	= $fileArray['bits'] * 1024;
				$file['ext']	= $ext;
				$file['path']   = $path.'3d/thumb.'.$ext;
				$file['app_id'] = 1;
				$file['name'] 	= 'thumb';
				$file['user_id'] = $userId;

				$fileInterface = resolve('Yjtec\PanoEdit\Repositories\Contracts\FileInterface');
				$res = $fileInterface->add($file);
     		}

	    }
	}
?>