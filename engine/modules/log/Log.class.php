<?
class ModuleLog extends Module {
	protected $oDb;
	protected $oLog=null;
	protected $bActive=true;
	static protected $aStat=array();
	
	public function Init() {
		$this->oDb=Engine::GetDb(__CLASS__);
		$this->oDb->Install();
		$this->bActive=Config::Get('app.log.use');
		if (!$this->bActive) {
			return false;
		}
	}
	
	public function Add(ModuleLog_EntityLog $oLog) {
		if (!$this->bActive) {
			return false;
		}
		if ($iId=$this->oDb->Add($oLog)){
			$oLog->setId($iId);
		}
		return $oLog;
	}
	
	public function GetLogs($iLimit) {
		if (false === ($data = $this->Cache_Get("logs"))) {
			$data=$this->oDb->GetLogs($iLimit);
			$this->Cache_Set("logs", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
}