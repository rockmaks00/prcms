<?
class ComponentGallery extends Component {
	public function Init(){		
		$this->SetDefaultAction('default');
		$this->oNode=Router::GetCurrentNode();
		$this->Template_SetPageTitle($this->oNode->getTitle());
		$this->Template_AddTitle($this->oNode->getTitle());
	}
	
	protected function RegisterActions() {
		$this->AddAction('default','ActionDefault');
		$this->AddActionPreg('/^(\d+)?$/i','ActionGallery');
	}
		
	protected function ActionDefault() {
		$aGalleries=$this->ComponentGallery_Gallery_GetGalleriesByNode($this->oNode->getId());
		$this->Template_Assign("aGalleries", $aGalleries);
		$this->SetTemplate("default.tpl");
	}
	
	protected function ActionGallery() {
		$aImages=$this->ComponentGallery_Gallery_GetImagesByGallery(intval(Router::GetAction()));
		$this->Template_Assign("aImages", $aImages);
		$this->SetTemplate("gallery.tpl");
	}
}	
