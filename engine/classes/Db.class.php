<?
abstract class Db extends EngineObject {
	protected $oDb;
	public function __construct($oDb) {
		$this->oDb = $oDb;
	}
}