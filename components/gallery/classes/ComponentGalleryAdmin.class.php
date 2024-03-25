<?
class ComponentGalleryAdmin extends Component {
	protected $oNode=null;
	protected $sAction=null;
	protected $aParams=array();
	protected $aLang=array();
	
	public function Init(){		
		$this->SetDefaultAction('default'); // Устанавливает экшн по умолчанию
		$this->oNode=Router::GetCurrentNode(); // Текущий редактируемый раздел
		$this->sAction=Router::GetActionAdmin(); // Текущий экшн
		$this->aParams=Router::getParams(); // Массив параметров
		$sTemplatePath=$this->Template_GetHost()."components/admin/templates/default/";
		$this->sTemplatePath=$sTemplatePath; // Путь до шаблона админки
		$this->aLang=array(
			"Items"=>"Альбомы", //Множественное число
			"items"=>"альбомы",
			"Item"=>"Альбом", //Единственное число
			"item"=>"альбом",
			"item_genitive"=>"альбома", //Родительный падеж
			"Item_genitive"=>"Альбома" 
		);
		$this->Template_Assign("aLang", $this->aLang);
	}
	protected function RegisterActions() {
		$this->AddAction('default',		'ActionDefault');
		$this->AddAction('add',			'ActionForm');
		$this->AddAction('edit',		'ActionForm');
		$this->AddAction('update',		'ActionUpdate');
		$this->AddAction('delete',		'ActionDelete');
		$this->AddAction('deleteall',	'ActionDeleteAll');
		$this->AddAction('activate',	'ActionActivate');
		$this->AddAction('sort',		'ActionSort');
		$this->AddAction('removegalleryimage',	'ActionRemoveGalleryImage');
		$this->AddAction('images',		'ActionImages');
		$this->AddAction('upload',		'ActionUpload');
		$this->AddAction('updateimages','ActionUpdateImages');
		$this->AddAction('removeimage',	'ActionRemoveImage');
	}
	protected function ActionDefault() {
		if( !$this->AccessCheck("R") ) return $this->AccessDenied();
		$aGalleries=$this->ComponentGallery_Gallery_GetGalleriesByNode($this->oNode->getId());
		$this->Template_Assign("aGalleries", $aGalleries);
		
		$this->Template_Assign("sFormTitle", $this->oNode->getTitle());
		$this->SetTemplate("gallery_list.tpl");
	}
	protected function ActionForm(ComponentGallery_ModuleGallery_EntityGallery $oGallery = null){
		if( !$this->AccessCheck("V") ) return $this->AccessDenied();
		$this->Template_Assign("sFormTitle", "Добавление ".$this->aLang['item_genitive']);
		$this->Template_Assign("sFormAction", "update");
		$this->Template_AddCss($this->sTemplatePath."assets/chosen-bootstrap/chosen/chosen.css");
		$this->Template_AddCss($this->sTemplatePath."assets/bootstrap-toggle-buttons/static/stylesheets/bootstrap-toggle-buttons.css");
		$this->Template_AddCss($this->sTemplatePath."assets/bootstrap/css/bootstrap-fileupload.css");
		$this->Template_AddJs($this->sTemplatePath."assets/chosen-bootstrap/chosen/chosen.jquery.min.js");
		$this->Template_AddJs($this->sTemplatePath."assets/bootstrap-toggle-buttons/static/js/jquery.toggle.buttons.js");
		$this->Template_AddJs($this->sTemplatePath."assets/bootstrap/js/bootstrap-fileupload.js");
		
		if( !$oGallery ){
			if( $iId = Router::GetParam(0) ){
				$oGallery = $this->ComponentGallery_Gallery_GetGalleryById( $iId );
			}else{
				$oGallery=Engine::GetEntity('ComponentGallery_Gallery');
				$oGallery->setDate(date("Y-m-d"));
				$oGallery->setTime(date("H:i"));
				$oGallery->setActive(1);
			}
		}
		$this->Template_Assign("oGallery", $oGallery);
		$this->SetTemplate("gallery_form.tpl");
	}
	protected function ActionUpdate() {
		if( !$this->AccessCheck("W") ) return $this->AccessDenied();

		if( $iId = getRequest("id", null, "id") ){
			$oGallery = $this->ComponentGallery_Gallery_GetGalleryById($iId);
		}else{
			$oGallery = Engine::GetEntity("ComponentGallery_Gallery");
			$oGallery->setSort(500);
		}

		$oGallery->setTitle(		getRequest('title'));
		$oGallery->setDate(			getRequest('date'));
		$oGallery->setTime(			getRequest('time'));
		$oGallery->setImage(		getRequest('image'));
		$oGallery->setDescription(	getRequest('description'));
		$oGallery->setActive(		getRequest('active'));
		$oGallery->setNode(			$this->oNode->getId());

		$oGallery->setUploadedImg();
		if( !$oGallery->getTitle() ){
			$this->ActionAdd($oGallery);
			$this->Template_AddMessage("Ошибка!","Не все поля корректно заполнены!");
			return $this->ActionForm($oGallery);
		}else{
			if ($oGallery->getId()) $this->ComponentGallery_Gallery_Update($oGallery);
			else $oGallery=$this->ComponentGallery_Gallery_Add($oGallery);
		}

		if (!getRequest("apply")) header("Location: ".Config::Get("host")."admin/content/".$this->oNode->getId()."/");
		else header("Location: ".Config::Get("host")."admin/content/".$this->oNode->getId()."/add/".$oGallery->getId()."/");
		exit;
	}
	
	public function ActionDelete(){
		if( !$this->AccessCheck("W") ) return $this->AccessDenied();
		if( is_numeric( Router::GetParam(0) ) ){
			$this->ComponentGallery_Gallery_Delete( Router::GetParam(0) );
		}
		header("Location: ".Config::Get("host")."admin/content/".$this->oNode->getId()."/");
		exit;
	}
	public function ActionDeleteall(){
		if( !$this->AccessCheck("W") ) return $this->AccessDenied();
		if( $aIds = explode(',', Router::GetParam(0)) ){
			foreach ($aIds as $iId){
				$this->ComponentGallery_Gallery_Delete( intval($iId) );
			}
		}
		$result['state']="success";
		$result['msg']=0;
		echo json_encode($result);
		exit;
	}
	protected function ActionActivate(){
		if(!$this->AccessCheck("V")) return $this->AccessDenied();
		$sMode = Router::GetParam(0);
		$iId = intval(Router::GetParam(1));

		if($sMode=="activate"){
			if( $this->ComponentGallery_Gallery_Activate($iId) ){
				$result['state']="success";
				$result['msg']=1;
			}else $result['state']="error";
		}elseif($sMode=="deactivate"){
			if ($this->ComponentGallery_Gallery_Deactivate($iId) ){ 
				$result['state']="success";
				$result['msg']=0;
			}else $result['state']="error";
		}else{
			$result['state']="error";	
		}
		echo json_encode($result);
		exit;
	}
	public function ActionSort(){
		if( !$this->AccessCheck("V") ) return;
		$oGallery 	 = $this->ComponentGallery_Gallery_GetGalleryById( intval(Router::GetParam(0)) );
		if( !$oGallery ) $this->NotFound();
		$sSortAction = Router::GetParam(1);
		$this->ComponentGallery_Gallery_Sort($oGallery, $sSortAction);
		$aGalleries=$this->ComponentGallery_Gallery_GetGalleriesByNode($this->oNode->getId());
		$this->Template_Assign("aGalleries", $aGalleries);
		$this->SetTemplate("gallery_list_portlet.tpl");
	}
	protected function ActionRemoveGalleryImage(){
		if( !$this->AccessCheck("W") ) return $this->AccessDenied();
		if( is_numeric(Router::GetParam(0)) ){
			$oGallery 	 = $this->ComponentGallery_Gallery_GetGalleryById( intval(Router::GetParam(0)) );
			$this->Image_Delete($oGallery->getImage());
			$oGallery->setImage("");
			$this->ComponentGallery_Gallery_Update($oGallery);
		}
		exit;
	}

	public function ActionUpload(){
		if( !$this->AccessCheck("V") ) return $this->AccessDenied();
		if (!empty($_FILES) && $_FILES['Filedata']['tmp_name']) {
			if (is_uploaded_file($_FILES['Filedata']['tmp_name'])) {	
				if ($sFileName=$this->Image_UploadImage($_FILES['Filedata'], "images")) {	
					$aFile=pathinfo($_FILES['Filedata']['name']);
					$oImage=Engine::GetEntity('ComponentGallery_Gallery', null, 'Image');
					$oImage->setUrl($sFileName);
					$oImage->setGallery(intval(Router::getParam(0)));
					$oImage->setTitle($aFile['filename']);
					$oImage->setSort(500);
					$this->ComponentGallery_Gallery_AddImage($oImage);
				} else {
					header("HTTP/1.1 500 Internal Server Error");
					echo "ОШИБКА: Файл не загружен!";
				}
			}else{
				header("HTTP/1.1 500 Internal Server Error");
				echo "ОШИБКА: Файл не загружен!";
			}
		}
		exit;
	}
	protected function ActionImages(){
		if( !$this->AccessCheck("R") ) return $this->AccessDenied();
		$this->Template_Assign("sFormTitle", "Добавление ".$this->aLang['item_genitive']);
		$this->Template_Assign("sFormAction", "updateimages");
		$this->Template_AddCss($this->sTemplatePath."assets/chosen-bootstrap/chosen/chosen.css");
		$this->Template_AddCss($this->sTemplatePath."assets/bootstrap-toggle-buttons/static/stylesheets/bootstrap-toggle-buttons.css");
		$this->Template_AddCss($this->sTemplatePath."assets/bootstrap/css/bootstrap-fileupload.css");
		$this->Template_AddCss($this->sTemplatePath."assets/uploadify/uploadify.css");
		$this->Template_AddCss("/components/gallery/templates/admin/css/gallery.css");
		$this->Template_AddJs($this->sTemplatePath."assets/chosen-bootstrap/chosen/chosen.jquery.min.js");
		$this->Template_AddJs($this->sTemplatePath."assets/bootstrap-toggle-buttons/static/js/jquery.toggle.buttons.js");
		$this->Template_AddJs($this->sTemplatePath."assets/bootstrap/js/bootstrap-fileupload.js");
		$this->Template_AddJs($this->sTemplatePath."assets/uploadify/jquery.uploadify-3.1.min.js");
		$this->Template_AddJs("/components/gallery/templates/admin/js/gallery.js");		
		
		$oGallery=$this->ComponentGallery_Gallery_GetGalleryById(intval(Router::getParam(0)));
		$aImages=$this->ComponentGallery_Gallery_GetImagesByGallery(intval(Router::getParam(0)));
		$this->Template_Assign("oGallery", $oGallery);
		$this->Template_Assign("aImages", $aImages);
	
		$this->SetTemplate("gallery_images.tpl");
	}

	public function ActionUpdateImages(){
		if( !$this->AccessCheck("V") ) return $this->AccessDenied();
		$iGalleryId = Router::GetParam(0);
		$aFiles = getRequest("file");
		foreach($aFiles as $iId => $aFile){
			$oImage = $this->ComponentGallery_Gallery_GetImageById($iId);
			$oImage->setTitle($aFile["title"]);
			$oImage->setDescription($aFile["description"]);
			$oImage->setSort($aFile["sort"]);
			$oImage->setGallery($iGalleryId);
			$this->ComponentGallery_Gallery_UpdateImage($oImage);
		}
		if (!getRequest("apply")) header("Location: ".Config::Get("host")."admin/content/".$this->oNode->getId()."/");
		else header("Location: ".Config::Get("host")."admin/content/".$this->oNode->getId()."/images/".$iGalleryId."/");
		exit;
	}
	protected function ActionRemoveImage(){
		if( !$this->AccessCheck("W") ) return $this->AccessDenied();
		$iId=intval(Router::GetParam(0));
		$this->ComponentGallery_Gallery_DeleteImage($iId);
		exit;
	}
}	
