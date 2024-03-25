<?
class ComponentNewsAdmin extends Component {
	protected $oNode=null;
	protected $sAction=null;
	protected $aParams=array();
	protected $aLang=array();
	
	public function Init(){		
		$this->SetDefaultAction('default'); // Устанавливает экшн по умолчанию
		$this->oNode=Router::GetCurrentNode(); // Текущий редактируемый раздел
		$this->sAction=Router::GetActionAdmin(); // Текущий экшн
		$this->aParams=Router::getParams(); // Массив параметров
		$this->sTemplatePath=$this->Template_GetHost()."components/admin/templates/default/"; // Путь до шаблона админки
		
		$this->aLang=array(
			"Items"=>"Новости", //Множественное число
			"items"=>"новости",
			"Item"=>"Новость", //Единственное число
			"item"=>"новость",
			"item_genitive"=>"новости", //Родительный падеж
			"Item_genitive"=>"Новости" 
		);
		
		$this->Template_Assign("aLang", $this->aLang);
	}
	
	protected function RegisterActions() {
		$this->AddAction('default','ActionDefault');
		$this->AddAction('add','ActionForm');
		$this->AddAction('edit','ActionForm');
		$this->AddAction('update','ActionUpdate');
		$this->AddAction('activate','ActionActivate');
		$this->AddAction('deactivate','ActionDeactivate');
		$this->AddAction('delete','ActionDelete');
		$this->AddAction('deleteall','ActionDeleteAll');
		$this->AddAction('removeimage','ActionRemoveImage');
	}
		
	protected function ActionDefault() {
		if( !$this->AccessCheck("R") ) return $this->AccessDenied();
		$aNews=$this->ComponentNews_News_GetNewsByNode($this->oNode->getId());
		$this->Template_Assign("aNews", $aNews);
		$this->Template_Assign("sFormTitle", $this->oNode->getTitle());
		$this->SetTemplate("news_list.tpl");
	}
	protected function ActionForm(ComponentNews_ModuleNews_EntityNews $oNews = null){
		if( !$this->AccessCheck("V") ) return $this->AccessDenied();
		$this->Template_Assign("sFormTitle", "Добавление ".$this->aLang['item_genitive']);
		$this->Template_Assign("sFormAction", "update");
		$this->Template_AddCss($this->sTemplatePath."assets/chosen-bootstrap/chosen/chosen.css");
		$this->Template_AddCss($this->sTemplatePath."assets/bootstrap-toggle-buttons/static/stylesheets/bootstrap-toggle-buttons.css");
		$this->Template_AddCss($this->sTemplatePath."assets/bootstrap/css/bootstrap-fileupload.css");
		$this->Template_AddJs($this->sTemplatePath."assets/chosen-bootstrap/chosen/chosen.jquery.min.js");
		$this->Template_AddJs($this->sTemplatePath."assets/bootstrap-toggle-buttons/static/js/jquery.toggle.buttons.js");
		$this->Template_AddJs($this->sTemplatePath."assets/bootstrap/js/bootstrap-fileupload.js");
		$this->Template_AddJs($this->sTemplatePath."assets/ckeditor/ckeditor.js");
		
		if( !$oNews ){
			if( $iId = intval(Router::getParam(0)) ){
				$oNews=$this->ComponentNews_News_GetNewsById($iId);
			}else{
				$oNews=Engine::GetEntity('ComponentNews_News');
				$oNews->setNode($this->oNode->getId());
				$oNews->setDatetime(date('Y-m-d H:i:s'));
				$oNews->setActive(1);
			}
		}
		
		$this->Template_Assign("oNews", $oNews);
		$this->Template_Assign("action", "update");
		$this->SetTemplate("news_form.tpl");
	}
	
	protected function ActionUpdate(){
		if( !$this->AccessCheck("V") ) return $this->AccessDenied();
		if( $iId = getRequest('id', null, 'id') ){
			$oNews=$this->ComponentNews_News_GetNewsById($iId);
		}else{
			$oNews=Engine::GetEntity('ComponentNews_News');
		}

		$oNews->setTitle(	getRequest('title'));
		$oNews->setDate(	getRequest('date'));
		$oNews->setTime(	getRequest('time'));
		$oNews->setAnnouncement(getRequest('announcement'));
		$oNews->setBody(	getRequest('body'));
		$oNews->setActive(	getRequest('active'));
		$oNews->setNode($this->oNode->getId());
		
		if( $sUploadedImage = $this->Image_GetUploadedFilePath($_FILES['image'], "news") ){
			$oNews->setImage($sUploadedImage);
		}

		if( !$oNews->getNode() || !$oNews->getTitle() || $sUploadedImage === false ){
			$this->Template_AddMessage("Ошибка!","Не получилось загрузить фото!");
			return $this->ActionForm($oNews);
		}

		if ($oNews->getId()) $this->ComponentNews_News_Update($oNews);
		else $oNews=$this->ComponentNews_News_Add($oNews);

		if (!getRequest("apply")) header("Location: ".Config::Get("host")."admin/content/".$this->oNode->getId()."/");
		else header("Location: ".Config::Get("host")."admin/content/".$this->oNode->getId()."/edit/".$oNews->getId()."/");
	}
	
	
	protected function ActionDelete(){
		if( !$this->AccessCheck("W") ) return $this->AccessDenied();
		$this->ComponentNews_News_Delete(intval(Router::GetParam(0)));
		header("Location: ".Config::Get("host")."admin/content/".$this->oNode->getId()."/");
	}
	public function ActionDeleteall(){
		if( !$this->AccessCheck("W") ) return $this->AccessDenied();
		if( $aIds = explode(',', Router::GetParam(0)) ){
			foreach ($aIds as $iId){
				$this->ComponentNews_News_Delete( intval($iId) );
			}
		}
		$result['state']="success";
		$result['msg']=0;
		echo json_encode($result);
		exit;
	}
	protected function ActionDeactivate(){
		if( !$this->AccessCheck("V") ) return $this->AccessDenied();
		$iId=intval(Router::GetParam(0));
		if ($this->ComponentNews_News_Deactivate($iId)){ 
			$result['state']="success";
			$result['msg']=0;
		}
		else $result['state']="error";
		echo json_encode($result);
		exit;
	}
	protected function ActionActivate(){
		if( !$this->AccessCheck("V") ) return $this->AccessDenied();
		$iId=intval(Router::GetParam(0));
		if ($this->ComponentNews_News_Activate($iId)){ 
			$result['state']="success";
			$result['msg']=1;
		}
		else $result['state']="error";
		echo json_encode($result);
		exit;
	}
	
	protected function ActionRemoveImage(){
		if( !$this->AccessCheck("V") ) return $this->AccessDenied();
		$iId=intval(Router::GetParam(0));
		$oNews=$this->ComponentNews_News_GetNewsById($iId);
		$this->Image_Delete($oNews->getImage());
		$oNews->setImage("");
		$this->ComponentNews_News_Update($oNews);
		exit;
	}	
}	
