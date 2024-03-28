<?
abstract class Module extends EngineObject {
	
	protected $oEngine=null;
	
	final public function __construct(Engine $oEngine) {		
		$this->oEngine=$oEngine;
	}
	
	public function __call($sName,$aArgs) {
		return $this->oEngine->_CallModule($sName, $aArgs);
	}
}	