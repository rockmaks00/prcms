<?
class ModuleDashboard extends Module {
	protected $oDb;
	protected $oComponent;
	
	public function Init() {
		$this->oDb=Engine::GetDb(__CLASS__);
	}
	
	protected function dir_size($dir) { 
		$totalsize=0; 
		if ($dirstream = @opendir($dir)) { 
			while (false !== ($filename = readdir($dirstream))) { 
				if ($filename!="." && $filename!=".."){ 
					if (is_file($dir."/".$filename)) 
					$totalsize+=filesize($dir."/".$filename); 
	
					if (is_dir($dir."/".$filename)) 
						$totalsize+=$this->dir_size($dir."/".$filename); 
				} 
			} 
		}
		closedir($dirstream); 
		return $totalsize; 
	} 
	
	public function GetFilesSize() {
		if (false === ($data = $this->Cache_Get("dir_size"))) {
			$data=$this->dir_size(".");
			$data=round($data / 10240)/100;
			$this->Cache_Set("dir_size", $data, 60*60*24*7);
		}
		return $data;
	}
	
	public function GetDbSize(){
		if (false === ($data = $this->Cache_Get("db_size"))) {
			$data = $this->oDb->GetDbSize();
			$this->Cache_Set("db_size", $data, 60*60*24*1);
		}
		return $data;
	}

}