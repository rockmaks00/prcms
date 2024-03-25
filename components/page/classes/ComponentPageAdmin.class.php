<?
class ComponentPageAdmin extends Component {
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
		$this->AddAction('update','ActionUpdate');
		$this->AddActionPreg('/^bad$/i','/^(page(\d+))?$/i','ActionDefault');
		$this->AddActionPreg('/^new$/i','/^(page(\d+))?$/i','ActionDefault');
	}
		
	protected function ActionDefault() {
		if( !$this->AccessCheck("R") ) exit;
		$this->Template_Assign("sFormTitle", "Редактирование страницы");
		$this->Template_Assign("sFormAction", "update");
		$this->Template_AddCss($this->sTemplatePath."assets/chosen-bootstrap/chosen/chosen.css");
		$this->Template_AddCss($this->sTemplatePath."assets/bootstrap-toggle-buttons/static/stylesheets/bootstrap-toggle-buttons.css");
		$this->Template_AddCss($this->sTemplatePath."assets/bootstrap/css/bootstrap-fileupload.css");
		$this->Template_AddJs($this->sTemplatePath."assets/chosen-bootstrap/chosen/chosen.jquery.min.js");
		$this->Template_AddJs($this->sTemplatePath."assets/bootstrap-toggle-buttons/static/js/jquery.toggle.buttons.js");
		$this->Template_AddJs($this->sTemplatePath."assets/bootstrap/js/bootstrap-fileupload.js");
		$this->Template_AddJs($this->sTemplatePath."assets/ckeditor/ckeditor.js");		
		
		$oPage=$this->ComponentPage_Page_GetPageByNode($this->oNode->getId());
		if ($oPage){
			$_REQUEST['content']=$oPage->getBody();
		}
		
		$this->SetTemplate("page_form.tpl");
	}
	protected function ActionUpdate() {
		if( !$this->AccessCheck("V") ) exit;
		$oPage=$this->ComponentPage_Page_GetPageByNode($this->oNode->getId());
		if (!$oPage) $oPage=Engine::GetEntity('ComponentPage_Page');
		$oPage->setBody(getRequest('content'));
		$oPage->setNode($this->oNode->getId());
		
		
		if ($oPage->getId()) $this->ComponentPage_Page_Update($oPage);
		else $oPage=$this->ComponentPage_Page_Add($oPage);
		
		if (getRequest("apply")) header("Location: ".Config::Get("host")."admin/content/".$this->oNode->getId()."/");
		else header("Location: ".Config::Get("host")."admin/nodes/");
	}
}	
