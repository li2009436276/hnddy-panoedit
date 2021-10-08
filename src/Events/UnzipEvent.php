<?php 
	
	namespace Yjtec\PanoEdit\Events;


	use Illuminate\Broadcasting\Channel;
	use Illuminate\Queue\SerializesModels;
	use Illuminate\Broadcasting\PrivateChannel;
	use Illuminate\Broadcasting\PresenceChannel;
	use Illuminate\Foundation\Events\Dispatchable;
	use Illuminate\Broadcasting\InteractsWithSockets;
	use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
	/**
	 * 
	 */
	class UnzipEvent
	{
		
		use Dispatchable, InteractsWithSockets, SerializesModels;

		public $userId;
		public $path 	= '';
		public $id;
		public function __construct($userId,$id,$path)
		{
			$this->userId	= $userId;
			$this->path 	= $path;
			$this->id 		= $id;
		}

		/**
	    * Get the channels the event should broadcast on.
	    *
	    * @return \Illuminate\Broadcasting\Channel|array
	    */
	    public function broadcastOn()
	    {
	        return new PrivateChannel('channel-name');
	    }
	}

?>