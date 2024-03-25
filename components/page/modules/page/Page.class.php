<?
class ComponentPage_ModulePage extends Module {
	protected $oDb;
	public function Init() {
		$this->oDb=Engine::GetDb(__CLASS__);
		$this->oDb->Install();
	}
	public function GetPageById($iId) {
		if (false === ($data = $this->Cache_Get("page_{$iId}"))) {
			$data=$this->oDb->GetPageById($iId);
			$this->Cache_Set("page_{$iId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	public function GetPageByNode($iNodeId) {
		if (false === ($data = $this->Cache_Get("page_node_{$iNodeId}"))) {
			$data=$this->oDb->GetPageByNode($iNodeId);
			$this->Cache_Set("page_node_{$iNodeId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	public function Add(ComponentPage_ModulePage_EntityPage $oPage) {
		$this->Cache_Delete("page_node_{$oPage->getNode()}");
		if ($iId=$this->oDb->Add($oPage)){
			$oPage->setId($iId);
		}
		return $oPage;
	}
	public function Update(ComponentPage_ModulePage_EntityPage $oPage) {
		$this->Cache_Delete("page_node_{$oPage->getNode()}");
		$this->Cache_Delete("page_{$oPage->getId()}");
		return $this->oDb->Update($oPage);
	}
	public function Search($aWords){
		$aIds = array();
		foreach ($aWords as $sWord) {
			$aIds = array_merge( $aIds, $this->oDb->Search($sWord) );
		}
		$aIds = array_unique($aIds);

		$aResults = array();
		foreach( $aIds as $iId ){
			
			$oPage = $this->GetPageById( $iId );
			if( !$oPage ) continue;

			$oNode = $this->Node_GetNodeById( $oPage->getNode() );
			if( !$oNode ) continue;

			$oResult = Engine::GetEntity('ComponentSearch_Search', null, 'Result');
			$oResult->setNode( $oNode );
			$oResult->setText( strip_tags($oPage->getBody()) );
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