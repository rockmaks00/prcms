<?
/* 
	Примеры использования запросов! 
	
	$sql = "SELECT * FROM ".Config::Get("db.prefix")."nodes";
	$aResult = array();
	$aRows=$this->oDb->Select($sql);
	foreach ($aRows as $aRow) {
		$aResult[]=Engine::GetEntity('Node',$aRow);
	}
	return $aResult;
	
	$sql = "INSERT INTO ".Config::Get("db.prefix")."nodes (node_id, node_title) VALUES (?, ?)";
	$this->oDb->Query($sql, "1", "Главная");
	
	$sql = "SELECT * FROM ".Config::Get("db.prefix")."nodes WHERE node_url=? AND node_parent=?";
	$aRow=$this->oDb->SelectRow($sql, $sUrl, $iParentId);
	if ($aRow) return Engine::GetEntity('Node',$aRow);
	else return null;
*/

class ModuleUser_DbUser extends Db {
	public function Install(){
		if( !$this->oDb->CheckTableExists(Config::Get("db.prefix")."users") ){
			$sql = "CREATE TABLE IF NOT EXISTS `".Config::Get("db.prefix")."users` (
				`user_id` int(11) NOT NULL AUTO_INCREMENT,
				`user_login` varchar(250) NOT NULL,
				`user_password` varchar(250) NOT NULL,
				`user_group` int(11) NOT NULL,
				`user_email` varchar(25) NOT NULL,
				`user_name` varchar(50) NOT NULL,
				`user_lastlog` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				`user_regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				`user_active` int(11) NOT NULL,
				PRIMARY KEY (`user_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
			$this->oDb->Query($sql);
		}
		if( !$this->oDb->CheckTableExists(Config::Get("db.prefix")."users_accesses") ){
			$sql = "CREATE TABLE IF NOT EXISTS `".Config::Get("db.prefix")."users_accesses` (
				`access_id` int(11) NOT NULL AUTO_INCREMENT,
				`access_group` int(11) NOT NULL,
				`access_type` varchar(25) NOT NULL,
				`access_value` varchar(1) NOT NULL,
				PRIMARY KEY (`access_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
			$this->oDb->Query($sql);
		}
		if( !$this->oDb->CheckTableExists(Config::Get("db.prefix")."users_groups") ){
			$sql = "CREATE TABLE IF NOT EXISTS `".Config::Get("db.prefix")."users_groups` (
				`group_id` int(11) NOT NULL AUTO_INCREMENT,
				`group_name` varchar(25) NOT NULL,
				`group_desc` varchar(250) NOT NULL,
				PRIMARY KEY (`group_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
			$this->oDb->Query($sql);
		}
		if( !$this->oDb->CheckTableExists(Config::Get("db.prefix")."sessions") ){
			$sql = "CREATE TABLE IF NOT EXISTS `".Config::Get("db.prefix")."sessions` (
				`session_id` int(11) NOT NULL AUTO_INCREMENT,
				`session_uid` varchar(250) NOT NULL,
				`session_user` int(11) NOT NULL,
				`session_time` int(11) NOT NULL,
				PRIMARY KEY (`session_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
			$this->oDb->Query($sql);
		}
	}
	public function GetUserBySession($sUserId) {
		$sql = "SELECT user_id FROM ".Config::Get("db.prefix")."users 
				JOIN ".Config::Get("db.prefix")."sessions 
				ON user_id=session_user
				WHERE session_uid=?
				LIMIT 1";

		$aResult=$this->oDb->SelectRow($sql, $sUserId);
		return $aResult['user_id'];
	}
	
	public function GetUserByLogin($sLogin, $sPass=null) {
		if (isset($sPass)){
			$sql = "SELECT user_id FROM ".Config::Get("db.prefix")."users WHERE user_login=? AND user_password=? LIMIT 1";
			$aResult=$this->oDb->SelectRow($sql, $sLogin, md5($sPass));
		}else{
			$sql = "SELECT user_id FROM ".Config::Get("db.prefix")."users WHERE user_login=? LIMIT 1";
			$aResult=$this->oDb->SelectRow($sql, $sLogin);
		}
		return $aResult['user_id'];
	}
	
	public function GetUserById($iId) {
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."users WHERE user_id=?";
		$aRow=$this->oDb->SelectRow($sql, $iId);
		if ($aRow) return Engine::GetEntity('User', $aRow);
		else return null;
	}
	
	public function SetUserSession(ModuleUser_EntityUser $oUser, $sUserId){
		$this->ClearUserSession($sUserId);
		$sql = "INSERT INTO ".Config::Get("db.prefix")."sessions (
				session_user,
				session_uid,
				session_time
			) 
			VALUES(?, ?, ?)
		";
		return $this->oDb->Query($sql, 
			$oUser->getId(),
			$sUserId,
			time()
		);
	}
	
	public function ClearUserSession($sUserId){
		$sql = "DELETE FROM ".Config::Get("db.prefix")."sessions  WHERE session_uid = ?";
		return $this->oDb->Query($sql, $sUserId);
	}
	
	public function Add($oUser){
		$sql = "INSERT INTO ".Config::Get("db.prefix")."users (
				user_login,
				user_password,
				user_group,
				user_email,
				user_name,
				user_regdate, 
				user_active
			) 
			VALUES(?, ?, ?, ?, ?, ?, ?)
		";
		return $this->oDb->Query($sql, 
			$oUser->getLogin(),
			$oUser->getPassword(),
			$oUser->getGroup()->getId(),
			$oUser->getEmail(),
			$oUser->getName(),
			$oUser->getRegdate(),
			$oUser->getActive()
		);
		/*не пихать сюда ничего! это пользователя админки, а не и-нет магазина!!!*/
	}
	public function Update($oUser){
		$sql = "UPDATE ".Config::Get("db.prefix")."users SET 
				user_login=?,
				user_password=?,
				user_group=?,
				user_email=?,
				user_name=?,
				user_active=?
			WHERE user_id=?
		";
		return $this->oDb->Query($sql, 
			$oUser->getLogin(),
			$oUser->getPassword(),
			$oUser->getGroup()->getId(),
			$oUser->getEmail(),
			$oUser->getName(),
			$oUser->getActive(),
			$oUser->getId()
		);
	}
	/*не пихать сюда ничего! это пользователя админки, а не и-нет магазина!!!*/

	public function GetUsers() {
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."users";
		$aResult = array();
		$aRows=$this->oDb->Select($sql);
		foreach ($aRows as $aRow) {
			$aResult[]=Engine::GetEntity('User',$aRow);
		}
		return $aResult;
	}
	public function Activate($iUserId) {
		$sql = "UPDATE ".Config::Get("db.prefix")."users SET user_active=1 WHERE user_id=?";
		if ($this->oDb->Query($sql, $iUserId)) return true;
		else return false;
	}
	public function Deactivate($iUserId) {
		$sql = "UPDATE ".Config::Get("db.prefix")."users SET user_active=0 WHERE user_id=?";
		if ($this->oDb->Query($sql, $iUserId)) return true;
		else return false;
	}
	public function Delete($iUserId) {
		$sql = "DELETE FROM ".Config::Get("db.prefix")."users WHERE user_id=?";
		if ($this->oDb->Query($sql, $iUserId)) return true;
		else return false;
	}

	public function GetGroups() {
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."users_groups";
		$aResult = array();
		$aRows=$this->oDb->Select($sql);
		foreach ($aRows as $aRow) {
			$aResult[]=Engine::GetEntity('User',$aRow, "Group");
		}
		return $aResult;
	}
	public function GetGroupById($iId) {
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."users_groups WHERE group_id=?";
		$aRow=$this->oDb->SelectRow($sql, $iId);
		if ($aRow) return Engine::GetEntity('User', $aRow, "Group");
		else return null;
	}
	public function AddGroup($oGroup){
		$sql = "INSERT INTO ".Config::Get("db.prefix")."users_groups (
				group_name,
				group_desc
			) 
			VALUES(?, ?)
		";
				//group_permissions,
		return $this->oDb->Query($sql, 
			$oGroup->getName(),
			//json_encode( $oGroup->getPermissions() ),
			$oGroup->getDesc()
		);
	}
	public function UpdateGroup($oGroup){
		$sql = "UPDATE ".Config::Get("db.prefix")."users_groups SET 
				group_name=?,
				group_desc=?
			WHERE group_id=?
		";
		return $this->oDb->Query($sql, 
			$oGroup->getName(),
			$oGroup->getDesc(),
			$oGroup->getId()
		);
	}
	public function DeleteGroup($iGroupId) {
		$sql = "DELETE FROM ".Config::Get("db.prefix")."users_groups WHERE group_id=?";
		if ($this->oDb->Query($sql, $iGroupId)) return true;
		else return false;
	}

	public function GetAccessesByGroup( $iId ){
		$sql = "SELECT * FROM ".Config::Get("db.prefix")."users_accesses WHERE access_group = ? ORDER BY access_id";
		$aResult = array();
		$aRows=$this->oDb->Select($sql, $iId);
		foreach ($aRows as $aRow){
			$aResult[]=Engine::GetEntity('User',$aRow, "Access");
		}
		return $aResult;
	}
	public function AddAccesses(ModuleUser_EntityAccess $oAccess){
		$sql = "INSERT INTO ".Config::Get("db.prefix")."users_accesses (
				access_group,
				access_type,
				access_value
			) VALUES(?, ?, ?)
		";
		return $this->oDb->Query($sql, 
			$oAccess->getGroup(),
			$oAccess->getType(),
			$oAccess->getValue()
		);
	}
	public function DeleteAccesses($iId){
		$sql = "DELETE FROM ".Config::Get("db.prefix")."users_accesses WHERE access_group = ?";
		if ($this->oDb->Query($sql, $iId)) return true;
		else return false;
	}
	
}	