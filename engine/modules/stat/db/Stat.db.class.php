<?
class ModuleStat_DbStat extends Db {
	public function Install(){
		if( !$this->oDb->CheckTableExists(Config::Get("db.prefix")."stats") ){
			$sql="CREATE TABLE IF NOT EXISTS `".Config::Get("db.prefix")."stats` (
				`stat_id` int(11) NOT NULL AUTO_INCREMENT,
				`stat_node` int(11) NOT NULL,
				`stat_ip` varchar(15) NOT NULL,
				`stat_date` date NOT NULL,
				`stat_time` time NOT NULL,
				PRIMARY KEY (`stat_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
		}		
	}
	public function Add($oStat){
		$sql = "INSERT INTO ".Config::Get("db.prefix")."stats (
				stat_node,
				stat_ip,
				stat_date,
				stat_time
			) 
			VALUES(?, ?, ?, ?)
		";
		if (!$oStat->getIp()) $oStat->setIp($_SERVER['REMOTE_ADDR']);
		return $this->oDb->Query($sql, 
			$oStat->getNode(),
			$oStat->getIp(),
			date("Y-m-d"),
			date("H:i:s")
		);
	}
	public function GetViewsByDate($sDate) {
		$sql = "SELECT COUNT(stat_id) as count FROM ".Config::Get("db.prefix")."stats WHERE stat_date=?";
		$aRow=$this->oDb->SelectRow($sql, $sDate);
		
		if ($aRow['count']) return $aRow['count'];
		else return 0;
	}
	
	public function GetUniqViewsByDate($sDate) {
		$sql = "SELECT COUNT(DISTINCT stat_ip) as count FROM ".Config::Get("db.prefix")."stats WHERE stat_date=?";
		$aRow=$this->oDb->SelectRow($sql, $sDate);
		
		if ($aRow['count']) return $aRow['count'];
		else return 0;
	}
}	