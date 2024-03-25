<?
class HookMenu extends Hook {
	public function Init() {}
	
	public function Menu($aParams) {
		$sMenuName=$aParams['menu'];
		if (!$sMenuName) return false;

		$oMenu = $this->Menu_GetMenuByName($sMenuName);
		if(!$oMenu) return false;

		$aItems=$this->Menu_GetMenuStructure($oMenu->getId());
		$this->Template_Assign('aItems', $aItems);
		
		if(!$aParams['template']) $aParams['template']="default";
		$this->Template_Assign('sTemplate', $aParams['template']);
		return $this->Template_Fetch("hooks/menu/templates/".$aParams['template']."/wrapper.tpl");
	}
}