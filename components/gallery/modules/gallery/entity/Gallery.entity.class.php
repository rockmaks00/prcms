<?
class ComponentGallery_ModuleGallery_EntityGallery extends Entity {

	public function getResizedImage($iWidth=200, $iHeight=150, $iCrop=1, $iLeft=null, $iTop=null){
		if( !($sUrl = $this->_aData["gallery_resized_image"]) ){
			$sUrl = Engine::getInstance()->Image_AdaptImage($this->getImage(), $iWidth, $iHeight, $iCrop, $iLeft, $iTop);
			$this->setResizedImage($sUrl);
		}
		return $sUrl;
	}
	public function getImage(){
		if( !($sUrl = $this->_aData["gallery_image"]) ){
			$aImages = $this->getImages();
			if(empty($aImages))
				$sUrl = "";
			else
				$sUrl = $aImages[0]->getImage();
			$this->setImage($sUrl);
		}
		return $sUrl;
	}
	public function getImages(){
		if( !( $aImages = $this->_aData["gallery_images"] ) ){
			$aImages = Engine::GetInstance()->ComponentGallery_ModuleGallery_GetImagesByGallery($this->getId());
			$this->setImages($aImages);
		}
		return $aImages;
	}
	public function getDate() {
		$iTimestamp = strtotime($this->_aData['gallery_datetime']);
		return date("Y-m-d", $iTimestamp);
	}

	public function getTime() {
		$iTimestamp = strtotime($this->_aData['gallery_datetime']);
		return date("H:i", $iTimestamp);
	}

	public function setDate($sDate) {
		$iTimestamp = strtotime($this->_aData['gallery_datetime']);
		$sDatetime = $sDate.date(" H:i", $iTimestamp);
		$this->_aData['gallery_datetime']=$sDatetime;
	}

	public function setTime($sTime) {
		$iTimestamp = strtotime($this->_aData['gallery_datetime']);
		$sDatetime = date("Y-m-d ", $iTimestamp).$sTime;
		$this->_aData['gallery_datetime']=$sDatetime;
	}

	public function setUploadedImg(){
		if(isset($_FILES['image']) and is_uploaded_file($_FILES['image']['tmp_name'])) {
			Engine::GetInstance()->Image_Delete($this->getImage());
			if ($sFileName = Engine::GetInstance()->Image_UploadImage($_FILES['image'],"gallery")) {
				$this->setImage($sFileName);
			}else{
				return false;
			}
		}elseif( !$this->getImage() ) $this->setImage("");
		return true;
	}
	public function getNodeObject(){
		if( !( $oNode = $this->_aData["gallery_node_object"] ) ){
			$oNode = Engine::GetInstance()->Node_GetNodeById($this->getNode());
			if(!$oNode) $oNode = Engine::GetEntity("Node");
			$this->setNodeObject($oNode);
		}
		return $oNode;
	}
	public function getUrl(){
		if( !( $sUrl = $this->_aData["gallery_url"] ) ){
			$oNode = $this->getNodeObject();
			if(!$oNode->getId()) return "";
			$sUrl = $oNode->getFullUrl().$this->getId()."/";
			$this->setUrl($sUrl);
		}
		return $sUrl;
	}
}	