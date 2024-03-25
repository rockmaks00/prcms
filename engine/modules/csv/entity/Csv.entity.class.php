<?
class ModuleCsv_EntityCsv extends Entity {
	public function getVaules(){
		return $this->_aData;
	}
	public function getValue($iIndex){
		if( isset($this->_aData[$iIndex]) )
			return $this->_aData[$iIndex];
		else if( is_numeric($iIndex) ){
			$sValue = array_values($this->_aData);
			return $sValue[$iIndex];
		}
		return NULL;
	}
}