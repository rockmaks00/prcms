<?
class ComponentNews_ModuleNews extends Module {
	protected $oDb;
	public function Init() {
		$this->oDb=Engine::GetDb(__CLASS__);
		$this->oDb->Install();
	}
	public function GetNews() {
		if (false === ($data = $this->Cache_Get("news_ids"))) {
			$data=$this->oDb->GetNews();
			$this->Cache_Set("news_ids", $data, Config::Get("app.cache.expire"));
		}
		while( list($i,$iId) = each( $data )){
			$data[$i]=$this->oDb->GetNewsById($iId);
		}
		return $data;
	}
	public function GetNewsByNode($iNodeId, $iOnPage=0, $iPage=1) {
		if (false === ($data = $this->Cache_Get("news_node_{$iNodeId}"))) {
			$data=$this->oDb->GetNewsByNode($iNodeId);
			$this->Cache_Set("news_node_{$iNodeId}", $data, Config::Get("app.cache.expire"));
		}
		if($iOnPage){
			$iOffset = $iOnPage*($iPage-1);
			$data = array_slice($data, $iOffset, $iOnPage);
		}
		while( list($i,$iId) = each( $data )){
			$data[$i]=$this->GetNewsById($iId);
		}
		return $data;
	}
	public function GetActiveNewsByNode($iNodeId, $iOnPage=0, $iPage=1) {
		$data = $this->oDb->GetActiveNewsByNode($iNodeId);
		if( $iOnPage ){
			$iOffset = $iOnPage*($iPage-1);
			$data = array_slice($data, $iOffset, $iOnPage);
		}
		while( list($i,$iId) = each( $data )){
			$data[$i]=$this->GetNewsById($iId);
		}
		return $data;
	}
	public function GetActiveNewsCountByNode($iNodeId){
		$data = $this->oDb->GetActiveNewsByNode($iNodeId);
		return count($data);
	}
	public function GetNewsByIds($aIds) {
		foreach ($aIds as $iId) {
			$data[]=$this->GetNewsById($iId);
		}
		$data = array_filter($data, function($var){return !empty($var);});
		usort($data, function($a, $b){
			if($a->getDate() == $b->getDate()){
				return $a->getId() > $b->getId() ? 1 : -1;
			}
			return $a->getDate() > $b->getDate() ? -1 : 1;
		});
		return $data;
	}
	
	public function GetNewsById($iId) {
		if (false === ($data = $this->Cache_Get("news_{$iId}"))) {
			$data=$this->oDb->GetNewsById($iId);
			$this->Cache_Set("news_{$iId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	public function Add(ComponentNews_ModuleNews_EntityNews $oNews) {
		$this->Cache_Delete("news_ids");
		$this->Cache_Delete("news_node_{$oNews->getNode()}");
		if ($iId=$this->oDb->Add($oNews)){
			$oNews->setId($iId);
		}
		return $oNews;
	}
	public function Update(ComponentNews_ModuleNews_EntityNews $oNews) {
		$this->Cache_Delete("news_{$oNews->getId()}");
		return $this->oDb->Update($oNews);
	}
	
	public function Delete($iId) {
		$oNews=$this->GetNewsById($iId);
		$this->Cache_Delete("news_{$oNews->getId()}");
		$this->Cache_Delete("news_ids");
		$this->Cache_Delete("news_node_{$oNews->getNode()}");
		return $this->oDb->Delete($iId);
	}
	
	public function Activate($iId) {
		$this->Cache_Delete("news_{$iId}");
		return $this->oDb->Activate($iId);
	}
	
	public function Deactivate($iId) {
		$this->Cache_Delete("news_{$iId}");
		return $this->oDb->Deactivate($iId);
	}

	public function Search($aWords){
		$aIds = array();
		foreach ($aWords as $sWord) {
			$aIds = array_merge( $aIds, $this->oDb->Search($sWord) );
		}
		$aIds = array_unique($aIds);

		$aResults = array();
		foreach( $aIds as $iId ){
			$oNews = $this->GetNewsById( $iId );
			if( !$oNews ) continue;

			$oNode = $this->Node_GetNodeById( $oNews->getNode() );
			if( !$oNode ) continue;

			$oResult = Engine::GetEntity('ComponentSearch_Search', null, 'Result');
			$oResult->setNode( $oNode );
			$oResult->setText( strip_tags($oNews->getBody()) );
			$oResult->setUrl(  $oNode->getFullUrl().$oNews->getId()."/" );
			$oResult->setTitle($oNode->getTitle()." - ".$oNews->getTitle() );
			$iCount=0;
			foreach ($aWords as $sWord) {
				$iCount += mb_substr_count(mb_strtolower($oResult->getText()), $sWord);
				$iCount += mb_substr_count(mb_strtolower($oResult->getTitle()), $sWord);
			}
			$oResult->setCount( $iCount );
			$sKey = md5( $oResult->getUrl() );
			$aResults[$sKey] = $oResult;
			unset($oResult);
		}
		return $aResults;
	}
}