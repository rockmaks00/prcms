<?
class ModuleTemplate_DbTemplate extends Db {
	public function Install(){
		if( !$this->oDb->CheckTableExists(Config::Get("db.prefix")."template_conditions") ){
			$sql = "CREATE TABLE IF NOT EXISTS `".Config::Get("db.prefix")."template_conditions` (
				`condition_id` int(11) NOT NULL AUTO_INCREMENT,
				`condition_template` varchar(250) NOT NULL,
				`condition_type` varchar(250) NOT NULL,
				`condition_var` varchar(250) NOT NULL,
				`condition_value` varchar(250) NOT NULL,
				`condition_node` int(11) NOT NULL,
				`condition_sort` int(11) NOT NULL,
				`condition_active` int(11) NOT NULL,
				PRIMARY KEY (`condition_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
			$this->oDb->Query($sql);
		}
	}
	public function AddCondition($oCondition){
		$sql = "INSERT INTO ".Config::Get("db.prefix")."template_conditions (
				condition_template,
				condition_type,
				condition_var,
				condition_value,
				condition_node,
				condition_sort,
				condition_active
			) 
			VALUES (?, ?, ?, ?, ?, ?, ?)";
		return $this->oDb->Query($sql, 
			$oCondition->getTemplate(),
			$oCondition->getType(),
			$oCondition->getVar(),
			$oCondition->getValue(),
			$oCondition->getNode(),
			$oCondition->getSort(),
			$oCondition->getActive()
		);
	}
	public function UpdateCondition($oCondition){
		$sql = "UPDATE ".Config::Get("db.prefix")."template_conditions SET 
				condition_template=?,
				condition_type=?,
				condition_var=?,
				condition_value=?,
				condition_node=?,
				condition_sort=?,
				condition_active=?
			WHERE condition_id=?";
		return $this->oDb->Query($sql, 
			$oCondition->getTemplate(),
			$oCondition->getType(),
			$oCondition->getVar(),
			$oCondition->getValue(),
			$oCondition->getNode(),
			$oCondition->getSort(),
			$oCondition->getActive(),
			$oCondition->getId()
		);
	}
	public function GetConditionsList() {
		$sql = "SELECT condition_id FROM ".Config::Get("db.prefix")."template_conditions ORDER BY condition_sort, condition_id";
		$aResult = array();
		$aRows = $this->oDb->Select($sql);
		$aResult = array_map(function($aRow){return $aRow["condition_id"];}, $aRows);
		return $aResult;
	}
	public function DeleteCondition($iId) {
		$sql = "DELETE FROM ".Config::Get("db.prefix")."template_conditions WHERE condition_id=?";
		if ($this->oDb->Query($sql, $iId)) return true;
		else return false;
	}
	public function GetConditionById($iId) {
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."template_conditions WHERE condition_id=?";
		$aRow=$this->oDb->SelectRow($sql, $iId);
		if ($aRow) return Engine::GetEntity('Template', $aRow, "Condition");
		else return null;
	}
	public function ActivateCondition($iId) {
		$sql = "UPDATE ".Config::Get("db.prefix")."template_conditions SET condition_active=1 WHERE condition_id=?";
		if ($this->oDb->Query($sql, $iId)) return true;
		else return false;
	}
	public function DeactivateCondition($iId) {
		$sql = "UPDATE ".Config::Get("db.prefix")."template_conditions SET condition_active=0 WHERE condition_id=?";
		if ($this->oDb->Query($sql, $iId)) return true;
		else return false;
	}
}	