<?
class ComponentFeedback_HookFeedback extends Hook {
	public function Init() {
		
	}
	public function Feedback($aParams) {
		$iNode=intval($aParams['node']);
		if (!$iNode) return false;
		
		$oFeedback=$this->ComponentFeedback_ModuleFeedback_GetFeedbackByNode($iNode);
		$aFields=$this->ComponentFeedback_ModuleFeedback_GetFieldsByFeedback($oFeedback->getId());

		$this->Template_Assign('oFeedback', $oFeedback);
		$this->Template_Assign('aFields', $aFields);
		
		return $this->Template_Fetch("components/feedback/templates/hooks/".$aParams['template'].".tpl");
	}
}