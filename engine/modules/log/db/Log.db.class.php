<?
class ModuleLog_DbLog extends Db {
	public function Install(){
		if( !$this->oDb->CheckTableExists(Config::Get("db.prefix")."logs") ){
			$sql = "CREATE TABLE IF NOT EXISTS `".Config::Get("db.prefix")."logs` (
				`log_id` int(11) NOT NULL AUTO_INCREMENT,
				`log_user` int(11) NOT NULL,
				`log_ip` varchar(15) NOT NULL,
				`log_text` varchar(250) NOT NULL,
				`log_datetime` datetime NOT NULL,
				`log_type` varchar(30) NOT NULL,
				PRIMARY KEY (`log_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
			$this->oDb->Query($sql);
		}
	}
	public function Add($oLog){
		$sql = "INSERT INTO ".Config::Get("db.prefix")."logs (
				log_user,
				log_ip,
				log_datetime,
				log_text,
				log_type
			) 
			VALUES(?, ?, ?, ?, ?)
		";
		if (!$oLog->getIp()) $oLog->setIp($_SERVER['REMOTE_ADDR']);
		return $this->oDb->Query($sql, 
			$oLog->getUser(),
			$oLog->getIp(),
			date("Y-m-d H:i:s"),
			$oLog->getText(),
			$oLog->getType()
		);
	}
	
	public function GetLogs($iLimit=100) {
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."logs ORDER BY log_datetime DESC LIMIT ".round($iLimit);
		$aResult = array();
		$aRows=$this->oDb->Select($sql);
		foreach ($aRows as $aRow) {
			$aResult[]=Engine::GetEntity('Log',$aRow);
		}
		return $aResult;
	}
	
	public function GetUniqViewsByDate($sDate) {
		$sql = "SELECT COUNT(DISTINCT log_ip) as count FROM ".Config::Get("db.prefix")."logs WHERE log_date=?";
		$aRow=$this->oDb->SelectRow($sql, $sDate);
		
		if ($aRow['count']) return $aRow['count'];
		else return 0;
	}
}	