<?
class ComponentSearch_HookSearch extends Hook {
	public function Init() {
		
	}
	public function Search($aParams) {
		// $iNode=intval($aParams['node']);
		// if (!$iNode) return false;
		
		// $oFeedback=$this->ComponentFeedback_ModuleFeedback_GetFeedbackByNode($iNode);
		// $aFields=$this->ComponentFeedback_ModuleFeedback_GetFieldsByFeedback($oFeedback->getId());

		// $this->Template_Assign('oFeedback', $oFeedback);
		// $this->Template_Assign('aFields', $aFields);
		$iNode = intval($aParams['node']);
		if (!$iNode) return false;
		$oNode = $this->Node_GetNodeById($iNode);
		$this->Template_Assign('sAction', $oNode->getFullUrl()."results/");
		
		return $this->Template_Fetch("components/search/templates/hooks/".$aParams['template']."/".$aParams['template'].".tpl");
	}
}