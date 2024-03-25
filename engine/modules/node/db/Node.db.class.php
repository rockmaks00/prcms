<?
class ModuleNode_DbNode extends Db {
	public function Install(){
		if( !$this->oDb->CheckTableExists(Config::Get("db.prefix")."nodes") ){
			$sql = "CREATE TABLE IF NOT EXISTS `".Config::Get("db.prefix")."nodes` (
				`node_id` int(11) NOT NULL AUTO_INCREMENT,
				`node_title` varchar(250) NOT NULL,
				`node_url` varchar(250) NOT NULL,
				`node_component` int(11) NOT NULL,
				`node_parent` int(11) NOT NULL,
				`node_active` int(11) NOT NULL DEFAULT '1',
				`node_sort` int(11) NOT NULL DEFAULT '500',
				`node_seo_title` varchar(250) NOT NULL,
				`node_seo_description` text NOT NULL,
				`node_seo_keywords` text NOT NULL,
				`node_createdate` datetime NOT NULL,
				`node_modifieddate` datetime NOT NULL,
				`node_childs` int(11) NOT NULL,
				`node_image` varchar(250) NOT NULL,
				`node_description` text NOT NULL,
				PRIMARY KEY (`node_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
			$this->oDb->Query($sql);
			$sql="INSERT INTO `pr_nodes` (
					`node_id`, `node_title`, `node_url`, `node_component`, `node_parent`, `node_active`, `node_sort`, `node_seo_title`, `node_seo_description`, `node_seo_keywords`, `node_createdate`, `node_modifieddate`, `node_childs`, `node_image`, `node_description`
				) VALUES (1, 'Главная', 'root', 2, 0, 1, 1, '', '', '', '0000-00-00 00:00:00', '2014-04-04 09:44:17', 0, '', ''),
					(2, 'Административная панель', 'admin', 1, 1, 1, 500, '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, '', ''),
					(3, 'Карта сайта', 'sitemap', 3, 1, 1, 4, '', '', '', '2013-02-17 22:23:51', '2013-10-31 17:09:31', 0, '', '')";
			$this->oDb->Query($sql);
		}
	}
	public function Add($oNode){
		$sql = "INSERT INTO ".Config::Get("db.prefix")."nodes (
				node_parent,
	            node_title,
	            node_url,
	            node_component,
	            node_sort,
	            node_active,
	            node_description, 
	            node_seo_title, 
	            node_seo_keywords,
	            node_seo_description,
	            node_createdate,
	            node_modifieddate,
	            node_image		
			) 
			VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)		";
		return $this->oDb->Query($sql, 
			$oNode->getParent(),
			$oNode->getTitle(),
			$oNode->getUrl(),
			$oNode->getComponent(),
			$oNode->getSort(),
			$oNode->getActive(),
			$oNode->getDescription(),
			$oNode->getSeoTitle(),
			$oNode->getSeoKeywords(),
			$oNode->getSeoDescription(),
			date("Y-m-d H:i:s"),
			date("Y-m-d H:i:s"),
			$oNode->getImage()
		);
	}
	public function Update($oNode){
		$sql = "UPDATE ".Config::Get("db.prefix")."nodes SET 
				node_parent=?,
	            node_title=?,
	            node_url=?,
	            node_component=?,
	            node_sort=?,
	            node_active=?,
	            node_description=?,
	            node_seo_title=?, 
	            node_seo_keywords=?,
	            node_seo_description=?,
	            node_modifieddate=?,
	            node_image=?		
			WHERE node_id=?
		";
		return $this->oDb->Query($sql, 
			$oNode->getParent(),
			$oNode->getTitle(),
			$oNode->getUrl(),
			$oNode->getComponent(),
			$oNode->getSort(),
			$oNode->getActive(),
			$oNode->getDescription(),
			$oNode->getSeoTitle(),
			$oNode->getSeoKeywords(),
			$oNode->getSeoDescription(),
			date("Y-m-d H:i:s"),
			$oNode->getImage(),
			$oNode->getId()
		);
	}
	public function GetNodes() {
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."nodes WHERE node_url!='admin' ORDER BY node_sort, node_id";
		$aResult = array();
		$aRows=$this->oDb->Select($sql);
		foreach ($aRows as $aRow) {
			$aResult[]=Engine::GetEntity('Node',$aRow);
		}
		return $aResult;
	}
	public function GetNodesOrderParent() {
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."nodes WHERE node_url!='admin' ORDER BY node_parent, node_sort, node_id";
		$aResult = array();
		$aRows=$this->oDb->Select($sql);
		foreach ($aRows as $aRow) {
			$aResult[]=Engine::GetEntity('Node',$aRow);
		}
		return $aResult;
	}
	public function GetNodesByParent($iParentId) {
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."nodes WHERE node_url!='admin' AND node_parent=? ORDER BY node_sort, node_id";
		$aResult = array();
		$aRows=$this->oDb->Select($sql, $iParentId);
		foreach ($aRows as $aRow) {
			$aResult[]=Engine::GetEntity('Node',$aRow);
		}
		return $aResult;
	}
	public function GetNodeByUrl($sUrl, $iParentId) {
		$sql = "SELECT node_id FROM ".Config::Get("db.prefix")."nodes WHERE node_url=? AND node_parent=?";
		$aRow=$this->oDb->SelectRow($sql, $sUrl, $iParentId);
		if ($aRow) return $aRow["node_id"];//Engine::GetEntity('Node',$aRow);
		else return null;
	}
	public function GetNodeById($iId) {
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."nodes WHERE node_id=?";
		$aRow=$this->oDb->SelectRow($sql, $iId);
		if ($aRow) return Engine::GetEntity('Node', $aRow);
		else return null;
	}
	public function GetNodesByComponent($iComponentId) {
		$sql = "SELECT node_id FROM ".Config::Get("db.prefix")."nodes WHERE node_component=? ORDER BY node_sort, node_id";
		$aResult = array();
		$aRows=$this->oDb->Select($sql, $iComponentId);
		return array_map(function($aRow){return $aRow["node_id"];}, $aRows);
	}
	
	public function Activate($iNodeId) {
		$sql = "UPDATE ".Config::Get("db.prefix")."nodes SET node_active=1 WHERE node_id=?";
		if ($this->oDb->Query($sql, $iNodeId)) return true;
		else return false;
	}
	
	public function Deactivate($iNodeId) {
		$sql = "UPDATE ".Config::Get("db.prefix")."nodes SET node_active=0 WHERE node_id=?";
		if ($this->oDb->Query($sql, $iNodeId)) return true;
		else return false;
	}
	
	public function Delete($iNodeId) {
		$sql = "DELETE FROM ".Config::Get("db.prefix")."nodes WHERE node_id=?";
		if ($this->oDb->Query($sql, $iNodeId)) return true;
		else return false;
	}
	public function Search($sWord){
		$sWord = "%".$sWord."%";
		$sql = "SELECT node_id FROM ".Config::Get("db.prefix")."nodes WHERE node_title LIKE ? AND node_active = 1";
		$aRows = $this->oDb->Select($sql, $sWord);
		return array_map( function($var){return $var["node_id"];}, $aRows);
	}
}	