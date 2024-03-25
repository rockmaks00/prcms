<?
class ComponentFeedback_ModuleFeedback_EntityAnswer extends Entity {
	public function getPreview($iLength = 50){
		$sText = strip_tags($this->getText());
		$sDots = ( mb_strlen($sText)>$iLength ? "..." : "" );
		return mb_strcut($sText,0,$iLength,"utf-8").$sDots;
	}
}