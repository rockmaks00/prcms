<?
class ModuleHook extends Module {
	protected $oDb;
	protected $_aConfig = array();

	public function Init() {
		$this->oDb=Engine::GetDb(__CLASS__);
		$this->oDb->Install();
	}

	public function GetConfig($sName, $sComponent=null){
		$sKey = ( $sComponent ? "Component".ucfirst($sComponent)."_" : "" );
		$sKey.= "Hook".$sName;
		if( !isset($this->_aConfig[$sKey]) ){
			$sPath = ( $sComponent ? 
					"components/".$sComponent."/config/Hook".ucfirst($sName).".config.php"
				:
					"hooks/".$sName."/config/config.php"
				);
			if( file_exists($sPath) ) include_once( $sPath );
		}
		return $this->_aConfig[$sKey];
	}
	public function SetConfig($sName, $sComponent=null, $aConfig) {
		$sKey = ( $sComponent ? "Component".ucfirst($sComponent)."_" : "" );
		$sKey.= "Hook".$sName;
		$this->_aConfig[$sKey] = $aConfig;
	}

	public function Add(ModuleHook_EntityHook $oHook) {
		$this->Cache_Delete("hooks");
		if ($iId=$this->oDb->Add($oHook)){
			$oHook->setId($iId);
		}
		return $oHook;
	}
	public function Update(ModuleHook_EntityHook $oHook){
		$this->Cache_Delete("hook_{$oHook->getId()}");
		$this->Cache_Delete("hooks");
		return $this->oDb->Update($oHook);
	}
	public function Delete($iHookId) {
		$this->Cache_Delete("hook_{$iHookId}");
		$this->Cache_Delete("hooks");
		$this->DeleteParamsByHook( $iHookId );
		$this->DeleteNodesByHook( $iHookId );
		return $this->oDb->Delete( $iHookId );
	}
	public function GetList() {
		if (false === ($data = $this->Cache_Get("hooks"))) {	
			$aResult=$this->oDb->GetList(); //только id
			$data = array();
			foreach ($aResult as $iId) {
				$data[] = $this->GetHookById($iId);
			}
			$this->Cache_Set("hooks", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	public function GetHookById($iId) {
		if (false === ($data = $this->Cache_Get("hook_{$iId}"))) {
			$data=$this->oDb->GetHookById($iId);
			$this->Cache_Set("hook_{$iId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	public function GetHooksByNodeGroup($iNodeId, $sGroup) {
		$aHookId = $this->oDb->GetHooksByNodeGroup($iNodeId, $sGroup);
		$aResult = array();
		foreach ($aHookId as $iId) {
			$aResult[] = $this->GetHookById($iId);
		}
		return $aResult;
	}
	public function Activate($iHookId) {
		$this->Cache_Delete("hook_{$iHookId}");
		return $this->oDb->Activate($iHookId);
	}
	public function Deactivate($iHookId) {
		$this->Cache_Delete("hook_{$iHookId}");
		return $this->oDb->Deactivate($iHookId);
	}
	public function Sort(ModuleHook_EntityHook $oHook, $sAction) {

		$aHooks=$this->GetList();
		foreach($aHooks as $i=>$oHookEach){
			$oHookEach->setSort(($i+1));
			if ($oHookEach->getId()==$oHook->getId()) $index=$i;
		}
		if ($sAction=="up") $oSiblingHook = $aHooks[$index-1];
		if ($sAction=="down") $oSiblingHook = $aHooks[$index+1];
		if (isset( $oSiblingHook )){
			$tmp=$oSiblingHook->getSort();
			$oSiblingHook->setSort($aHooks[$index]->getSort());
			$aHooks[$index]->setSort($tmp);
		}
		foreach($aHooks as $oHookEach){
			$this->Update($oHookEach);
		}
		return true;
	}

	public function GetAvailableHookList(){
		$aResult = array();
		$aComponents = $this->Component_GetComponents();
		foreach ($aComponents as $oComponent) {
			$aHooks = glob("components/{$oComponent->getName()}/hooks/Hook*.class.php");
			foreach($aHooks as $sHook){
				preg_match("/^components\/(\w+)\/hooks\/Hook(\w+).class.php$/i", $sHook, $aMatches);
				$aResult[] = array(
					"title" => ucfirst($aMatches[2])." (в компоненте ".ucfirst($aMatches[1]).")",
					"name" => "Hook".ucfirst($aMatches[2])."_Component".ucfirst($aMatches[1])
				);
			}
		}
		$aHooks = glob("hooks/*", GLOB_ONLYDIR);
		foreach($aHooks as $sHook){
			$sName = str_replace("hooks/", "", $sHook);
			if(file_exists($sHook."/Hook".ucfirst($sName).".class.php")){
				$aResult[] = array(
					"title" => ucfirst($sName),
					"name" => "Hook".ucfirst($sName)
				);
			}
		}
		return $aResult;
	}


	/*PARAMS*/

	public function GetParamsByHook($iHookId){
		if (false === ($data = $this->Cache_Get("hook_params_{$iHookId}"))) {
			$data=$this->oDb->GetParamsByHook($iHookId);
			$this->Cache_Set("hook_params_{$iHookId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	public function DeleteParamsByHook($iHookId){
		$this->Cache_Delete("hook_params_{$iHookId}");
		return $this->oDb->DeleteParamsByHook($iHookId);
	}
	public function AddParam($oParam){
		$this->Cache_Delete("hook_params_{$oParam->getHook()}");
		return $this->oDb->AddParam($oParam);
	}


	/*NODES*/
	public function GetNodesByHook($iHookId){
		if (false === ($data = $this->Cache_Get("hook_nodes_{$iHookId}"))) {
			$data=$this->oDb->GetNodesByHook($iHookId);
			$this->Cache_Set("hook_nodes_{$iHookId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	public function DeleteNodesByHook($iHookId){
		$this->Cache_Delete("hook_nodes_{$iHookId}");
		return $this->oDb->DeleteNodesByHook($iHookId);
	}
	public function AddNodesToHook($mNodeIds, $iHookId){
		$this->Cache_Delete("hook_nodes_{$iHookId}");
		if( empty($mNodeIds) ) $mNodeIds = "all";
		if( !is_array($mNodeIds) ) $mNodeIds = array($mNodeIds);
		foreach ($mNodeIds as $iId) {
			$this->oDb->AddNodesToHook($iId, $iHookId); 
		}
	}

	/*GROUPS*/
	public function GetGroupById($iGroupId){
		if (false === ($data = $this->Cache_Get("hook_group_{$iGroupId}"))) {
			$data=$this->oDb->GetGroupById($iGroupId);
			$this->Cache_Set("hook_group_{$iGroupId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	public function GetGroupByName($sGroupName){
		$iId=$this->oDb->GetGroupByName($sGroupName);
		$data=$this->GetGroupById($iId);
		return $data;
	}
	public function AddGroup(ModuleHook_EntityGroup $oGroup){
		$this->Cache_Delete("hook_groups");
		if ($iId=$this->oDb->AddGroup($oGroup)){
			$oGroup->setId($iId);
		}
		return $oGroup;
	}
	public function UpdateGroup(ModuleHook_EntityGroup $oGroup){
		$this->Cache_Delete("hook_groups");
		$this->Cache_Delete("hook_group_{$oGroup->getId()}");
		return $this->oDb->UpdateGroup($oGroup);
	}
	public function DeleteGroup($iGroupId){
		$this->Cache_Delete("hook_groups");
		$this->Cache_Delete("hook_group_{$iGroupId}");
		return $this->oDb->DeleteGroup($iGroupId);
	}
	public function GetGroups() {
		if (false === ($data = $this->Cache_Get("hook_groups"))) {	
			$aResult=$this->oDb->GetGroups();
			$data = array();
			foreach ($aResult as $iId) {
				$data[] = $this->GetGroupById($iId);
			}
			$this->Cache_Set("hook_groups", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}

	public function SaveHookParamsFromArray(ModuleHook_EntityHook$oHook, $aParams){
		if(empty($aParams)) return false;
		$this->DeleteParamsByHook( $oHook->getId() );
		foreach ($aParams as $sName => $sValue) {
			$oParam = Engine::GetEntity("Hook", null, "Param");
			$oParam->setName($sName);
			$oParam->setValue($sValue);
			$oParam->setHook( $oHook->getId() );
			$this->AddParam($oParam);
		}
	}
}