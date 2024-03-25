<?
class ModuleComponent_EntityComponent extends Entity {
	public function getParams(){
		$sComponent = $this->getName();
		$aParams = Engine::GetInstance()->Component_GetConfig($sComponent);
		return $aParams;
	}
	public function getParam($sName){
		$aParams = $this->getParams();
		return $aParams[$sName];
	}
	public function getParamVar($sName){
		$aParam = $this->getParam($sName);
		return $aParam["default"];
	}
}	