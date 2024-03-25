<?
class ModuleHook_EntityHook extends Entity {

	public function getComponent(){
		if( !isset($this->_aData["hook_component"]) ) $this->ParseType();
		return $this->_aData["hook_component"];
	}
	public function getName(){
		if( !isset($this->_aData["hook_name"]) ) $this->ParseType();
		return $this->_aData["hook_name"];
	}
	public function ParseType(){
		$aType = explode("_", $this->getType());
		$sName = strtolower( str_replace("Hook", "", $aType[0]) );
		$sComponent = strtolower( str_replace("Component", "", $aType[1]) );
		$this->setName($sName);
		$this->setComponent($sComponent);
	}

	public function getCurrentParams(){
		if( !isset( $this->_aData["hook_current_params"] ) ){
			$aParams = Engine::GetInstance()->Hook_GetParamsByHook( $this->getId() );
			$this->setCurrentParams($aParams);
		}
		return $this->_aData["hook_current_params"];
	}
	public function getCurrentParam($sName){
		$aParams = $this->getCurrentParams();
		return $aParams[$sName];
	}

	public function getDefaultParams(){
		return Engine::GetInstance()->Hook_GetConfig($this->getName(), $this->getComponent());
	}
	public function getDefaultParam($sName){
		$aParams = $this->getDefaultParams();
		return $aParams[$sName];
	}

	public function getParam($sName){
		$sCurrentParam = $this->getCurrentParam($sName);
		if($sCurrentParam) 
			return $sCurrentParam->getValue();
		$sDefaultParam = $this->getDefaultParam($sName);
		return $sDefaultParam["default"];
	}

	public function getParamsArray(){
		if( !isset( $this->_aData["hook_params_array"] ) ){
			$aResult=array();
			foreach ($this->getDefaultParams() as $sParam => $aArray) {
				$aResult[$sParam] = $this->getParam($sParam);
			}
			$this->setParamsArray($aResult);
		}
		return $this->_aData["hook_params_array"];
	}

	public function getGroupObject(){
		if( !isset( $this->_aData["hook_group_object"] ) ){
			$oGroup = Engine::GetInstance()->Hook_GetGroupById($this->getGroup());
			if(empty($oGroup)) $oGroup = Engine::GetEntity("Hook",null,"Group");
			$this->setGroupObject($oGroup);
		}
		return $this->_aData["hook_group_object"];
	}

}