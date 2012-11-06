<?php
class Cache{

	public $dirname;	// Directory for temporaries files
	public $duration;   // Cache life duration in minute
	public $buffer;		// Buffer for methods start and end

	/**
	* Class initialisation
	* @param string $dirname directory for temporaries files
	* @param int $duration cache life time in minute
	**/
	public function __construct($dirname, $duration){
		$this->dirname = $dirname;
		$this->duration = $duration;
	}

	/**
	* Write string in cache file
	* @param string $cachename name of cache
	* @param string $content storing content
	* @return boolean stat action
	**/
	public function write($cachename, $content){
		return file_put_contents($this->dirname.'/'.$cachename, $content);
	}

	/**
	* Method that allows to read content of cache 
	* @param string $cachename name of cache file
	* @return return false is the cache is not find or if duration is expired, otherwise return string of content
	**/
	public function read($cachename){
		$file = $this->dirname.'/'.$cachename;
		if(!file_exists($file)){
			return false;
		}
		$lifetime = (time() - filemtime($file)) / 60;
		if($lifetime > $this->duration){
			return false;
		}
		return file_get_contents($file);
	}

	/**
	* Method that allows to delete spefic cache file
	* @param string $cachename Name of cache file
	**/
	public function delete($cachename){
		$file = $this->dirname.'/'.$cachename;
		if(file_exists($file)){
			unlink($file);
		}
	}

	/**
	* Method that allows to clean cache directory
	**/
	public function clear(){
		$files = glob($this->dirname.'/*');
		foreach( $files as $file ) {
			unlink($file);
		}
	}

	/**
	* Method that allows to include one php file
	* @param string $file file to include (with absolute path)
	* @param string $cachename Name of cache file 
	* @return return true and show content with echo function
	**/
	public function inc($file, $cachename = null){
		if(!$cachename){
			$cachename = basename($file);
		}
		if($content = $this->read($cachename)){
			echo $content;
			return true;
		}
		ob_start();
		require $file;
		$content = ob_get_clean();
		$this->write($cachename, $content);
		echo $content;
		return true;
	}

	/**
	* Start the buffer for capture script part and wait method end() to stop capture and show content
	* @param string $cachename Name of cache file
	* @return strinf of content 
	**/
	public function start($cachename){
		if($content = $this->read($cachename)){
			echo $content;
			$this->buffer = false;
			return true;
		}
		ob_start();
		$this->buffer = $cachename;
	}

	/**
	* Stop the buffer and show content if not in cache when call method start()
	* @return strinf of content 
	**/
	public function end(){
		if(!$this->buffer){
			return false;
		}
		$content = ob_get_clean();
		echo $content;
		$this->write($this->buffer, $content);
	}

}