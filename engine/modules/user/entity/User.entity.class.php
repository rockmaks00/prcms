<?
class ModuleUser_EntityUser extends Entity {
	public function getGroup(){
		$iGroupId = $this->_aData['user_group'];
		
		$oGroup = Engine::GetInstance()->User_GetGroupById($iGroupId);
		if(!$oGroup) $oGroup = Engine::GetEntity('User', $aRow, "Group");
		return $oGroup;
	}	

	public function setPassword($sPassFirst, $sPassSecond) {
		if( is_string($sPassFirst) && !empty($sPassFirst) && $sPassFirst===$sPassSecond ){
			$this->_aData['user_password'] = md5($sPassFirst);
			return;
		}
		$this->_aData['user_password'] = false;
	}




	public function getAccess($sType){
		return $this->getGroup()->getAccess($sType);
	}
	public function getAccesses($sMode){
		return $this->getGroup()->getAccesses($sMode);
	}

}