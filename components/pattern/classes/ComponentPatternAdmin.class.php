<?
class ComponentPatternAdmin extends Component {
	protected $oNode=null;
	protected $sAction=null;
	protected $aParams=array();
	
	public function Init(){		
		$this->SetDefaultAction('default'); // Устанавливает экшн по умолчанию
		$this->oNode=Router::GetCurrentNode(); // Текущий редактируемый раздел
		$this->sAction=Router::GetActionAdmin(); // Текущий экшн
		$this->aParams=Router::getParams(); // Массив параметров
		$sTemplatePath=$this->Template_GetHost()."components/admin/templates/default/";
		$this->sTemplatePath=$sTemplatePath; // Путь до шаблона админки
	}
	
	protected function RegisterActions() {
		$this->AddAction('default','ActionDefault');
		$this->AddAction('updatemap','ActionUpdateMap');
		$this->AddAction('edit','ActionEdit');
		$this->AddAction('add','ActionAdd');
		$this->AddAction('update','ActionUpdate');
		$this->AddAction('removeimage','ActionRemoveImage');
		$this->AddAction('deleteall','ActionDeleteAll');
		$this->AddAction('delete','ActionDelete');
		$this->AddActionPreg('/^bad$/i','/^(page(\d+))?$/i','ActionDefault');
		$this->AddActionPreg('/^new$/i','/^(page(\d+))?$/i','ActionDefault');
	}
		
	protected function ActionDefault(){
		$this->Template_Assign("sFormTitle", "Редактирование карты");
		$this->Template_Assign("sFormAction", "updatemap");
		$this->Template_AddCss($this->sTemplatePath."assets/chosen-bootstrap/chosen/chosen.css");
		$this->Template_AddCss($this->sTemplatePath."assets/bootstrap-toggle-buttons/static/stylesheets/bootstrap-toggle-buttons.css");
		$this->Template_AddCss($this->sTemplatePath."assets/bootstrap/css/bootstrap-fileupload.css");
		$this->Template_AddJs($this->sTemplatePath."assets/chosen-bootstrap/chosen/chosen.jquery.min.js");
		$this->Template_AddJs($this->sTemplatePath."assets/bootstrap-toggle-buttons/static/js/jquery.toggle.buttons.js");
		$this->Template_AddJs($this->sTemplatePath."assets/bootstrap/js/bootstrap-fileupload.js");
		$this->Template_AddJs($this->sTemplatePath."assets/ckeditor/ckeditor.js");

		$oMap = $this->ComponentPattern_Pattern_GetMapByNode($this->oNode->getId());
		$this->Template_Assign("oMap", $oMap);
		$aPatterns = $this->ComponentPattern_Pattern_GetPatternsByNode($this->oNode->getId());
		$this->Template_Assign("aPatterns", $aPatterns);

		$this->SetTemplate("list.tpl");
	}

	protected function ActionUpdateMap() {
		$oMap = $this->ComponentPattern_Pattern_GetMapByNode($this->oNode->getId());
		if (!$oMap) $oMap=Engine::GetEntity('ComponentPattern_Pattern', null, "map");
		$oMap->setUploadedImg();
		$oMap->setDesc(getRequest('desc'));
		$oMap->setNode($this->oNode->getId());

		if ( $oMap->getId() ) $this->ComponentPattern_Pattern_UpdateMap($oMap);
		else $oMap=$this->ComponentPattern_Pattern_AddMap($oMap);

		if ( getRequest("apply") ) header("Location: ".Config::Get("host")."admin/content/".$this->oNode->getId()."/");
		else header("Location: ".Config::Get("host")."admin/nodes/");
	}

	protected function ActionEdit() {
		$this->Template_Assign("sFormTitle", "Редактирование Области");
		$this->Template_Assign("sFormAction", "update");
		$this->Template_AddCss($this->sTemplatePath."assets/chosen-bootstrap/chosen/chosen.css");
		$this->Template_AddCss($this->sTemplatePath."assets/bootstrap-toggle-buttons/static/stylesheets/bootstrap-toggle-buttons.css");
		$this->Template_AddCss($this->sTemplatePath."assets/bootstrap/css/bootstrap-fileupload.css");
		$this->Template_AddCss($this->Template_GetHost()."components/pattern/templates/admin/com_pattern.css");
		$this->Template_AddJs($this->sTemplatePath."assets/chosen-bootstrap/chosen/chosen.jquery.min.js");
		$this->Template_AddJs($this->sTemplatePath."assets/bootstrap-toggle-buttons/static/js/jquery.toggle.buttons.js");
		$this->Template_AddJs($this->sTemplatePath."assets/bootstrap/js/bootstrap-fileupload.js");
		$this->Template_AddJs($this->sTemplatePath."assets/ckeditor/ckeditor.js");
		$this->Template_AddJs($this->sTemplatePath."assets/js/canvasAreaDraw.min.js");
		$this->Template_AddJs($this->sTemplatePath."assets/js/com_pattern.js");

		$oMap = $this->ComponentPattern_Pattern_GetMapByNode($this->oNode->getId());
		$this->Template_Assign("oMap", $oMap);

		$iId = intval(Router::GetParam("0"));
		$oPattern = $this->ComponentPattern_Pattern_GetPatternById($iId);
		if( !$oPattern ) $this->NotFound();
		$this->Template_Assign("oPattern", $oPattern);
		
		$this->SetTemplate("form.tpl");
	}

	protected function ActionAdd() {
		$this->Template_Assign("sFormTitle", "Добавление Области");
		$this->Template_Assign("sFormAction", "update");
		$this->Template_AddCss($this->sTemplatePath."assets/chosen-bootstrap/chosen/chosen.css");
		$this->Template_AddCss($this->sTemplatePath."assets/bootstrap-toggle-buttons/static/stylesheets/bootstrap-toggle-buttons.css");
		$this->Template_AddCss($this->sTemplatePath."assets/bootstrap/css/bootstrap-fileupload.css");
		$this->Template_AddCss($this->Template_GetHost()."components/pattern/templates/admin/com_pattern.css");
		$this->Template_AddJs($this->sTemplatePath."assets/chosen-bootstrap/chosen/chosen.jquery.min.js");
		$this->Template_AddJs($this->sTemplatePath."assets/bootstrap-toggle-buttons/static/js/jquery.toggle.buttons.js");
		$this->Template_AddJs($this->sTemplatePath."assets/bootstrap/js/bootstrap-fileupload.js");
		$this->Template_AddJs($this->sTemplatePath."assets/ckeditor/ckeditor.js");
		$this->Template_AddJs($this->sTemplatePath."assets/js/canvasAreaDraw.min.js");
		$this->Template_AddJs($this->sTemplatePath."assets/js/com_pattern.js");

		$oMap = $this->ComponentPattern_Pattern_GetMapByNode($this->oNode->getId());
		$this->Template_Assign("oMap", $oMap);

		$oPattern = Engine::GetEntity('ComponentPattern_Pattern', null);
		$oPattern->setActive(1);

		$this->Template_Assign("oPattern", $oPattern);
		
		$this->SetTemplate("form.tpl");
	}


	protected function ActionUpdate() {
		$iPatternId = GetRequest("id", null, "id");
		if( $iPatternId ){
			$oPattern = $this->ComponentPattern_Pattern_GetPatternById( $iPatternId );
		}else{
			$oPattern = Engine::GetEntity('ComponentPattern_Pattern', null);
		}
		
		$oPattern->setTitle(  GetRequest("title")  );
		$oPattern->setCoords( GetRequest("coords")  );
		$oPattern->setDesc(   GetRequest("desc")    );
		$oPattern->setActive( GetRequest("active")  );
		$oPattern->setNode(   $this->oNode->getId() );
		
		if ( is_numeric($oPattern->getId()) ) $this->ComponentPattern_Pattern_Update($oPattern);
		else $oPattern = $this->ComponentPattern_Pattern_Add($oPattern);

		if ( getRequest("apply") ) header("Location: ".Config::Get("host")."admin/content/".$this->oNode->getId()."/edit/".$oPattern->getId());
		else header("Location: ".Config::Get("host")."admin/content/".$this->oNode->getId());
	}

	protected function ActionDelete(){
		$iPatternId = intval($this->aParams[0]);
		if( $iPatternId ){
			$oPattern = $this->ComponentPattern_Pattern_GetPatternById($iPatternId);
			if( $oPattern ) $this->ComponentPattern_Pattern_Delete($oPattern);
		}
		header("Location: ".Config::Get("host")."admin/content/".$this->oNode->getId()."/");
		exit;
	}

	protected function ActionRemoveImage(){
		$iId  = intval(Router::GetParam(0));
		$oMap = $this->ComponentPattern_Pattern_GetMapById($iId);
		$this->Image_Delete( $oMap->getImg() );
		$oMap->setImg("");
		$this->ComponentPattern_Pattern_UpdateMap($oMap);
		exit;
	}
	protected function ActionDeleteAll(){
		if( $aIds = $checked=explode(',', $this->aParams[0]) ){
			foreach ($aIds as $iId){
				$oPattern = $this->ComponentPattern_Pattern_GetPatternById( intval($iId) );
				$this->ComponentPattern_Pattern_Delete( $oPattern );
			}
		}
		$result['state']="success";
		$result['msg']=0;
		echo json_encode($result);
		exit;
	}
}