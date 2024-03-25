<?
class ModuleBanner_EntityBanner extends Entity {
	public function setUploadedImg(){
		if( !$this->getImg() ) $this->setImg('');
		if(isset($_FILES['image']) and is_uploaded_file($_FILES['image']['tmp_name'])) {
			Engine::GetInstance()->Image_Delete($this->getImg());
			if ($sFileName = Engine::GetInstance()->Image_UploadImage($_FILES['image'],"banners")) {
				$this->setImg($sFileName);
			}else{
				return false;
			}
		}
		return true;
	}

	public function getExtension(){
		if( !($aImgInfo = $this->getImgInfo()) ){
			$aImgInfo = pathinfo( $this->getImg() );
			$this->setImgInfo($aImgInfo);
		}
		return strtolower( $aImgInfo["extension"] );
	}
}