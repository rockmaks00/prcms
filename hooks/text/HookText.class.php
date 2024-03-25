<?
class HookText extends Hook {
	public function Init() {}
	
	public function Text($aParams) {
		if( empty($aParams["text"]) ) return false;
		$this->Template_Assign('aParams', $aParams);
		return $this->Template_Fetch("hooks/text/templates/".$aParams['template']."/template.tpl");
	}
}