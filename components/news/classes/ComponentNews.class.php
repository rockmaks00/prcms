<?
class ComponentNews extends Component {
	public $oNode=null;

	public function Init(){		
		$this->SetDefaultAction('default');
		$this->oNode=Router::GetCurrentNode();
		$this->Template_SetPageTitle($this->oNode->getTitle());
	}
	
	protected function RegisterActions() {
		$this->AddAction('default','ActionDefault');
		$this->AddActionPreg('/^(\d+)$/i','ActionDetail');
		$this->AddActionPreg('/^bad$/i','/^(page(\d+))?$/i','ActionDefault');
		$this->AddAction('page','ActionDefault');
	}
		
	protected function ActionDefault() {
		$iOnPage = $this->oNode->getParam("onpage");

		$iPage = Router::GetParam(0);
		if(!$iPage) $iPage=1;

		$iCount = $this->ComponentNews_News_GetActiveNewsCountByNode($this->oNode->getId());
		$aNews  = $this->ComponentNews_News_GetActiveNewsByNode($this->oNode->getId(), $iOnPage, $iPage);

		$this->Template_Assign("iCount", $iCount);
		$this->Template_Assign("iPage", $iPage);
		$this->Template_Assign("iOnPage", $iOnPage);
		$this->Template_Assign("aNews", $aNews);
		$this->SetTemplate("default.tpl");
	}
	
	protected function ActionDetail() {
		$iId=intval(Router::GetAction());
		$oNews=$this->ComponentNews_News_GetNewsById($iId);
		if(!$oNews) return $this->NotFound();

		$this->Template_AddBreadCrumb(
			$this->oNode->getFullUrl()."/".$oNews->getId()."/", 
			$oNews->getTitle()
		);

		$this->Template_SetPageTitle("");
		$this->Template_AddTitle($oNews->getTitle());
		$this->Template_Assign("oNews", $oNews);
		$this->SetTemplate("detail.tpl");
	}
}