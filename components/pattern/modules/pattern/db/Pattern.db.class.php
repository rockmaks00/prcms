<?
class ComponentPattern_ModulePattern_DbPattern extends Db {
	
	public function Install(){
		if( !$this->oDb->CheckTableExists(Config::Get("db.prefix")."com_patterns") ){
			$sql = "CREATE TABLE IF NOT EXISTS `".Config::Get("db.prefix")."com_patterns` (
				`pattern_id` int(11) NOT NULL AUTO_INCREMENT,
				`pattern_title` varchar(250) NOT NULL,
				`pattern_coords` text NOT NULL,
				`pattern_desc` text NOT NULL,
				`pattern_active` int(1) NOT NULL,
				`pattern_node` int(11) NOT NULL,
				PRIMARY KEY (`pattern_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8";
			$this->oDb->Query($sql);
		}
		if( !$this->oDb->CheckTableExists(Config::Get("db.prefix")."com_patterns_map") ){
			$sql = "CREATE TABLE IF NOT EXISTS `pr_com_patterns_map` (
				`map_id` int(11) NOT NULL AUTO_INCREMENT,
				`map_img` varchar(250) NOT NULL,
				`map_desc` text NOT NULL,
				`map_node` int(11) NOT NULL,
				PRIMARY KEY (`map_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8";
			$this->oDb->Query($sql);
		}
	}

	public function GetMapById($iId) {
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."com_patterns_map WHERE map_id=?";
		$aRow=$this->oDb->SelectRow($sql, $iId);
		if ($aRow) return Engine::GetEntity('ComponentPattern_Pattern', $aRow, "map");
		else return null;
	}

	public function GetMapByNode($iNodeId) {
		$sql = "SELECT map_id FROM ".Config::Get("db.prefix")."com_patterns_map WHERE map_node=?";
		$data=$this->oDb->SelectRow($sql, $iNodeId);
		return $data["map_id"];
	}

	public function AddMap($oMap){
		$sql = "INSERT INTO ".Config::Get("db.prefix")."com_patterns_map (
				map_img,
				map_desc,
				map_node,
			) 
			VALUES(?, ?, ?)
		";
		return $this->oDb->Query($sql, 
			$oMap->getImg(),
			$oMap->getDesc(),
			$oMap->getNode()
		);
	}

	public function UpdateMap($oMap){
		$sql = "UPDATE ".Config::Get("db.prefix")."com_patterns_map SET 
				map_img=?,
				map_desc=?,
				map_node=?
			WHERE map_id=?
		";
		return $this->oDb->Query($sql, 
			$oMap->getImg(),
			$oMap->getDesc(),
			$oMap->getNode(),
			$oMap->getId()
		);
	}
	public function GetPatternById($iId) {
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."com_patterns WHERE pattern_id=?";
		$aRow=$this->oDb->SelectRow($sql, $iId);
		if ($aRow) return Engine::GetEntity('ComponentPattern_Pattern', $aRow);
		else return null;
	}

	public function GetPatternsByNode($iNodeId) {
		
		$sql = "SELECT pattern_id FROM ".Config::Get("db.prefix")."com_patterns WHERE pattern_node=?";
		$aRows=$this->oDb->Select($sql, $iNodeId);
		$data = array_map(function($aRow){ return $aRow["pattern_id"]; }, $aRows);
		return $data;
	}

	public function Add($oPattern){
		$sql = "INSERT INTO ".Config::Get("db.prefix")."com_patterns (
				pattern_title,
				pattern_coords,
				pattern_desc,
				pattern_active,
				pattern_node
			) 
			VALUES(?, ?, ?, ?, ?)
		";
		return $this->oDb->Query($sql,
			$oPattern->getTitle(),
			$oPattern->getCoords(),
			$oPattern->getDesc(),
			$oPattern->getActive(),
			$oPattern->getNode()
		);
	}

	public function Update($oPattern){
		$sql = "UPDATE ".Config::Get("db.prefix")."com_patterns SET 
				pattern_title=?,
				pattern_coords=?,
				pattern_desc=?,
				pattern_active=?,
				pattern_node=?		
			WHERE pattern_id=?
		";
		return $this->oDb->Query($sql,
			$oPattern->getTitle(),
			$oPattern->getCoords(),
			$oPattern->getDesc(),
			$oPattern->getActive(),
			$oPattern->getNode(),
			$oPattern->getId()
		);
	}

	public function Delete($iPatternId){
		$sql = "DELETE FROM ".Config::Get("db.prefix")."com_patterns 
			WHERE pattern_id=?";
		$this->oDb->Query($sql, $iPatternId);
		return true;
	}

	public function Activate($iPatternId){
		$sql = "UPDATE ".Config::Get("db.prefix")."com_patterns SET 
				page_active=1
			WHERE pattern_id=?
		";
		return $this->oDb->Query($sql, $iPatternId);
	}

	public function Deactivate($iPatternId){
		$sql = "UPDATE ".Config::Get("db.prefix")."com_patterns SET 
				page_active=0
			WHERE pattern_id=?
		";
		return $this->oDb->Query($sql, $iPatternId);
	}

	public function Search($sWord){
		$sWord = "%".$sWord."%";
		$sql = "SELECT pattern_id FROM ".Config::Get("db.prefix")."com_patterns WHERE pattern_desc LIKE ?";
		$aRows = $this->oDb->Select($sql, $sWord);
		return array_map( function($var){ return $var["pattern_id"]; }, $aRows );
	}
}	