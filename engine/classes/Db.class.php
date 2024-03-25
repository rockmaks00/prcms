<?
abstract class Db extends Object {
	protected $oDb;
	public function __construct($oDb) {
		$this->oDb = $oDb;
	}
}