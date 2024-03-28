<?
abstract class Hook extends EngineObject {
		
	public function __construct() {		
		
	}
	
	public function GetParamsCurrent($iHookId){
		try { $iId = $iHookId->getId();	} catch (Exception $e) { $iId = $iHookId; } //если передали объект
		$this->aParamsCurrent = $this->Hook_GetParamsByHook($iHookId);
		return $this->aParamsCurrent;
	}

	public function __call($sName,$aArgs) {
		return Engine::getInstance()->_CallModule($sName,$aArgs);
	}
}