<?
class ComponentSearch_ModuleSearch_DbSearch extends Db {

	public function Install(){
		if( !$this->oDb->CheckTableExists(Config::Get("db.prefix")."com_search") ){
			$sql = "CREATE TABLE IF NOT EXISTS `".Config::Get("db.prefix")."com_search` (
				`search_id` int(11) NOT NULL AUTO_INCREMENT,
				`search_node` int(11) NOT NULL,
				`search_component` int(11) NOT NULL,
				PRIMARY KEY (`search_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
			$this->oDb->Query($sql);
		}
	}
	public function Select($iId){
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."com_search WHERE search_node = ?";
		$aRows = $this->oDb->Select($sql, $iId);
		foreach ($aRows as $aRow){
			$aResult[ $aRow["search_component"] ] = Engine::GetEntity('ComponentSearch_Search', $aRow);
		}
		return $aResult;
	}
	public function Delete($iId){
		$sql = "DELETE FROM ".Config::Get("db.prefix")."com_search WHERE search_node = ?";
		if ($this->oDb->Query($sql, $iId)) return true;
		else return false;
	}
	public function Insert($oSearch){
		$sql = "INSERT INTO ".Config::Get("db.prefix")."com_search (search_node, search_component) VALUES (?, ?)";
		return $this->oDb->Query($sql, 
			$oSearch->getNode(),
			$oSearch->getComponent()
		);
	}
}	