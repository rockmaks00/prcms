<?
class ModuleNode extends Module {
	protected $oDb;
	protected $oComponent;
	public function Init() {
		$this->oDb=Engine::GetDb(__CLASS__);
		$this->oDb->Install();
	}
	public function Add(ModuleNode_EntityNode $oNode) {
		$this->Cache_Delete("nodes");
		if ($iId=$this->oDb->Add($oNode)){
			$oNode->setId($iId);
		}
		return $oNode;
	}
	public function Update(ModuleNode_EntityNode $oNode) {
		$this->Cache_Delete("node_{$oNode->getId()}");
		$this->Cache_Delete("nodes");
		return $this->oDb->Update($oNode);
	}
	public function Delete($iNodeId) {
		$this->Cache_Delete("node_{$iNodeId}");
		$this->Cache_Delete("nodes");
		return $this->oDb->Delete($iNodeId);
	}
	public function GetNodes() {
		if (false === ($data = $this->Cache_Get("nodes"))) {	
			$data=$this->oDb->GetNodes();
			$this->Cache_Set("nodes", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	public function GetNodesByParent($iParentId) {
		$aNodes=$this->oDb->GetNodesByParent($iParentId);
		return $aNodes;
	}

	public function GetNodesByComponent($mComponent) {
		if( !is_numeric($mComponent) ) $oComponent = $this->Component_GetComponentByName($mComponent);
		else $oComponent = $this->Component_GetComponentById($mComponent);
		if( !$oComponent ) return null;

		$data = array();
		$aNodeId = $this->oDb->GetNodesByComponent($oComponent->getId());
		foreach ($aNodeId as $iId) {
			$data[] = $this->GetNodeById($iId);
		}
		return $data;
	}

	public function GetNodesTreeByParent($iParentId, $aAvailable) {
		$aNodes=$this->GetNodesByParent($iParentId);
		$aTree=array();
		foreach($aNodes as $oNode){
				$aTmp=array();
				if( empty($aAvailable) || in_array($oNode->getId(), $aAvailable)  ) {
					$aTmp['node']=$oNode;
				}else{
					$aTmp['node']=Engine::GetEntity("Node", array("node_title"=>"--"));
				}
				$aChilds=$this->GetNodesTreeByParent($oNode->getId(), $aAvailable);
				if ($aChilds) $aTmp['childs']=$aChilds;
				$aTree[]=$aTmp;
		}
		
		return $aTree;
	}

	public function GetNodeByUrl($sUrl, $iParentId) {
		$iNodeId=$this->oDb->GetNodeByUrl($sUrl, $iParentId);
		$data = $this->GetNodeById($iNodeId);
		return $data;
	}

	public function GetNodeById($iId) {
		if (false === ($data = $this->Cache_Get("node_{$iId}"))) {
			$data=$this->oDb->GetNodeById($iId);
			$this->Cache_Set("node_{$iId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	
	/*public function GetNodeAdditionalData($oNode) {
		if (isset($oNode)){
			$oComponent = $this->Component_GetComponentById($oNode->getComponentId());
			if( !$oComponent ) $oComponent = Engine::GetEntity('Component');
			$oNode->setComponent($oComponent);
		}
		return $oNode;
	}*/
	
	public function Activate($iNodeId) {
		$this->Cache_Delete("node_{$iNodeId}");
		$this->Cache_Delete("nodes");
		return $this->oDb->Activate($iNodeId);
	}
	
	public function Deactivate($iNodeId) {
		$this->Cache_Delete("node_{$iNodeId}");
		$this->Cache_Delete("nodes");
		return $this->oDb->Deactivate($iNodeId);
	}
	
	public function Sort($iNodeId, $sAction) {
		$aNodes=$this->oDb->GetNodesOrderParent();
		foreach($aNodes as $i=>$oNode){
			$oNode->setSort(($i+1));
			if ($oNode->getId()==$iNodeId) $index=$i;
		}
		if ($sAction=="up"){
			if (isset($aNodes[$index-1])){
				$tmp=$aNodes[$index-1]->getSort();
				$aNodes[$index-1]->setSort($aNodes[$index]->getSort());
				$aNodes[$index]->setSort($tmp);
			}
		}
		if ($sAction=="down"){
			if (isset($aNodes[$index+1])){
				$tmp=$aNodes[$index+1]->getSort();
				$aNodes[$index+1]->setSort($aNodes[$index]->getSort());
				$aNodes[$index]->setSort($tmp);
			}
		}
		foreach($aNodes as $oNode){
			$this->oDb->Update($oNode);
		}
		return true;
	}
	
	public function Search($aWords){
		$aIds = array();
		foreach ($aWords as $sWord) {
			$aIds = array_merge( $aIds, $this->oDb->Search($sWord) );
		}
		$aIds = array_unique($aIds);
		$aResults=array();
		foreach ($aIds as $iId) {
			$oNode = $this->Node_GetNodeById($iId);
			if(!$oNode)continue;
			$sUrl = $oNode->getFullUrl();
			$sKey = md5($sUrl);
			$aResults[$sKey] = Engine::GetEntity('ComponentSearch_Search', null, 'Result');
			$aResults[$sKey]->setNode( $oNode );
			$iCount=0;
			foreach ($aWords as $sWord) {
				$iCount += mb_substr_count(mb_strtolower($oNode->getTitle()), $sWord);
			}
			$aResults[$sKey]->setCount($iCount);
			$aResults[$sKey]->setUrl( $sUrl );
		}
		return $aResults;
	}
	
}