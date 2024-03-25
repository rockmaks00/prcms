<?
class ComponentSearch_ModuleSearch_EntityResult extends Entity {
	/*public function getNodeObject(){
		list($sKey, $sVal) = each($this->_aData);
		$sEntity = mb_substr($sKey, 0, mb_strpos($sKey, "_")+1);
		$iNodeId = $this->_aData[$sEntity."node"];
		$oNode = Engine::GetInstance()->Node_GetNodeById($iNodeId);
		if( !$oNode ) $oNode = Engine::GetEntity('Node');
		return $oNode;
	}
	public function getNodeUrl(){
		return $this->getNodeObject()->getFullUrl();
	}*/
	public function addCount($iCount){
		$this->setCount($iCount + $this->getCount());
	}
	public function getTitle(){
		if( !$this->_aData["result_title"] ){
			$sTitle = $this->getNode()->getTitle();
			if(!$sTitle) $sTitle = "...";
			$this->setTitle($sTitle);
		}
		return $this->_aData["result_title"];
	}
	public function getTextCut(){
		$iCount = 30;
		$sText = $this->getText();
		$aText = explode(" ", $sText);
		$sCut = implode(" ", array_slice($aText, 0, $iCount) );
		if( count($aText) > $iCount ) $sCut .= "...";
		return $sCut;
	}

}	