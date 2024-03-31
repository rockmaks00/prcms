<?
class ModuleComponent_DbComponent extends Db {
	public function Install(){
		if( !$this->oDb->CheckTableExists(Config::Get("db.prefix")."components") ){
			$sql = "CREATE TABLE IF NOT EXISTS `".Config::Get("db.prefix")."components` (
				`component_id` int(11) NOT NULL AUTO_INCREMENT,
				`component_title` varchar(250) NOT NULL,
				`component_name` varchar(250) NOT NULL,
				`component_active` int(11) NOT NULL DEFAULT '1',
				PRIMARY KEY (`component_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
			$this->oDb->Query($sql);
			$sql = "INSERT INTO `".Config::Get("db.prefix")."components` (
				`component_id`,
				`component_title`,
				`component_name`,
				`component_active`
			) VALUES (1, 'Административная панель', 'admin', 1),
				(2, 'Страница', 'page', 1),
				(3, 'Карта сайта', 'sitemap', 1),
				(4, 'Обратная связь', 'feedback', 1),
				(5, 'Новости', 'news', 1),
				(6, 'Галерея изображений', 'gallery', 1),
				(7, 'Поиск', 'search', 1),
				(8, 'Импорт CSV', 'import', 1)";
			$this->oDb->Query($sql);
		}
		if( !$this->oDb->CheckTableExists(Config::Get("db.prefix")."components_params") ){
			$sql = "CREATE TABLE IF NOT EXISTS `".Config::Get("db.prefix")."components_params` (
				`param_id` int(11) NOT NULL AUTO_INCREMENT,
				`param_node` int(11) NOT NULL,
				`param_component` int(11) NOT NULL,
				`param_var` varchar(250) NOT NULL,
				`param_val` text NOT NULL,
				PRIMARY KEY (`param_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
			$this->oDb->Query($sql);
		}
	}
	public function GetComponentById($iId){
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."components WHERE component_id=?";
		$aRow=$this->oDb->SelectRow($sql, $iId);
		if ($aRow) return Engine::GetEntity('Component', $aRow);
		else return null;
	}
	public function GetComponentByName($sName){
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."components WHERE component_name=?";
		$aRow=$this->oDb->SelectRow($sql, $sName);
		if ($aRow) return Engine::GetEntity('Component', $aRow);
		else return null;
	}
	public function GetComponents(){
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."components ORDER BY component_title";
		$aResult = array();
		$aRows=$this->oDb->Select($sql);
		foreach ($aRows as $aRow){
			$aResult[]=Engine::GetEntity('Component',$aRow);
		}
		return $aResult;
	}
	public function Add($oComponent){
		$sql = "INSERT INTO ".Config::Get("db.prefix")."components (
				component_title,
				component_name,
				component_active
			) 
			VALUES(?, ?, ?)
		";
		return $this->oDb->Query($sql, 
			$oComponent->getTitle(),
			$oComponent->getName(),
			$oComponent->getActive()
		);
	}
	public function Update($oComponent){
		$sql = "UPDATE ".Config::Get("db.prefix")."components SET 
				component_title=?,
				component_name=?,
				component_active=?
			WHERE component_id=?
		";
		return $this->oDb->Query($sql, 
			$oComponent->getTitle(),
			$oComponent->getName(),
			$oComponent->getActive(),
			$oComponent->getId()
		);
	}
	public function Activate($iComponentId) {
		$sql = "UPDATE ".Config::Get("db.prefix")."components SET component_active=1 WHERE component_id=?";
		if ($this->oDb->Query($sql, $iComponentId)) return true;
		else return false;
	}
	public function Deactivate($iComponentId) {
		$sql = "UPDATE ".Config::Get("db.prefix")."components SET component_active=0 WHERE component_id=?";
		if ($this->oDb->Query($sql, $iComponentId)) return true;
		else return false;
	}
	public function Delete($iComponentId) {
		$sql = "DELETE FROM ".Config::Get("db.prefix")."components WHERE component_id=?";
		if ($this->oDb->Query($sql, $iComponentId)) return true;
		else return false;
	}
	
	
	/*------PARAMS BEGIN-----*/
	public function GetParamById($iId) {
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."components_params WHERE param_id=?";
		$aRow=$this->oDb->SelectRow($sql, $iId);
		if ($aRow) return Engine::GetEntity('Component', $aRow, "Param");
		else return null;
	}
	public function AddParam($oParam){
		$sql = "INSERT INTO ".Config::Get("db.prefix")."components_params (
				param_node,
				param_component,
				param_var,
				param_val
			) 
			VALUES(?, ?, ?, ?)
		";
		return $this->oDb->Query($sql, 
			$oParam->getNode(),
			$oParam->getComponent(),
			$oParam->getVar(),
			$oParam->getVal()
		);
	}
	public function UpdateParam($oParam){
		$sql = "UPDATE ".Config::Get("db.prefix")."components_params SET 
				param_node=?,
				param_component=?,
				param_var=?,
				param_val=?
			WHERE param_id=?
		";
		return $this->oDb->Query($sql, 
			$oParam->getNode(),
			$oParam->getComponent(),
			$oParam->getVar(),
			$oParam->getVal(),
			$oParam->getId()
		);
	}
	public function DeleteParam($iParamId) {
		$sql = "DELETE FROM ".Config::Get("db.prefix")."components_params WHERE param_id=?";
		if ($this->oDb->Query($sql, $iParamId)) return true;
		else return false;
	}
	
	public function GetParams() {
		$sql = "SELECT param_id FROM ".Config::Get("db.prefix")."components_params ORDER BY param_node";
		$aRows = $this->oDb->Select($sql);
		$aResult = array_map(function($aRow){return $aRow["param_id"];}, $aRows);
		return $aResult;
	}
	public function GetParamsByNode($iNode) {
		$sql = "SELECT param_id FROM ".Config::Get("db.prefix")."components_params WHERE param_node=?";
		$aRows = $this->oDb->Select($sql, $iNode);
		$aResult = array_map(function($aRow){return $aRow["param_id"];}, $aRows);
		return $aResult;
	}
	public function GetParamsByNodeComponent($iNode, $iComponent){
		$sql = "SELECT param_id FROM ".Config::Get("db.prefix")."components_params WHERE param_node=? AND param_component=?";
		$aRows = $this->oDb->Select($sql, $iNode, $iComponent);
		$aResult = array_map(function($aRow){return $aRow["param_id"];}, $aRows);
		return $aResult;
	}
	public function GetParamByNodeComponentVar($iNode, $iComponent, $sVar){
		$sql = "SELECT param_id FROM ".Config::Get("db.prefix")."components_params WHERE param_node=? AND param_component=? AND param_var=?";
		$aRow=$this->oDb->SelectRow($sql, $iNode, $iComponent, $sVar);
		return $aRow["param_id"];
	}
	/*----PARAMS END-----*/
}	