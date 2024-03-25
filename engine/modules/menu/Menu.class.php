<?
class ModuleMenu extends Module {
	protected $oDb;
	protected $oComponent;
	public function Init() {
		$this->oDb=Engine::GetDb(__CLASS__);
		$this->oDb->Install();
	}
	public function Add(ModuleMenu_EntityMenu $oMenu) {
		$this->Cache_Delete("menus");
		if ($iId=$this->oDb->Add($oMenu)){
			$oMenu->setId($iId);
		}
		return $oMenu;
	}
	public function Update(ModuleMenu_EntityMenu $oMenu) {
		$this->Cache_Delete("menu_{$oMenu->getId()}");
		$this->Cache_Delete("menus");
		return $this->oDb->Update($oMenu);
	}
	public function Delete($iMenuId) {
		$this->Cache_Delete("menu_{$iMenuId}");
		$this->Cache_Delete("menus");
		return $this->oDb->Delete($iMenuId);
	}
	public function GetMenu() {
		if (false === ($data = $this->Cache_Get("menus"))) {	
			$data=$this->oDb->GetMenu();
			$this->Cache_Set("menus", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	public function GetMenuByName($sName) {
		$iId = $this->oDb->GetMenuByName($sName);
		$data = $this->GetMenuById($iId);
		return $data;
	}
	public function GetMenuById($iId) {
		if (false === ($data = $this->Cache_Get("menu_{$iId}"))) {
			$data=$this->oDb->GetMenuById($iId);
			//$data=$this->GetMenuAdditionalData($oMenu);
			$this->Cache_Set("menu_{$iId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	public function Activate($iMenuId) {
		$this->Cache_Delete("menu_{$iMenuId}");
		$this->Cache_Delete("menus");
		return $this->oDb->Activate($iMenuId);
	}
	
	public function Deactivate($iMenuId) {
		$this->Cache_Delete("menu_{$iMenuId}");
		$this->Cache_Delete("menus");
		return $this->oDb->Deactivate($iMenuId);
	}
	

/*items*/
	public function AddItem(ModuleMenu_EntityItem $oItem) {
		$this->Cache_Delete("menu_items_{$oItem->getMenu()}");
		if ($iId=$this->oDb->AddItem($oItem)){
			$oItem->setId($iId);
		}
		return $oItem;
	}
	public function UpdateItem(ModuleMenu_EntityItem $oItem) {
		$this->Cache_Delete("menu_item_{$oItem->getId()}");
		$this->Cache_Delete("menu_items_{$oItem->getMenu()}");
		return $this->oDb->UpdateItem($oItem);
	}
	public function DeleteItem($iItemId) {
		$this->Cache_Delete("menu_item_{$iItemId}");
		$oItem = $this->GetItemById($iItemId);
		$this->Cache_Delete("menu_items_{$oItem->getMenu()}");
		return $this->oDb->DeleteItem($iItemId);
	}
	public function DeleteItemsByMenu($iMenuId){
		if($iMenuId){
			$aItems = $this->GetItemList(intval($iMenuId));
			foreach($aItems as $oItem){
				$this->DeleteItem($oItem->getId());
			}
			return true;
		}else{
			return false;
		}
	}
	public function GetItemList($iMenuId) {
		if (false === ($data = $this->Cache_Get("menu_items_{$iMenuId}"))) {	
			$data=$this->oDb->GetItemList($iMenuId);
			$this->Cache_Set("menu_items_{$iMenuId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	public function GetItemById($iItemId) {
		if (false === ($data = $this->Cache_Get("menu_item_{$iItemId}"))) {
			$data=$this->oDb->GetItemById($iItemId);
			$this->Cache_Set("menu_item_{$iItemId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	public function GetItemsByParent($iItemId, $sMenuId){
		$data=$this->oDb->GetItemsByParent($iItemId, $sMenuId);
		return $data;
	}
	public function GetMenuStructure($sMenuId, $iItemId=0){
		$aMenu=$this->GetItemsByParent($iItemId, $sMenuId);
		$aTree=array();
		foreach( $aMenu as $key => $oMenu ) {
			$aTmp = array();
			$aTmp["item"] = $oMenu;
			$aChilds = $this->GetMenuStructure($oMenu->getMenu(), $oMenu->getId());
			if( $aChilds ){
				foreach( $aChilds as $aChild){
					if( $aChild["item"]->getCurrent() ) { $aTmp["item"]->setCurrent(1); }
				}
				$aTmp["childs"] = $aChilds;
			}
			$aTree[] = $aTmp;
		}
		return $aTree;
	}
	public function SortItem($oItem, $sAction) {
		$aItems=$this->oDb->GetItemsByParent($oItem->getParent(), $oItem->getMenu());
		foreach($aItems as $i=>$oItemEach){
			$oItemEach->setSort(($i+1));
			if ($oItemEach->getId()==$oItem->getId()) $index=$i;
		}
		if ($sAction=="up"){
			if (isset($aItems[$index-1])){
				$tmp=$aItems[$index-1]->getSort();
				$aItems[$index-1]->setSort($aItems[$index]->getSort());
				$aItems[$index]->setSort($tmp);
			}
		}
		if ($sAction=="down"){
			if (isset($aItems[$index+1])){
				$tmp=$aItems[$index+1]->getSort();
				$aItems[$index+1]->setSort($aItems[$index]->getSort());
				$aItems[$index]->setSort($tmp);
			}
		}
		foreach($aItems as $oItemEach){
			$this->oDb->UpdateItem($oItemEach);
		}
		return true;
	}
	public function ActivateItem($oItem) {
		$this->Cache_Delete("menu_item_{$oItem->getId()}");
		$this->Cache_Delete("menu_items_{$oItem->getMenu()}");
		return $this->oDb->ActivateItem($oItem->getId());
	}
	
	public function DeactivateItem($oItem) {
		$this->Cache_Delete("menu_item_{$oItem->getId()}");
		$this->Cache_Delete("menu_items_{$oItem->getMenu()}");
		return $this->oDb->DeactivateItem($oItem->getId());
	}
}