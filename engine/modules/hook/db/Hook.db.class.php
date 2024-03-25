<?
class ModuleHook_DbHook extends Db {
	public function Install(){
		if( !$this->oDb->CheckTableExists(Config::Get("db.prefix")."hooks") ){
			$sql = "CREATE TABLE IF NOT EXISTS `".Config::Get("db.prefix")."hooks` (
				`hook_id` int(11) NOT NULL AUTO_INCREMENT,
				`hook_title` varchar(250) NOT NULL,
				`hook_desc` text NOT NULL,
				`hook_type` varchar(250) NOT NULL,
				`hook_group` int(11) NOT NULL,
				`hook_sort` int(11) NOT NULL,
				`hook_active` int(1) NOT NULL,
				PRIMARY KEY (`hook_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8";
			$this->oDb->Query($sql);
		}
		if( !$this->oDb->CheckTableExists(Config::Get("db.prefix")."hook_nodes") ){
			$sql = "CREATE TABLE IF NOT EXISTS `".Config::Get("db.prefix")."hook_nodes` (
				`node_id` int(11) NOT NULL AUTO_INCREMENT,
				`node_node` varchar(11) NOT NULL,
				`node_hook` int(11) NOT NULL,
				PRIMARY KEY (`node_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8";
			$this->oDb->Query($sql);
		}
		if( !$this->oDb->CheckTableExists(Config::Get("db.prefix")."hook_params") ){
			$sql = "CREATE TABLE IF NOT EXISTS `".Config::Get("db.prefix")."hook_params` (
				`param_id` int(11) NOT NULL AUTO_INCREMENT,
				`param_hook` int(11) NOT NULL,
				`param_name` varchar(50) NOT NULL,
				`param_value` varchar(250) NOT NULL,
				PRIMARY KEY (`param_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8";
			$this->oDb->Query($sql);
		}
		if( !$this->oDb->CheckTableExists(Config::Get("db.prefix")."hook_groups") ){
			$sql = "CREATE TABLE IF NOT EXISTS `".Config::Get("db.prefix")."hook_groups` (
				`group_id` int(11) NOT NULL AUTO_INCREMENT,
				`group_name` varchar(50) NOT NULL,
				`group_desc` varchar(250) NOT NULL,
				PRIMARY KEY (`group_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8";
			$this->oDb->Query($sql);
		}
	}

	public function Add($oHook){
		$sql = "INSERT INTO ".Config::Get("db.prefix")."hooks (
				hook_title,
				hook_desc,
				hook_type,
				hook_group,
				hook_sort,
				hook_active
			) 
			VALUES(?, ?, ?, ?, ?, ?)
		";
		return $this->oDb->Query($sql,
			$oHook->getTitle(),
			$oHook->getDesc(),
			$oHook->getType(),
			$oHook->getGroup(),
			$oHook->getSort(),
			$oHook->getActive()
		);
	}
	
	public function Update($oHook){
		$sql = "UPDATE ".Config::Get("db.prefix")."hooks SET 
				hook_title=?,
				hook_desc=?,
				hook_type=?,
				hook_group=?,
				hook_sort=?,
				hook_active=?			
			WHERE hook_id=?
		";
		return $this->oDb->Query($sql, 
			$oHook->getTitle(),
			$oHook->getDesc(),
			$oHook->getType(),
			$oHook->getGroup(),
			$oHook->getSort(),
			$oHook->getActive(),
			$oHook->getId()
		);
	}
	public function GetList() {
		$sql = "SELECT hook_id FROM ".Config::Get("db.prefix")."hooks ORDER BY hook_sort, hook_id";
		$aResult = array();
		$aRows = $this->oDb->Select($sql);
		$aResult = array_map(function($aRow){return $aRow["hook_id"];}, $aRows);
		return $aResult;
	}
	public function GetHookById($iId) {
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."hooks WHERE hook_id=?";
		$aRow=$this->oDb->SelectRow($sql, $iId);
		if ($aRow) return Engine::GetEntity('Hook', $aRow);
		else return null;
	}
	public function GetHooksByNodeGroup($iNodeId, $sGroup){//поиск по ноду и группе
		$sql = "SELECT hook_id FROM 
			".Config::Get("db.prefix")."hooks AS h 
				INNER JOIN 
			".Config::Get("db.prefix")."hook_nodes AS n
				ON 
			h.hook_id = n.node_hook
				WHERE
			n.node_node IN ('all', ?) AND h.hook_group = ? AND h.hook_active = 1 ORDER BY hook_sort, hook_id";
		$aRows = $this->oDb->Select($sql, $iNodeId, $sGroup);
		return array_map(function($aRow){return $aRow["hook_id"];}, $aRows);
	}
	public function Activate($iHookId) {
		$sql = "UPDATE ".Config::Get("db.prefix")."hooks SET hook_active=1 WHERE hook_id=?";
		if ($this->oDb->Query($sql, $iHookId)) return true;
		else return false;
	}
	public function Deactivate($iHookId) {
		$sql = "UPDATE ".Config::Get("db.prefix")."hooks SET hook_active=0 WHERE hook_id=?";
		if ($this->oDb->Query($sql, $iHookId)) return true;
		else return false;
	}
	public function Delete($iHookId) {
		$sql = "DELETE FROM ".Config::Get("db.prefix")."hooks WHERE hook_id=?";
		if ($this->oDb->Query($sql, $iHookId)) return true;
		else return false;
	}
	

	/*PARAMS*/
	public function GetParamsByHook($iHookId){
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."hook_params WHERE param_hook=? ORDER BY param_id";
		$aRows=$this->oDb->Select($sql, $iHookId);
		$aResult = array();
		foreach ($aRows as $aRow) {
			$aResult[ $aRow["param_name"] ] = Engine::GetEntity('Hook', $aRow, "Param");
		}
		return $aResult;
	}
	public function DeleteParamsByHook($iHookId){
		$sql = "DELETE FROM ".Config::Get("db.prefix")."hook_params WHERE param_hook=?";
		if( $this->oDb->Query($sql, $iHookId) ) return true;
		else return false;
	}
	public function AddParam($oParam){
		$sql = "INSERT INTO ".Config::Get("db.prefix")."hook_params (
				param_hook,
				param_name,
				param_value
			) 
			VALUES(?, ?, ?)
		";
		return $this->oDb->Query($sql,
			$oParam->getHook(),
			$oParam->getName(),
			$oParam->getValue()
		);
	}


	/*NODES*/
	public function GetNodesByHook($iHookId){
		$sql = "SELECT node_node FROM ".Config::Get("db.prefix")."hook_nodes WHERE node_hook=?";
		$aRows=$this->oDb->Select($sql, $iHookId);
		return array_map(function($aRow){ return $aRow["node_node"]; }, $aRows);
	}
	public function DeleteNodesByHook($iHookId){
		$sql = "DELETE FROM ".Config::Get("db.prefix")."hook_nodes WHERE node_hook=?";
		if( $this->oDb->Query($sql, $iHookId) ) return true;
		return false;
	}
	public function AddNodesToHook($iNodeId, $iHookId){
		$sql = "INSERT INTO ".Config::Get("db.prefix")."hook_nodes (
			node_node,
			node_hook
			)
		VALUES (?, ?)";
		return $this->oDb->Query($sql, $iNodeId, $iHookId);
	}

	/*GROUPS*/
	public function GetGroupById($iId){
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."hook_groups WHERE group_id=?";
		$aRow=$this->oDb->SelectRow($sql, $iId);
		if ($aRow) return Engine::GetEntity('Hook', $aRow, "Group");
		else return null;
	}
	public function GetGroupByName($sGroupName){
		$sql = "SELECT group_id FROM ".Config::Get("db.prefix")."hook_groups WHERE group_name=?";
		$aRow=$this->oDb->SelectRow($sql, $sGroupName);
		if ($aRow) return $aRow["group_id"];
		else return null;
	}
	public function AddGroup($oGroup){
		$sql = "INSERT INTO ".Config::Get("db.prefix")."hook_groups (
				group_name,
				group_desc
			) 
			VALUES(?, ?)
		";
		return $this->oDb->Query($sql,
			$oGroup->getName(),
			$oGroup->getDesc()
		);
	}
	public function UpdateGroup($oGroup){
		$sql = "UPDATE ".Config::Get("db.prefix")."hook_groups SET 
				group_name=?,
				group_desc=?
			WHERE group_id=?
		";
		return $this->oDb->Query($sql, 
			$oGroup->getName(),
			$oGroup->getDesc(),
			$oGroup->getId()
		);
	}
	public function DeleteGroup($iGroupId){
		$sql = "DELETE FROM ".Config::Get("db.prefix")."hook_groups WHERE group_id=?";
		if ($this->oDb->Query($sql, $iGroupId)) return true;
		else return false;
	}
	public function GetGroups() {
		$sql = "SELECT group_id FROM ".Config::Get("db.prefix")."hook_groups ORDER BY group_id";
		$aResult = array();
		$aRows = $this->oDb->Select($sql);
		$aResult = array_map(function($aRow){return $aRow["group_id"];}, $aRows);
		return $aResult;
	}
}	