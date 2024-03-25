<?
class ComponentNews_ModuleNews_DbNews extends Db {
	public function Install(){
		if( !$this->oDb->CheckTableExists(Config::Get("db.prefix")."com_news") ){
			$sql = "CREATE TABLE IF NOT EXISTS `".Config::Get("db.prefix")."com_news` (
				`news_id` int(11) NOT NULL AUTO_INCREMENT,
				`news_title` varchar(250) NOT NULL,
				`news_announcement` text NOT NULL,
				`news_body` text NOT NULL,
				`news_datetime` datetime NOT NULL,
				`news_image` varchar(250) NOT NULL DEFAULT '',
				`news_node` int(11) NOT NULL,
				`news_active` int(11) NOT NULL DEFAULT '1',
				PRIMARY KEY (`news_id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
			return $this->oDb->Query($sql);
		}
	}

	public function GetNewsById($iId) {
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."com_news WHERE news_id=?";
		$aRow=$this->oDb->SelectRow($sql, $iId);
		if ($aRow) return Engine::GetEntity('ComponentNews_News', $aRow);
		else return null;
	}
	public function GetNews() {
		$sql = "SELECT news_id FROM ".Config::Get("db.prefix")."com_news ORDER BY news_datetime DESC, news_id DESC";
		$aRows = $this->oDb->Select($sql);
		return array_map( function($var){ return $var["news_id"]; }, $aRows);
	}
	public function GetNewsByNode($iNodeId) {
		$sql = "SELECT news_id FROM ".Config::Get("db.prefix")."com_news WHERE news_node=? ORDER BY news_datetime DESC, news_id DESC";
		$aRows = $this->oDb->Select($sql, $iNodeId);
		return array_map( function($var){ return $var["news_id"]; }, $aRows);
	}
	public function GetActiveNewsByNode($iNodeId){
		$sql = "SELECT news_id FROM ".Config::Get("db.prefix")."com_news WHERE news_active=1 AND news_node=? ORDER BY news_datetime DESC, news_id DESC";
		$aRows = $this->oDb->Select($sql, $iNodeId);
		return array_map( function($var){ return $var["news_id"]; }, $aRows);
	}
	/*public function GetNewsByIds($aIds) {
		$sql = "SELECT news_id FROM ".Config::Get("db.prefix")."com_news WHERE news_id IN (?) ORDER BY news_datetime DESC, news_id DESC";
		$sIds = implode("','", $aIds);
		$aRows = $this->oDb->Select($sql, $sIds);
		return array_map( function($var){ return $var["news_id"]; }, $aRows);
	}*/
	
	
	public function Add($oNews){
		$sql = "INSERT INTO ".Config::Get("db.prefix")."com_news (
				news_title,
				news_announcement,
				news_body,
				news_datetime,
				news_node,
				news_image,
				news_active			
			) 
			VALUES(?, ?, ?, ?, ?, ?, ?)
		";
		return $this->oDb->Query($sql, 
			$oNews->getTitle(),
			$oNews->getAnnouncement(),
			$oNews->getBody(),
			$oNews->getDatetime(),
			$oNews->getNode(),
			$oNews->getImage(),
			$oNews->getActive()
		);
	}
	public function Update($oNews){
		$sql = "UPDATE ".Config::Get("db.prefix")."com_news SET 
				news_title=?,
	            news_announcement=?,
	            news_body=?,
	            news_datetime=?,
	            news_node=?,
	            news_image=?,
	            news_active=?
			WHERE news_id=?
		";
		return $this->oDb->Query($sql, 
			$oNews->getTitle(),
			$oNews->getAnnouncement(),
			$oNews->getBody(),
			$oNews->getDatetime(),
			$oNews->getNode(),
			$oNews->getImage(),
			$oNews->getActive(),
			$oNews->getId()
		);
	}
	public function Activate($iNewsId) {
		$sql = "UPDATE ".Config::Get("db.prefix")."com_news SET news_active=1 WHERE news_id=?";
		if ($this->oDb->Query($sql, $iNewsId)) return true;
		else return false;
	}
	
	public function Deactivate($iNewsId) {
		$sql = "UPDATE ".Config::Get("db.prefix")."com_news SET news_active=0 WHERE news_id=?";
		if ($this->oDb->Query($sql, $iNewsId)) return true;
		else return false;
	}
	
	public function Delete($iNewsId) {
		$sql = "DELETE FROM ".Config::Get("db.prefix")."com_news WHERE news_id=?";
		if ($this->oDb->Query($sql, $iNewsId)) return true;
		else return false;
	}

	public function Search($sWord){
		$sWord = "%".$sWord."%";
		$sql = "SELECT news_id FROM ".Config::Get("db.prefix")."com_news WHERE ( news_announcement LIKE ? OR news_body LIKE ? ) AND news_active =1 ";
		$aRows = $this->oDb->Select($sql, $sWord, $sWord);
		return array_map( function($var){ return $var["news_id"]; }, $aRows );

	}
}	