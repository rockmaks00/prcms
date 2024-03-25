<?
class ModuleUser_EntityAccess extends Entity {
	public function getNode(){
		$sType = $this->_aData["access_type"];
		if (preg_match("/^node(\d+)$/i",$sType,$aMatch)) {
			return intval($aMatch[1]);
		}
		return 0;
	}
}	