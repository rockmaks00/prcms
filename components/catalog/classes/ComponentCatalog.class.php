<?
class ComponentCatalog extends Component {
	public function Init(){		
		$this->SetDefaultAction('default');
		$this->oNode=Router::GetCurrentNode();
		$this->Template_SetPageTitle($this->oNode->getTitle());
		$this->Template_AddTitle($this->oNode->getTitle());
	}
	protected function RegisterActions() {
		$this->AddAction('default','ActionDefault');
		$this->AddActionPreg('/^(\d+)\-.*$/i','ActionDefault');
		$this->AddAction('detail','ActionDetail');
		$this->AddAction('cart','ActionCart');
		//$this->AddActionPreg('/^bad$/i','/^(page(\d+))?$/i','ActionDefault');
	}
	protected function ActionDefault() {
		$action = Router::GetAction();
		preg_match_all('/^(\d+)\-.*$/i', $action, $matches);
		$iGroupId = intval($matches[1][0]);
		//$aParams=Router::GetParams();
		//preg_match_all('/^(\d+)\-.*$/i', $aParams[0], $matches2);
		//$iItemId = intval($matches2[1][0]);
		
		$aGroups = $this->ComponentCatalog_Catalog_GetGroupsByNode($this->oNode->getId(), $iGroupId);		
		$aItems = $this->ComponentCatalog_Catalog_GetItemsByGroup($iGroupId, $this->oNode->getId());
		
		$this->Template_Assign("aGroups", $aGroups);
		$this->Template_Assign("aItems", $aItems);
		$this->SetTemplate("list.tpl");
	}

	protected function ActionDetail() {
		$aParams=Router::GetParams();
		preg_match_all('/^(\d+)\-.*$/i', $aParams[0], $matches2);
		$iItemId = intval($matches2[1][0]);
		
		$oItem=$this->ComponentCatalog_Catalog_GetItemById($iItemId);
		$oGroup=$this->ComponentCatalog_Catalog_GetGroupById($oItem->getId());

		$this->Template_AddTitle($oItem->getTitle());
		$this->Template_Assign("oGroup", $oGroup);
		$this->Template_Assign("oItem", $oItem);
		$this->SetTemplate("detail.tpl");
	}
	
	protected function ActionCart() {
		$aParams=Router::GetParams();
		if ($aParams[0]=="add"){
			$iItemId=intval($aParams[1]);
			$iCount=intval($aParams[2]);
			if (!$iCount) $iCount = 1;
			
			$oItem=$this->ComponentCatalog_Catalog_GetItemById($iItemId);
			$this->ComponentCatalog_Catalog_CartAdd($oItem->getId(), $iCount);
			
			echo $this->ComponentCatalog_HookCart_Cart(array("template"=>"cart"));
			exit;
		}else{
			$this->SetTemplate("cart.tpl");
		}
		/*
$iItemId = intval($matches2[1][0]);
		
		$oItem=$this->ComponentCatalog_Catalog_GetItemById($iItemId);
		$oGroup=$this->ComponentCatalog_Catalog_GetGroupById($oItem->getId());

		$this->Template_AddTitle($oItem->getTitle());
		$this->Template_Assign("oGroup", $oGroup);
		$this->Template_Assign("oItem", $oItem);
*/
		
	}
}	
