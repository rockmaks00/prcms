<?
class ComponentFeedback_ModuleFeedback_EntityFeedback extends Entity {
	public function getId() {
        return $this->_aData['feedback_id'];
    }

	public function getPageObject(){
		if( empty($this->_aData['feedback_page_object']) ){
			$iNode = $this->getNode();
			if( !$iNode ) return false;
			$oPage = Engine::GetInstance()->ComponentPage_Page_GetPageByNode( $iNode );
			if(empty($oPage)) $oPage = Engine::GetEntity("ComponentPage_Page");
			$this->setPageObject($oPage);
		}
		return $this->_aData['feedback_page_object'];
	}

	public function setText($sText){
		$this->getPageObject()->setBody($sText);
	}
	public function getText(){
		return $this->getPageObject()->getBody();
	}
}