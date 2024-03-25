<?
class ComponentGallery_ModuleGallery_EntityImage extends Entity {
	public function getImage(){
		if( !($sUrl = $this->_aData["image_image"]) ){
			$sExt = $this->getExt();
			if( $this->isImg() ) $sUrl = $this->getUrl();
			else{
				$aFolders = array("files/images/icons/", "components/gallery/templates/admin/icons/");
				$sUrl = false;
				if($sExt=="docx") $sExt = "doc";
				if($sExt=="xlsx") $sExt = "xls";
				foreach( $aFolders as $sFold ){
					$sTmpUrl = $sFold.$sExt.".png";
					if( file_exists($sTmpUrl) ){
						$sUrl = Config::Get("host").$sTmpUrl;
						break;
					}
				}
				if( !$sUrl ){
					$sExt = "file";
					foreach( $aFolders as $sFold ){
						$sTmpUrl = $sFold.$sExt.".png";
						if( file_exists($sTmpUrl) ){
							$sUrl = Config::Get("host").$sTmpUrl;
							break;
						}
					}
				}
			}
			$this->setImage($sUrl);
		}
		return $sUrl;
	}
	public function getResizedImage($iWidth=200, $iHeight=150, $iCrop=1, $iLeft=null, $iTop=null){
		if( !($sUrl = $this->_aData["image_resized_image"]) ){
			$sUrl = $this->getImage();
			if( $sUrl ) $sUrl = Engine::getInstance()->Image_AdaptImage($sUrl, $iWidth, $iHeight, $iCrop, $iLeft, $iTop);
			$this->setResizedImage($sUrl);
		}
		return $sUrl;
	}
	public function getExt(){
		if( !($sExt = $this->_aData["image_ext"]) ){
			$sExt = Engine::getInstance()->Image_getImgType($this->getUrl());
			$this->setExt($sExt);
		}
		return $sExt;
	}
	public function isImg(){
		return (bool) in_array($this->getExt(), array("jpg", "jpeg", "png", "gif") );
	}
	public function getGalleryObject(){
		if( !($oGallery = $this->_aData["image_gallery_object"]) ){
			$oGallery = Engine::GetInstance()->ComponentGallery_Gallery_GetGalleryById($this->getGallery());
			$this->setGalleryObject( $oGallery );
		}
		return $oGallery;
	}
}	