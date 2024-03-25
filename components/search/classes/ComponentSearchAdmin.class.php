<?
class ComponentSearchAdmin extends Component {
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
	}
	
	protected function RegisterActions() {
		$this->AddAction('default','ActionDefault');
		$this->AddAction('update','ActionUpdate');
		$this->AddActionPreg('/^bad$/i','/^(page(\d+))?$/i','ActionDefault');
		$this->AddActionPreg('/^new$/i','/^(page(\d+))?$/i','ActionDefault');
	}
		
	protected function ActionDefault() {
		$this->Template_Assign("sFormTitle", "Редактирование страницы");
		$this->Template_Assign("sFormAction", "update");
		$this->Template_AddCss($this->sTemplatePath."assets/chosen-bootstrap/chosen/chosen.css");
		$this->Template_AddCss($this->sTemplatePath."assets/bootstrap-toggle-buttons/static/stylesheets/bootstrap-toggle-buttons.css");
		$this->Template_AddCss($this->sTemplatePath."assets/bootstrap/css/bootstrap-fileupload.css");
		$this->Template_AddJs($this->sTemplatePath."assets/chosen-bootstrap/chosen/chosen.jquery.min.js");
		$this->Template_AddJs($this->sTemplatePath."assets/bootstrap-toggle-buttons/static/js/jquery.toggle.buttons.js");
		$this->Template_AddJs($this->sTemplatePath."assets/bootstrap/js/bootstrap-fileupload.js");
		$this->Template_AddJs($this->sTemplatePath."assets/ckeditor/ckeditor.js");

		$aComponents = $this->Component_GetComponents();
		$aComponentsToSearch = $this->ComponentSearch_ModuleSearch_GetComponentsToSearchByNode( $this->oNode->getId() );

		while ( list($iKey, $oComponent) = each($aComponents)) {
			if( !empty($aComponentsToSearch[ $oComponent->getId() ]) )
				$aComponents[$iKey]->setSearch(1);
			if( in_array($oComponent->getName(), array("admin", "search")) )
				unset($aComponents[$iKey]);
		}
		//mpr($aComponents);

		$this->Template_Assign("aComponents", $aComponents);
		$this->Template_Assign("aComponentsToSearch", $aComponentsToSearch);
		$this->SetTemplate("default.tpl");
	}
	
	protected function ActionUpdate() {
		$aComIds = array_keys(getRequest("components"));
		$this->ComponentSearch_ModuleSearch_DeleteComponentsByNode( $this->oNode->getId() );


		foreach ($aComIds as $iComId) {
			$oSearch = Engine::GetEntity("ComponentSearch_Search");
			$oSearch->setNode( $this->oNode->getId() );
			$oSearch->setComponent( intval($iComId) );
			$this->ComponentSearch_ModuleSearch_AddComponentToSearch( $oSearch );
		}

		if (getRequest("apply")) header("Location: ".Config::Get("host")."admin/content/".$this->oNode->getId()."/");
		else header("Location: ".Config::Get("host")."admin/nodes/");
	}
}	
