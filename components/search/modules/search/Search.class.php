<?
class ComponentSearch_ModuleSearch extends Module {
	protected $oDb;
	public function Init() {
		$this->oDb=Engine::GetDb(__CLASS__);
		$this->oDb->Install();
	}
	public function ClearText($sSearchQuery) {
		$oStemmer = new Lingua_Stem_Ru();
		$sSearchQuery = preg_replace("/[^\w\x7F-\xFF\s]/"," ",strip_tags($sSearchQuery));
		$aWords = explode(' ',$sSearchQuery);
		foreach($aWords as $sWord){
			$sStemmedWord = $oStemmer->stem_word($sWord);
			if( mb_strlen($sStemmedWord, 'UTF-8') > 2 ) $aStemmedWords[] = mb_strtolower($sStemmedWord);
		}
		return $aStemmedWords;
	}
	public function GetComponentsToSearchByNode($iId){
		if (false === ($data = $this->Cache_Get("search_{$iId}"))) {
			$data=$this->oDb->Select($iId);
			$this->Cache_Set("search_{$iId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	public function DeleteComponentsByNode($iId){
		$this->Cache_Delete("search_{$iId}");
		$this->oDb->Delete($iId);
	}
	public function AddComponentToSearch($oSearch){
		$this->Cache_Delete("search_{$oSearch->getNode()}");
		return $this->oDb->Insert($oSearch);
	}
	public function Search(){
		return array();
	}
}