<?
class ModuleComponent extends Module {
	protected $oDb;
	protected $_aConfig = array();
	
	public function Init() {
		$this->oDb=Engine::GetDb(__CLASS__);
		$this->oDb->Install();
	}
	public function GetConfig($sName){
		if( !isset($this->_aConfig[$sName]) ){
			$sPath = "components/".$sName."/config/config.php";
			if( file_exists($sPath) ) include_once( $sPath );
		}
		return $this->_aConfig[$sName];
	}
	public function SetConfig($sName, $aConfig)	{
		$this->_aConfig[$sName] = $aConfig;
	}
	public function GetComponentById($iId) {
		if (false === ($data = $this->Cache_Get("component_{$iId}"))) {
			$data=$this->oDb->GetComponentById($iId);
			$this->Cache_Set("component_{$iId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	public function GetComponentByName($sName) {
		if (false === ($data = $this->Cache_Get("component_{$sName}"))) {
			$data=$this->oDb->GetComponentByName($sName);
			$this->Cache_Set("component_{$sName}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	public function GetComponents() {
		if (false === ($data = $this->Cache_Get("components"))) {
			$data=$this->oDb->GetComponents();
			$this->Cache_Set("components", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	public function Add(ModuleComponent_EntityComponent $oComponent) {
		$this->Cache_Delete("component_{$oComponent->getId()}");
		$this->Cache_Delete("component_{$oComponent->getName()}");
		$this->Cache_Delete("components");
		if ($iId=$this->oDb->Add($oComponent)){
			$oComponent->setId($iId);
		}
		return $oComponent;
	}
	public function Update(ModuleComponent_EntityComponent $oComponent) {
		$this->Cache_Delete("component_{$oComponent->getId()}");
		$this->Cache_Delete("component_{$oComponent->getName()}");
		$this->Cache_Delete("components");
		return $this->oDb->Update($oComponent);
	}
	public function Delete($iComponentId) {
		$this->Cache_Delete("component_{$this->GetComponentById($iComponentId)->getName()}");
		$this->Cache_Delete("component_{$iComponentId}");
		$this->Cache_Delete("components");
		return $this->oDb->Delete($iComponentId);
	}
	public function Activate($iComponentId) {
		$this->Cache_Delete("component_{$this->GetComponentById($iComponentId)->getName()}");
		$this->Cache_Delete("component_{$iComponentId}");
		$this->Cache_Delete("components");
		return $this->oDb->Activate($iComponentId);
	}
	public function Deactivate($iComponentId) {
		$this->Cache_Delete("component_{$this->GetComponentById($iComponentId)->getName()}");
		$this->Cache_Delete("component_{$iComponentId}");
		$this->Cache_Delete("components");
		return $this->oDb->Deactivate($iComponentId);
	}
	
	
	/*--------PARAMS BEGIN--------*/
	public function GetParamById($iParamId) {
		if (false === ($data = $this->Cache_Get("component_param_{$iParamId}"))) {
			$data = $this->oDb->GetParamById($iParamId);
			$this->Cache_Set("component_param_{$iParamId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	public function AddParam(ModuleComponent_EntityParam $oParam) {
		$this->Cache_Delete("component_params");
		if ($iParamId = $this->oDb->AddParam($oParam)){
			$oParam->setId($iParamId);
		}
		return $oParam;
	}
	public function UpdateParam(ModuleComponent_EntityParam $oParam) {
		$this->Cache_Delete("component_param_{$oParam->getId()}");
		$this->Cache_Delete("component_params");
		return $this->oDb->UpdateParam($oParam);
	}
	public function DeleteParam($iParamId) {
		$this->Cache_Delete("component_param_{$iParamId}");
		$this->Cache_Delete("component_params");
		return $this->oDb->DeleteParam($iParamId);
	}

	public function GetParams(){
		if (false === ($data = $this->Cache_Get("component_params"))) {
			$aIds = $this->oDb->GetParams();
			$data = array();
			foreach ($aIds as $iId){
				$data[] = $this->GetParamById($iId);
			}
			$this->Cache_Set("component_params", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	public function GetParamsByNode($iNode) {
		$aParamsId = $this->oDb->GetParamsByNode($iNode);
		$data = array(); 
		foreach ($aParamsId as $iParamId) {
			$oParam = $this->getParamById($iParamId);
			$data[] = $oParam;
		}
		return $data;
	}
	public function GetParamsByNodeComponent($iNode, $iComponent) {
		$aParamsId = $this->oDb->GetParamsByNodeComponent($iNode, $iComponent);
		$data = array(); 
		foreach ($aParamsId as $iParamId) {
			$oParam = $this->getParamById($iParamId);
			$data[ $oParam->getVar() ] = $oParam;
		}
		return $data;
	}
	public function GetParamByNodeComponentVar($iNode, $iComponent, $sVar) {
		$iParamId = $this->oDb->GetParamByNodeComponentVar($iNode, $iComponent, $sVar);
		return $this->getParamById($iParamId);
	}
	public function SaveNodeParamsFromArray($oNode, $aParams){
		$aNodeParams = $this->Component_GetParamsByNodeComponent($oNode->getId(), $oNode->getComponent());
		foreach($aParams as $sName=>$aParam){
			$oParam = $aNodeParams[$sName];
			if( !$oParam ){
				$oParam = Engine::GetEntity("Component", null, "Param");
				$oParam->setNode($oNode->getId());
				$oParam->setComponent($oNode->getComponent());
				$oParam->setvar($sName);
			}
			$oParam->setVal($aParams[$sName]);

			if (!$oParam->getId()){
				$this->Component_AddParam($oParam);
			}else{
				$this->Component_UpdateParam($oParam);
			}
		}
	}
	/*--------PARAMS END--------*/
}