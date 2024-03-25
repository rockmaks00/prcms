<?
class ComponentPattern_ModulePattern extends Module {
	protected $oDb;
	public function Init() {
		$this->oDb=Engine::GetDb(__CLASS__);
		$this->oDb->Install();
	}

	public function GetMapById($iId){
		if (false === ($data = $this->Cache_Get("pattern_map_{$iId}"))) {
			$data=$this->oDb->GetMapById($iId);
			$this->Cache_Set("pattern_map_{$iId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	public function GetMapByNode($iId){
		if (false === ($data = $this->Cache_Get("pattern_map_node_{$iId}"))) {
			$iMapId  = $this->oDb->GetMapByNode($iId);
			$data = $this->GetMapById($iMapId);
			$this->Cache_Set("pattern_map_node_{$iId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	public function AddMap(ComponentPattern_ModulePattern_EntityMap $oMap) {
		if ($iId=$this->oDb->AddMap($oMap)){
			$oMap->setId($iId);
		}
		return $oMap;
	}
	public function UpdateMap(ComponentPattern_ModulePattern_EntityMap $oMap) {
		$this->Cache_Delete("pattern_map_node_{$oMap->getNode()}");
		$this->Cache_Delete("pattern_map_{$oMap->getId()}");
		return $this->oDb->UpdateMap($oMap);
	}

	public function GetPatternById($iId) {
		if (false === ($data = $this->Cache_Get("pattern_{$iId}"))) {
			$data=$this->oDb->GetPatternById($iId);
			$this->Cache_Set("pattern_{$iId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	public function GetPatternsByNode($iNodeId) {
		if (false === ($data = $this->Cache_Get("patterns_node_{$iNodeId}"))) {
			$data = array();
			$aIds=$this->oDb->GetPatternsByNode($iNodeId);
			foreach ($aIds as $iId) {
				$data[] = $this->GetPatternById($iId);
			}
			$this->Cache_Set("patterns_node_{$iNodeId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	public function Add(ComponentPattern_ModulePattern_EntityPattern $oPattern) {
		$this->Cache_Delete("patterns_node_{$oPattern->getNode()}");
		if ($iId=$this->oDb->Add($oPattern)){
			$oPattern->setId($iId);
		}
		return $oPattern;
	}
	public function Update(ComponentPattern_ModulePattern_EntityPattern $oPattern) {
		$this->Cache_Delete("patterns_node_{$oPattern->getNode()}");
		$this->Cache_Delete("pattern_{$oPattern->getId()}");
		return $this->oDb->Update($oPattern);
	}
	public function Delete(ComponentPattern_ModulePattern_EntityPattern $oPattern) {
		$this->Cache_Delete("patterns_node_{$oPattern->getNode()}");
		$this->Cache_Delete("pattern_{$oPattern->getId()}");
		//$this->Image_Delete($oPattern->getImg());
		return $this->oDb->Delete($oPattern->getId());
	}
	public function Activate(ComponentPattern_ModulePattern_EntityPattern $oPattern) {
		$this->Cache_Delete("patterns_node_{$oPattern->getNode()}");
		$this->Cache_Delete("pattern_{$oPattern->getId()}");
		return $this->oDb->Activate($oPattern);
	}
	public function Deactivate(ComponentPattern_ModulePattern_EntityPattern $oPattern) {
		$this->Cache_Delete("patterns_node_{$oPattern->getNode()}");
		$this->Cache_Delete("pattern_{$oPattern->getId()}");
		return $this->oDb->Deactivate($oPattern);
	}
	public function Search($aWords){
		$aIds = array();
		foreach ($aWords as $sWord) {
			$aIds = array_merge( $aIds, $this->oDb->Search($sWord) );
		}
		$aIds = array_unique($aIds);
		$aResults = array();
		foreach( $aIds as $iId ){
			
			$oPattern = $this->GetPatternById( $iId );
			if( !$oPattern ) continue;

			$oNode = $this->Node_GetNodeById( $oPattern->getNode() );
			if( !$oNode ) continue;

			$oResult = Engine::GetEntity('ComponentSearch_Search', null, 'Result');
			$oResult->setNode( $oNode );
			$oResult->setText( strip_tags($oPattern->getDesc()) );
			$oResult->setUrl( $oNode->getFullUrl() );
			$iCount=0;
			foreach ($aWords as $sWord) {
				$iCount += mb_substr_count(mb_strtolower($oResult->getText()), $sWord);
			}
			$oResult->setCount($iCount);
			$sKey = md5( $oResult->getUrl() );
			$aResults[$sKey] = $oResult;
			unset($oResult);
		}
		return $aResults;
	}
}