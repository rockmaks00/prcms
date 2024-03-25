<?
class ModuleMail_EntityMail extends Entity {
	protected $aHeaders = array("MIME-Version" => "1.0",
		"Content-type" => 'text/html; charset="utf-8"');

	public function setHeader($sName, $sValue){
		$this->_aData['headers'][$sName] = $sValue;
	}
	public function setFrom($sMail){
		$this->setHeader("From", $sMail);
	}
	public function getHeaders(){
		if( is_array($this->_aData['headers']) ) $aResult = $this->_aData['headers'] + $this->aHeaders;
		else $aResult = $this->aHeaders;
		$sHeaders = "";
		foreach ($aResult as $sName => $sValue) {
			$sHeaders .= $sName.": ".$sValue."\r\n";
		}
		return $sHeaders;
	}
}