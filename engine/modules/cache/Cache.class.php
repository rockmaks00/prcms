<?
class ModuleCache extends Module {
	
	protected $oCache=null;
	protected $sPrefix;
	protected $bActive=true;
	static protected $aStat=array();
	
	public function Init() {
		$this->sPrefix = Config::Get("app.cache.prefix");
		$this->bActive=Config::Get('app.cache.use');
		if (!$this->bActive) {
			return false;
		}
		$this->oCache = new Memcache;
		$this->oCache->connect(Config::Get('app.cache.host'), Config::Get('app.cache.port')) or die("Could not connect to memcache");
		//$this->oCache->flush();
	}

	public function Get($sKey) {
		if (!$this->bActive) {
			return false;
		}
		self::$aStat['get']++;
		return $this->oCache->get($this->sPrefix.$sKey);
	}	
	
	public function Set($sKey, $var, $iExpire=false) {		
		if (!$this->bActive) {
			return false;
		}
		self::$aStat['set']++;
		return $this->oCache->set($this->sPrefix.$sKey, $var, false, $iExpire);
	}
	
	public function Delete($sKey) {
		if (!$this->bActive) {
			return false;
		}
		self::$aStat['delete']++;
		return $this->oCache->delete($this->sPrefix.$sKey);
	}
	
	public function Clean() {
		if (!$this->bActive) {
			return false;
		}
		return $this->oCache->flush();
	}
	
	public function GetStat(){
		return self::$aStat;
	}
	
	public function __destruct(){
		if (!$this->bActive) {
			return false;
		}
		$this->oCache->close();
	}
}