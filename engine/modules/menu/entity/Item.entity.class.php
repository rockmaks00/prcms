<?
class ModuleMenu_EntityItem extends Entity {
	public function getItemUrl() {
		$sUrl = $this->_aData['item_url'];
		$sNodePath = $this->getNodeObject()->getFullUrl();
		return $sUrl ? $sUrl : $sNodePath;
	}
	public function getNodeObject() {
		$iNodeId = $this->_aData['item_node'];
		$oNode = Engine::GetInstance()->Node_GetNodeById($iNodeId);
		if(!$oNode) $oNode = Engine::GetEntity('Node');
		return $oNode;
	}
	public function getCurrent() {
		return (Router::GetCurrentNode()->getId() == $this->_aData['item_node']);
	}

	public function setUploadedImg(){
		if(isset($_FILES['image']) and is_uploaded_file($_FILES['image']['tmp_name'])) {
			Engine::GetInstance()->Image_Delete($this->getImg());

			if ($sFileName = Engine::GetInstance()->Image_UploadImage($_FILES['image'],"menus")) {
				$this->setImg($sFileName);
			}else{
				return false;
			}
		}
		return true;
	}
	
}