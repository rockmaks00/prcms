<?
class ComponentFeedback_ModuleFeedback_EntityValue extends Entity {
	public function getId() {
        return $this->_aData['value_id'];
    }
    
    public function setId($data) {
    	$this->_aData['value_id']=$data;
    }
}	