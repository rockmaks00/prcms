<?
class ComponentPattern extends Component {
	public $oNode=null;
		
	public function Init(){		
		$this->SetDefaultAction('default');
		$this->oNode=Router::GetCurrentNode();
		$this->sTemplatePath = Config::Get("host")."components/".$this->oNode->getComponentObject()->getName()."/templates/skins/".$this->oNode->getParam("template")."/";
	}
	
	protected function RegisterActions() {
		$this->AddAction('default','ActionDefault');
		$this->AddActionPreg('/^(page(\d+))?$/i','ActionDefault');
	}
		
	protected function ActionDefault() {
		$oMap = $this->ComponentPattern_Pattern_GetMapByNode($this->oNode->getId());
		$aPatterns = $this->ComponentPattern_Pattern_GetPatternsByNode($this->oNode->getId());
		if (!$aPatterns){
			$this->NotFound();
		}

		$this->Template_Assign("oMap", $oMap);
		$this->Template_Assign("aPatterns", $aPatterns);
		$this->SetTemplate("default.tpl");
		$this->Template_AddJs($this->sTemplatePath."assets/jquery.qtip.min.js");
		$this->Template_AddJs($this->sTemplatePath."assets/pattern.js");
		$this->Template_AddCss($this->sTemplatePath."assets/jquery.qtip.min.css");
		$this->Template_AddCss($this->sTemplatePath."assets/pattern.css");
	}
}