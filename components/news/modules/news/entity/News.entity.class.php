<?
class ComponentNews_ModuleNews_EntityNews extends Entity {
	public function getId() {
		return $this->_aData['news_id'];
	}

	public function getDate() {
		$sDatetime=explode(" ", $this->_aData['news_datetime']);
		return $sDatetime[0];
	}

	public function getTime() {
		$sDatetime=explode(" ", $this->_aData['news_datetime']);
		return substr($sDatetime[1], 0, 5);
	}

	public function setId($data) {
		$this->_aData['news_id']=$data;
	}

	public function setDate($data) {
		$sDatetime=explode(" ", $this->_aData['news_datetime']);
		$sDatetime[0]=$data;
		$this->_aData['news_datetime']=implode(" ", $sDatetime);
	}

	public function setTime($data) {
		$sDatetime=explode(" ", $this->_aData['news_datetime']);
		if (!$sDatetime[0]) $sDatetime[0]="1970-01-01";
		$sDatetime[1]=$data;
		$this->_aData['news_datetime']=implode(" ", $sDatetime);
	}
}	