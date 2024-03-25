<?
class ModuleUser_EntityGroup extends Entity {
	public function setId($iId){
		$this->_aData['group_id'] = $iId;
		while(list($iKey, $oAccess) = each( $this->_aData['group_acceses'] )){
			$this->_aData['group_acceses'][$iKey]->setGroup($iId);
		}
	}

	public function getAccess($sType){
		$aAccesses = $this->getAccesses();
		foreach( $aAccesses as $oAccess ){
			if( $oAccess->getType() == $sType ) return $oAccess->getValue();
		}
		if( preg_match("/^node(\d+)$/i", $sType) ) return false;
		return "D";
	}

	public function getAccesses($sMode){
		$aAccesses = $this->_aData['group_acceses'];
		if( empty($aAccesses) ) $aAccesses = Engine::GetInstance()->User_GetAccessesByGroup( $this->_aData['group_id'] );
		if( $sMode ){
			if( $sMode == "nodes" ){
				$aAccesses = array_filter($aAccesses, function($var){ return (preg_match("/^node(\d+)$/i", $var->getType())===1); });
			}elseif( $sMode == "actions" ){
				$aAccesses = array_filter($aAccesses, function($var){ return (preg_match("/^node(\d+)$/i", $var->getType())===0); });
			}
		}
		sort($aAccesses);
		return $aAccesses;
	}

	public function setAccesses($aAccesses){
		foreach ($aAccesses as $sType => $Value) {
			if( $sType == "node" && is_array($Value) ){
				
				foreach ($Value as $iNode => $sValue) {
					$oAccess = Engine::GetEntity('User', $aData, "Access");
					$oAccess->setGroup($this->getId());
					$oAccess->setType( "node".$iNode);
					$oAccess->setValue($Value);
					$this->_aData['group_acceses'][] = $oAccess;
				}
			}else{
				$oAccess = Engine::GetEntity('User', $aData, "Access");
				$oAccess->setGroup($this->getId());
				$oAccess->setType( $sType);
				$oAccess->setValue($Value);
				$this->_aData['group_acceses'][] = $oAccess;
			}
		}
	}
}