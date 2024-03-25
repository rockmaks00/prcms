<?
class ModuleNode_EntityNode extends Entity {

	public function getSeoTitle() {
		return $this->_aData['node_seo_title'];
	}
	public function getSeoDescription() {
		return $this->_aData['node_seo_description'];
	}
	public function getSeoKeywords() {
		return $this->_aData['node_seo_keywords'];
	}

	public function setSeoTitle($data) {
		$this->_aData['node_seo_title']=$data;
	}
	public function setSeoDescription($data) {
		$this->_aData['node_seo_description']=$data;
	}
	public function setSeoKeywords($data) {
		$this->_aData['node_seo_keywords']=$data;
	}

	public function getComponentObject(){
		if( !isset( $this->_aData["node_component_object"] ) ){
			$oComponent = Engine::getInstance()->Component_GetComponentById($this->getComponent());
			if( !$oComponent ) $oComponent = Engine::GetEntity('Component');
			$this->setComponentObject( $oComponent );
		}
		return $this->_aData["node_component_object"];
	}

	public function getFullUrl(){
		if( !isset( $this->_aData["node_full_url"] ) ){

			$iNodeId = $this->_aData['node_id'];
			if(!$iNodeId) return;
			$oEngine=Engine::getInstance();
			$argv = array();
			$oNode = $oEngine->Node_GetNodeById($iNodeId);
			if( !$oNode->getParent() ){
				$this->setFullUrl( Config::Get("host") );
			}else{
				$oParentNode = $oEngine->Node_GetNodeById($oNode->getParent());
				$this->setFullUrl( $oParentNode->getFullUrl().$oNode->getUrl()."/" );
			}
		}
		return $this->_aData["node_full_url"];
	}

	public function setUploadedImg(){
		if(isset($_FILES['image']) and is_uploaded_file($_FILES['image']['tmp_name'])) {
			Engine::GetInstance()->Image_Delete($this->getImage());
			if ($sFileName = Engine::GetInstance()->Image_UploadImage($_FILES['image'],"nodes")) {
				$this->setImage($sFileName);
			}else{
				return false;
			}
		}
		return true;
	}

	
	public function getCurrentParams(){
		if( !isset( $this->_aData["node_current_params"] ) ){
			$aParams = Engine::GetInstance()->Component_GetParamsByNodeComponent( $this->getId(), $this->getComponent() );
			$this->setCurrentParams($aParams);
		}
		return $this->_aData["node_current_params"];
	}
	public function getCurrentParam($sName){
		$aParams = $this->getCurrentParams();
		return $aParams[$sName];
	}

	public function getDefaultParams(){
		return $this->getComponentObject()->getParams();
	}
	public function getDefaultParam($sName){
		return $this->getComponentObject()->getParam($sName);
	}

	public function getParam($sName){
		$sCurrentParam = $this->getCurrentParam($sName);
		if($sCurrentParam) 
			return $sCurrentParam->getVal();
		$sDefaultParam = $this->getDefaultParam($sName);
		return $sDefaultParam["default"];
	}
}