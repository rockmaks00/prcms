<?
abstract class Entity extends Object {	
	protected $_aData=array();
	public function __construct($aData) {
		$this->_setData($aData);
	}
	public function _setData($aData){
		if (is_array($aData)){
			foreach ($aData as $var => $val){
				$this->_aData[$var] = $val;
			}
		}
	}
	public function getInstance(){
		return $this->_aData;
	}
	public function __call($sName, $aArgs) {
		$sType=strtolower(substr($sName,0,3));
		$sClass=explode("_", get_class($this));
		if (count($sClass)==2) $sClass=strtolower(str_replace("Entity", "", $sClass[1]));
		if (count($sClass)==3) $sClass=strtolower(str_replace("Entity", "", $sClass[2]));
		
		if (in_array($sType, array('get','set'))) {	
			$sKey=strtolower(preg_replace('/([^A-Z])([A-Z])/',"$1_$2",substr($sName,3)));
			if ($sType=='get'){	
				if (isset($this->_aData[$sClass."_".$sKey])) {					
					return $this->_aData[$sClass."_".$sKey];
				}
				return null;
			} elseif ($sType=='set' and isset($aArgs[0])) {
				$this->_aData[$sClass."_".$sKey]=$aArgs[0];
			}
		} 
	}
}