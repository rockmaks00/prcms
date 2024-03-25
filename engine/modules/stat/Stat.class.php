<?
class ModuleStat extends Module {
	protected $oDb;
	protected $oComponent;
	
	public function Init() {
		$this->oDb=Engine::GetDb(__CLASS__);
		$this->oDb->Install();
	}
	
	public function Add(ModuleStat_EntityStat $oStat) {
		if ($iId=$this->oDb->Add($oStat)){
			$oStat->setId($iId);
		}
		return $oStat;
	}
	
	public function GetViewsByDates($sDateFrom, $sDateTo) {
		$iBegin=strtotime($sDateFrom);
		$iEnd=strtotime($sDateTo);
		$aResult=array();
		for ($i=$iBegin; $i<=$iEnd; $i+=60*60*24){
			$aResult[date("Y-m-d", $i)]=$this->GetViewsByDate(date("Y-m-d", $i));
		}
		return $aResult;
	}
	
	public function GetViewsByDate($sDate) {
		if (false === ($data = $this->Cache_Get("views_{$sDate}"))) {
			$data = $this->oDb->GetViewsByDate($sDate);
			if ($sDate<date("Y:m:d")) $this->Cache_Set("views_{$sDate}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	
	public function GetUniqViewsByDates($sDateFrom, $sDateTo) {
		$iBegin=strtotime($sDateFrom);
		$iEnd=strtotime($sDateTo);
		$aResult=array();
		for ($i=$iBegin; $i<=$iEnd; $i+=60*60*24){
			$aResult[date("Y-m-d", $i)]=$this->GetUniqViewsByDate(date("Y-m-d", $i));
			
			/*
$oStat=Engine::getEntity("Stat");
			$oStat->setNode(1);
			$oStat->setIp($_SERVER['REMOTE_ADDR']);
			$oStat->setDate(date("Y-m-d", $i));
			for($j=0; $j<rand(40, 60); $j++){
				//echo $j."<br>";
				//$this->Add($oStat);
			}
*/
		}
		return $aResult;
	}
	
	public function GetUniqViewsByDate($sDate) {
		if (false === ($data = $this->Cache_Get("uniq_views_{$sDate}"))) {
			$data = $this->oDb->GetUniqViewsByDate($sDate);
			if ($sDate<date("Y:m:d")) $this->Cache_Set("uniq_views_{$sDate}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}

}