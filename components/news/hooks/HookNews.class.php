<?
class ComponentNews_HookNews extends Hook {
	public function Init() {
		
	}
	public function News($aParams) {
		$iNode=intval($aParams['node']);
		if (!$iNode) return false;
		
		$aNews=$this->ComponentNews_ModuleNews_GetNewsByNode($iNode);
		for($i=$aParams['limit']; $i<=count($aNews); $i++) unset($aNews[$i]);
		$this->Template_Assign('aNews', $aNews);
		
		if(!$aParams['template']) $aParams['template']="default";
		return $this->Template_Fetch("components/news/templates/hooks/".$aParams['template']."/default.tpl");
	}
}