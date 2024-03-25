<?
class ComponentFeedback_ModuleFeedback_EntityField extends Entity {
	public function getId() {
        return $this->_aData['field_id'];
    }
    
    public function getValueArray() {
    	return explode(";", $this->_aData['field_value']);
    }
    
    public function setId($data) {
    	$this->_aData['field_id']=$data;
    }
    
    public function isArray() {
    	$value=$this->_aData['field_value'];
    	if (strpos($value, ";")) return true;
    	else return false;
    }
}	