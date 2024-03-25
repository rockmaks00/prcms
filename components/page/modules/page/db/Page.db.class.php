<?
class ComponentPage_ModulePage_DbPage extends Db {
	public function Install(){
		if( !$this->oDb->CheckTableExists(Config::Get("db.prefix")."com_pages") ){
			$sql = "CREATE TABLE IF NOT EXISTS `".Config::Get("db.prefix")."com_pages` (
				`page_id` int(11) NOT NULL AUTO_INCREMENT,
				`page_body` text NOT NULL,
				`page_node` int(11) NOT NULL DEFAULT '0',
				PRIMARY KEY (`page_id`),
				FULLTEXT KEY `page_body` (`page_body`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
			$this->oDb->Query($sql);
		}
	}
	public function GetPageById($iId) {
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."com_pages WHERE page_id=?";
		$aRow=$this->oDb->SelectRow($sql, $iId);
		if ($aRow) return Engine::GetEntity('ComponentPage_Page', $aRow);
		else return null;
	}
	public function GetPageByNode($iNodeId) {
		
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."com_pages WHERE page_node=?";
		$aRow=$this->oDb->SelectRow($sql, $iNodeId);
		if ($aRow) return Engine::GetEntity('ComponentPage_Page', $aRow);
		else return null;
	}
	public function Add($oPage){
		$sql = "INSERT INTO ".Config::Get("db.prefix")."com_pages (
				page_body,
	            page_node			
			) 
			VALUES(?, ?)
		";
		return $this->oDb->Query($sql, 
			$oPage->getBody(),
			$oPage->getNode()
		);
	}
	public function Update($oPage){
		$sql = "UPDATE ".Config::Get("db.prefix")."com_pages SET 
				page_body=?,
	            page_node=?			
			WHERE page_id=?
		";
		return $this->oDb->Query($sql, 
			$oPage->getBody(),
			$oPage->getNode(),
			$oPage->getId()
		);
	}
	public function Search($sWord){
		$sWord = "%".$sWord."%";
		$sql = "SELECT page_id FROM ".Config::Get("db.prefix")."com_pages WHERE page_body LIKE ?";
		$aRows = $this->oDb->Select($sql, $sWord);
		return array_map( function($var){ return $var["page_id"]; }, $aRows );
	}
}	