<?
class ModuleBanner_DbBanner extends Db {
	public function Install(){
		if( !$this->oDb->CheckTableExists(Config::Get("db.prefix")."banners") ){
			$sql = "CREATE TABLE IF NOT EXISTS `".Config::Get("db.prefix")."banners` (
				`banner_id` int(11) NOT NULL AUTO_INCREMENT,
				`banner_title` varchar(250) NOT NULL,
				`banner_desc` text NOT NULL,
				`banner_url` varchar(250) NOT NULL,
				`banner_priority` int(3) NOT NULL,
				`banner_img` varchar(250) NOT NULL DEFAULT '',
				`banner_group` int(11) NOT NULL,
				`banner_active` int(1) NOT NULL,
				`banner_seen` int(11) NOT NULL,
				PRIMARY KEY (`banner_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8";
			$this->oDb->Query($sql);
		}
		if( !$this->oDb->CheckTableExists(Config::Get("db.prefix")."banner_groups") ){
			$sql = "CREATE TABLE IF NOT EXISTS `".Config::Get("db.prefix")."banner_groups` (
				`group_id` int(11) NOT NULL AUTO_INCREMENT,
				`group_title` varchar(250) NOT NULL,
				`group_desc` text NOT NULL,
				PRIMARY KEY (`group_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8";
			$this->oDb->Query($sql);
		}
	}

	
	public function Add($oBanner){
		$sql = "INSERT INTO ".Config::Get("db.prefix")."banners (
				banner_title,
				banner_desc,
				banner_url,
				banner_priority,
				banner_img,
				banner_group,
				banner_active
			) 
			VALUES (?, ?, ?, ?, ?, ?, ?)";
		return $this->oDb->Query($sql, 
			$oBanner->getTitle(),
			$oBanner->getDesc(),
			$oBanner->getUrl(),
			$oBanner->getPriority(),
			$oBanner->getImg(),
			$oBanner->getGroup(),
			$oBanner->getActive()
		);
	}
	public function Update($oBanner){
		$sql = "UPDATE ".Config::Get("db.prefix")."banners SET 
				banner_title=?,
				banner_desc=?,
				banner_url=?,
				banner_priority=?,
				banner_img=?,
				banner_group=?,
				banner_active=?
			WHERE banner_id=?";
		return $this->oDb->Query($sql, 
			$oBanner->getTitle(),
			$oBanner->getDesc(),
			$oBanner->getUrl(),
			$oBanner->getPriority(),
			$oBanner->getImg(),
			$oBanner->getGroup(),
			$oBanner->getActive(),
			$oBanner->getId()
		);
	}
	public function GetBannerById($iId) {
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."banners WHERE banner_id=?";
		$aRow=$this->oDb->SelectRow($sql, $iId);
		if ($aRow) return Engine::GetEntity('Banner', $aRow);
		else return null;
	}
	public function GetBannersByGroup($iGroupId) {
		$sql = "SELECT banner_id FROM ".Config::Get("db.prefix")."banners WHERE banner_group = ? ORDER BY banner_id";
		$aRows = $this->oDb->Select($sql, $iGroupId);
		return array_map(function($var){ return $var["banner_id"]; }, $aRows);
	}
	
	public function IncrementSeen($iId) {
		$sql = "UPDATE ".Config::Get("db.prefix")."banners SET banner_seen = banner_seen+1 WHERE banner_id=?";
		if ($this->oDb->Query($sql, $iId)) return true;
		else return false;
	}

	public function Activate($iId) {
		$sql = "UPDATE ".Config::Get("db.prefix")."banners SET banner_active=1 WHERE banner_id=?";
		if ($this->oDb->Query($sql, $iId)) return true;
		else return false;
	}
	
	public function Deactivate($iId) {
		$sql = "UPDATE ".Config::Get("db.prefix")."banners SET banner_active=0 WHERE banner_id=?";
		if ($this->oDb->Query($sql, $iId)) return true;
		else return false;
	}
	
	public function Delete($iId) {
		$sql = "DELETE FROM ".Config::Get("db.prefix")."banners WHERE banner_id=?";
		if ($this->oDb->Query($sql, $iId)) return true;
		else return false;
	}

/*GROUPS*/
	public function AddGroup($oGroup){
		$sql = "INSERT INTO ".Config::Get("db.prefix")."banner_groups (
				group_title,
				group_desc
			) 
			VALUES(?, ?)
		";
		return $this->oDb->Query($sql, 
			$oGroup->getTitle(),
			$oGroup->getDesc()
		);
	}
	public function UpdateGroup($oGroup){
		$sql = "UPDATE ".Config::Get("db.prefix")."banner_groups SET 
				group_title=?,
				group_desc=?		
			WHERE group_id=?
		";
		return $this->oDb->Query($sql, 
			$oGroup->getTitle(),
			$oGroup->getDesc(),
			$oGroup->getId()
		);
	}
	
	public function DeleteGroup($iGroupId) {
		$sql = "DELETE FROM ".Config::Get("db.prefix")."banner_groups WHERE group_id=?";
		if ( $this->oDb->Query($sql, $iGroupId) ) return true;
		else return false;
	}
	public function GetGroupById($iGroupId) {
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."banner_groups WHERE group_id=?";
		$aRow=$this->oDb->SelectRow($sql, $iGroupId);
		if ($aRow) return Engine::GetEntity('Banner', $aRow, "Group");
		else return null;
	}
	public function GetGroups() {
		$sql = "SELECT group_id FROM ".Config::Get("db.prefix")."banner_groups";
		$aRows = $this->oDb->Select($sql);
		return array_map(function($aRow){return $aRow["group_id"];}, $aRows);
	}
}	