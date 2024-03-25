<?
class ComponentPattern_ModulePattern_EntityMap extends Entity {
	public function setUploadedImg(){
		if(isset($_FILES['image']) and is_uploaded_file($_FILES['image']['tmp_name'])) {
			Engine::GetInstance()->Image_Delete($this->getImg());
			if ($sFileName = Engine::GetInstance()->Image_UploadImage($_FILES['image'],"patterns")) {
				$this->setImg($sFileName);
			}else{
				return false;
			}
		}
		return true;
	}
	
	public function setImgSize(){
		$this->_aData["map_img_size"] = getimagesize( mb_substr($this->getImg(), 1) );
	}
	
	public function getImgSize(){
		if( !isset($this->_aData["map_img_size"]) && $this->getImg() ) $this->setImgSize();
		return $this->_aData["map_img_size"];
	}

	public function getImgWidth(){
		$aSizes = $this->getImgSize();
		return $aSizes[0];
	}

	public function getImgHeight(){
		$aSizes = $this->getImgSize();
		return $aSizes[1];
	}

	public function getImgSizeAttr(){
		$aSizes = $this->getImgSize();
		return $aSizes[3];
	}
}