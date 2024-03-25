<?
class ComponentGallery_ModuleGallery extends Module {
	protected $oDb;
	public function Init() {
		$this->oDb=Engine::GetDb(__CLASS__);
		$this->oDb->Install();
	}
	
	public function GetGalleryById($iId) {
		if (false === ($data = $this->Cache_Get("gallery_{$iId}"))) {
			$data=$this->oDb->GetGalleryById($iId);
			$this->Cache_Set("gallery_{$iId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	
	public function GetGalleriesByNode($iNodeId) {
		if(false === ($data = $this->Cache_Get("gallery_node_{$iNodeId}"))) {
			$data=$this->oDb->GetGalleriesByNode($iNodeId);
			$this->Cache_Set("gallery_node_{$iNodeId}", $data, Config::Get("app.cache.expire"));
		}
		while(list($i,$iId)=each($data)){
			$data[$i] = $this->GetGalleryById($iId);
		}
		usort($data, function($a,$b){ return $a->getSort()-$b->getSort(); });
		return $data;
	}
	
	public function Add(ComponentGallery_ModuleGallery_EntityGallery $oGallery) {
		$this->Cache_Delete("gallery_node_{$oGallery->getNode()}");
		if ($iId=$this->oDb->Add($oGallery)){
			$oGallery->setId($iId);
		}
		return $oGallery;
	}
	
	public function Update(ComponentGallery_ModuleGallery_EntityGallery $oGallery) {
		$this->Cache_Delete("gallery_{$oGallery->getId()}");
		return $this->oDb->Update($oGallery);
	}

	public function Activate($iId){
		$oGallery = $this->GetGalleryById($iId);
		$oGallery->setActive(1);
		return $this->Update($oGallery);
	}
	public function Deactivate($iId){
		$oGallery = $this->GetGalleryById($iId);
		$oGallery->setActive(0);
		return $this->Update($oGallery);
	}
	
	public function Delete($iId) {
		$oGallery=$this->GetGalleryById($iId);
		$this->Cache_Delete("gallery_{$oGallery->getId()}");
		$this->Cache_Delete("gallery_node_{$oGallery->getNode()}");
		return $this->oDb->Delete($iId);
	}

	public function Sort(ComponentGallery_ModuleGallery_EntityGallery $oGallery, $sAction) {
		$aGalleries = $this->GetGalleriesByNode($oGallery->getNode());
		foreach($aGalleries as $i=>$oGalleryEach){
			$oGalleryEach->setSort(($i+1));
			if ($oGallery->getId() == $oGalleryEach->getId()) $index=$i;
		}
		if ($sAction=="up"){
			
			if (isset($aGalleries[$index-1])){
				$tmp=$aGalleries[$index-1]->getSort();
				$aGalleries[$index-1]->setSort($aGalleries[$index]->getSort());
				$aGalleries[$index]->setSort($tmp);
			}
		}
		if ($sAction=="down"){
			
			if (isset($aGalleries[$index+1])){
				$tmp=$aGalleries[$index+1]->getSort();
				$aGalleries[$index+1]->setSort($aGalleries[$index]->getSort());
				$aGalleries[$index]->setSort($tmp);
			}
		}

		foreach($aGalleries as $oGalleryEach){
			$this->Update($oGalleryEach);
		}
		return true;
	}
	
	
	
	/*----------------IMAGE BEGIN------------------*/
	public function GetImagesByGallery($iGalleryId) {
		if (false === ($data = $this->Cache_Get("gallery_images_gallery_{$iGalleryId}"))) {
			$data=$this->oDb->GetImagesByGallery($iGalleryId);
			while( list($i,$iId) = each($data) ){
			//foreach($data as $i=>$iId){
				$data[$i]=$this->GetImageById($iId);
			}
			$this->Cache_Set("gallery_images_gallery_{$iGalleryId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	
	public function GetImageById($iId) {
		if (false === ($data = $this->Cache_Get("gallery_image_{$iId}"))) {
			$data=$this->oDb->GetImageById($iId);
			$this->Cache_Set("gallery_image_{$iId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	
	public function AddImage(ComponentGallery_ModuleGallery_EntityImage $oImage) {
		$this->Cache_Delete("gallery_images_gallery_{$oImage->getGallery()}");
		if ($iId=$this->oDb->AddImage($oImage)){
			$oImage->setId($iId);
		}
		return $oImage;
	}
	
	public function UpdateImage(ComponentGallery_ModuleGallery_EntityImage $oImage) {
		$this->Cache_Delete("gallery_image_{$oImage->getId()}");
		$this->Cache_Delete("gallery_images_gallery_{$oImage->getGallery()}");
		return $this->oDb->UpdateImage($oImage);
	}
	
	public function DeleteImage($iId) {
		$oImage=$this->GetImageById($iId);
		$this->Cache_Delete("gallery_image_{$oImage->getId()}");
		$this->Cache_Delete("gallery_images_gallery_{$oImage->getGallery()}");
		return $this->oDb->DeleteImage($iId);
	}

	public function Search($aWords){
		$aResults= array();

		$aIds = array("images"=>array(), "galleries"=>array());
		foreach ($aWords as $sWord) {
			$aTmp = $this->oDb->Search($sWord);
			$aIds["images"] 	= array_merge( $aIds["images"], $aTmp["images"] );
			$aIds["galleries"] 	= array_merge( $aIds["galleries"], $aTmp["galleries"] );
		}
		$aIds["images"] 	= array_unique($aIds["images"]);
		$aIds["galleries"] 	= array_unique($aIds["galleries"]);
		
		foreach ($aIds["galleries"] as $iId) {
			$oGallery = $this->GetGalleryById($iId); 
			if( !$oGallery ) continue;

			$oNode = $this->Node_GetNodeById( $oGallery->getNode() );
			if( !$oNode ) continue;

			$oResult = Engine::GetEntity('ComponentSearch_Search', null, 'Result');
			$oResult->setNode( $oNode );
			$oResult->setText( strip_tags($oGallery->getDescription()) );
			$oResult->setUrl(  $oNode->getFullUrl().$oGallery->getId()."/" );
			$oResult->setTitle($oNode->getTitle()." - ".$oGallery->getTitle() );
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

		foreach ($aIds["images"] as $iId) {
			$oImage = $this->GetImageById($iId); 
			if( !$oImage ) continue;

			$oNode = $this->Node_GetNodeById( $oImage->getGalleryObject()->getNode() );
			if( !$oNode ) continue;
			
			$sUrl = $oNode->getFullUrl().$oImage->getGallery()."/";
			$sKey = md5( $sUrl );
			if($aResults[$sKey]){
				$iCount=0;
				foreach ($aWords as $sWord) {
					$iCount += mb_substr_count(mb_strtolower(strip_tags($oImage->getDescription())), $sWord);
					$iCount += mb_substr_count(mb_strtolower($oImage->getTitle()), $sWord);
					$aResults[$sKey]->addCount($iCount);
				}
			}else{
				$oResult = Engine::GetEntity('ComponentSearch_Search', null, 'Result');
				$oResult->setNode( $oNode );
				$oResult->setText( strip_tags($oImage->getDescription()) );
				$oResult->setUrl(  $sUrl );
				$oResult->setTitle($oNode->getTitle()." - ".$oImage->getTitle() );
				$iCount=0;
				foreach ($aWords as $sWord) {
					$iCount += mb_substr_count(mb_strtolower($oResult->getText()), $sWord);
					$iCount += mb_substr_count(mb_strtolower($oResult->getTitle()), $sWord);
				}
				$oResult->setCount( $iCount );
				$aResults[$sKey] = $oResult;
				unset($oResult);
			}
		}
		return $aResults;
	}
	/*----------------IMAGE END------------------*/
}