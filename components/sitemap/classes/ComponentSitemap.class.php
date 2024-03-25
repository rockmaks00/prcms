<?
class ComponentSitemap extends Component {
	public $oNode=null;
		
	public function Init(){		
		$this->SetDefaultAction('default');
		$this->oNode=Router::GetCurrentNode();
	}
	
	protected function RegisterActions() {
		$this->AddAction('default','ActionDefault');
		$this->AddActionPreg('/^(page(\d+))?$/i','ActionDefault');
	}
		
	protected function ActionDefault() {
		$aSitemap = array();
		$aMenus = $this->Menu_GetMenu();
		foreach ($aMenus as $iKey=>$oMenu) {
			$aSitemap[$iKey]["menu"]  = $oMenu;
			$aSitemap[$iKey]["items"] = $this->Menu_GetMenuStructure( $oMenu->getId() );
		}
		$this->Template_Assign("aSitemap", $aSitemap);
		$this->SetTemplate("sitemap.tpl");
	}
}	
