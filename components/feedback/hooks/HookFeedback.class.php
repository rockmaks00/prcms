<?
class ComponentFeedback_HookFeedback extends Hook {
	public function Init() {
		
	}
	public function Feedback($aParams) {
		$iId=intval($aParams['node']);
		if( !$iId ) return false;
		if( !($oNode = $this->Node_GetNodeById($iId)) ) return false;

		if( !($oFeedback=$this->ComponentFeedback_ModuleFeedback_GetFeedbackByNode($iId) ) ) return false;

		$aFields=$this->ComponentFeedback_ModuleFeedback_GetFieldsByFeedback($oFeedback->getId());
		$oPage = $this->ComponentPage_Page_GetPageByNode($oNode->getId());

		$this->Template_Assign('oFeedback', $oFeedback);
		$this->Template_Assign('oPage', $oPage);
		$this->Template_Assign('oNode', $oNode);
		$this->Template_Assign('aFields', $aFields);
		$this->Template_AddJs(Config::Get("host")."external/form.js");
		
		$sJs = "components/feedback/templates/hooks/".$aParams['template']."/custom.js";
		if( file_exists($sJs) )
			$this->Template_AddJs(Config::Get("host").$sJs);

		$sCss = "components/feedback/templates/hooks/".$aParams['template']."/styles.css";
		if( file_exists($sCss) )
			$this->Template_AddCss(Config::Get("host").$sCss);
				
		return $this->Template_Fetch("components/feedback/templates/hooks/".$aParams['template']."/form.tpl");
	}
}