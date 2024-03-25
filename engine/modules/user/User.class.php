<?
class ModuleUser extends Module {
	protected $oDb;
	protected $oComponent;
	protected $oUserCurrent=null;
	protected $aAccessValues = array(
		"D" => "Закрыт",
		"R" => "Просмотр",
		"S" => "Наполнение",
		"V" => "Добавление, редактирование",
		"W" => "Полный доступ"
	);

	protected $aAccessTitles = array(
		"nodes" 	 => "Дерево Разделов",
		"content" 	 => "Контент",
		"menu" 		 => "Меню",
		"files" 	 => "Файловый менеджер",
		"components" => "Компоненты",
		"users" 	 => "Пользователи",
		"banners" 	 => "Баннерный барабан",
		"hooks" 	 => "Хуки",
		"templates"	 => "Шаблоны"
	);
	protected $aBlankAccesses = array(
		"nodes" 	 => "D",
		"content" 	 => "D",
		"files" 	 => "D",
		"menu" 		 => "D",
		"components" => "D",
		"users" 	 => "D",
		"banners" 	 => "D",
		"templates"	 => "D"
	);
	public function GetAccessValues(){
		return $this->aAccessValues;
	}
	public function GetAccessTitles(){
		return $this->aAccessTitles;
	}
	public function GetBlankAccesses(){
		return $this->aBlankAccesses;
	}

	public function GetNodesAvailable($sMinAccess="W"){
		$aAccesses = $this->oUserCurrent->getGroup()->getAccesses("nodes");
		foreach ($aAccesses as $oAccess) {
			if( $oAccess->getValue() >= $sMinAccess ) $aNodesAvailable[] = $oAccess->getNode();
		}
		return $aNodesAvailable;
	}
	
	public function Init() {
		$this->oDb=Engine::GetDb(__CLASS__);
		$this->oDb->Install();
		$sUserId=$this->Session_Get('uid');
		if (!$sUserId){
			$sUserId=md5(time().rand());
			$this->Session_Set('uid', $sUserId);
		}
		$this->SetUserCurrent($this->GetUserBySession($sUserId));
	}
	
	public function GetUserBySession($sUserId) {
		if (false === ($data = $this->Cache_Get("user_{$sUserId}"))) {	
			$data=$this->oDb->GetUserBySession($sUserId);
			$this->Cache_Set("user_{$sUserId}", $data, Config::Get("app.cache.expire"));
		}
		$data=$this->GetUserById($data);
		return $data;
	}
	
	public function GetUserByLogin($sLogin, $sPass=null) {
		if (false === ($data = $this->Cache_Get("user_login_{$sLogin}_{$sPass}"))) {	
			$data=$this->oDb->GetUserByLogin($sLogin, $sPass);
			$this->Cache_Set("user_login_{$sLogin}_{$sPass}", $data, Config::Get("app.cache.expire"));
		}
		$data=$this->GetUserById($data);
		return $data;
	}
	
	public function GetUserById($iId) {
		if (false === ($data = $this->Cache_Get("user_{$iId}"))) {
			$data = $this->oDb->GetUserById($iId);
			$this->Cache_Set("user_{$iId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	
	public function GetUserCurrent() {
		return $this->oUserCurrent;
	}
	
	public function SetUserCurrent($oUser) {
		$this->oUserCurrent=$oUser;
	}
	
	public function SetUserSession(ModuleUser_EntityUser $oUser) {
		$sUserId=$this->Session_Get('uid');
		$this->Cache_Delete("user_{$sUserId}");
		$this->oDb->SetUserSession($oUser, $sUserId);
		$this->SetUserCurrent($oUser);
		
		return true;
	}
	
	public function ClearUserSession() {
		$sUserId=$this->Session_Get('uid');
		$this->Cache_Delete("user_{$sUserId}");
		$this->oDb->ClearUserSession($sUserId);
		$this->SetUserCurrent(null);
		return true;
	}
	
	//управление пользователями!
	public function Add(ModuleUser_EntityUser $oUser) {
		$this->Cache_Delete("user_{$oUser->getId()}");
		$this->Cache_Delete("users");
		if ($iId=$this->oDb->Add($oUser)){
			$oUser->setId($iId);
		}
		return $oUser;
	}
	public function Update(ModuleUser_EntityUser $oUser) {
		$this->Cache_Delete("user_{$oUser->getId()}");
		$this->Cache_Delete("users");
		return $this->oDb->Update($oUser);
	}
	public function GetUsers() {
		if (false === ($data = $this->Cache_Get("users"))) {
			$data=$this->oDb->GetUsers();
			$this->Cache_Set("users", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	public function Delete($iUserId) {
		$this->Cache_Delete("user_{$iUserId}");
		$this->Cache_Delete("users");
		return $this->oDb->Delete($iUserId);
	}
	public function Activate($iUserId) {
		$this->Cache_Delete("user_{$iUserId}");
		$this->Cache_Delete("users");
		return $this->oDb->Activate($iUserId);
	}
	public function Deactivate($iUserId) {
		$this->Cache_Delete("user_{$iUserId}");
		$this->Cache_Delete("users");
		return $this->oDb->Deactivate($iUserId);
	}


	public function GetGroups(){
		if (false === ($data = $this->Cache_Get("users_groups"))) {
			$data=$this->oDb->GetGroups();
			$this->Cache_Set("users_groups", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	public function GetGroupById($iId){
		if (false === ($data = $this->Cache_Get("users_group_{$iId}"))) {
			$data = $this->oDb->GetGroupById($iId);
			$this->Cache_Set("users_group_{$iId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	public function AddGroup(ModuleUser_EntityGroup $oGroup) {
		$this->Cache_Delete("users_group_{$oGroup->getId()}");
		$this->Cache_Delete("users_groups");
		if ($iId = $this->oDb->AddGroup($oGroup)){
			$oGroup->setId($iId);
			$this->SetAccesses($oGroup);
		}
		return $oGroup;
	}
	public function UpdateGroup(ModuleUser_EntityGroup $oGroup) {
		$this->Cache_Delete("users_group_{$oGroup->getId()}");
		$this->Cache_Delete("users_groups");
		$this->SetAccesses($oGroup);
		return $this->oDb->UpdateGroup($oGroup); //bool
	}
	public function DeleteGroup($iGroupId){
		$this->Cache_Delete("users_group_{$iGroupId}");
		$this->Cache_Delete("users_groups");
		$this->DeleteAccesses($iGroupId);
		return $this->oDb->DeleteGroup($iGroupId);
	}
	public function GetAccessesByGroup($iGroupId){
		if (false === ($data = $this->Cache_Get("users_accesses_{$iGroupId}"))) {
			$data = $this->oDb->GetAccessesByGroup($iGroupId);
			$this->Cache_Set("users_accesses_{$iGroupId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	public function SetAccesses(ModuleUser_EntityGroup $oGroup) {
		$this->Cache_Delete("users_accesses_{$oGroup->getId()}");
		$this->DeleteAccesses($oGroup->getId());
		foreach( $oGroup->getAccesses() as $oAccess ){
			$oAccess->setGroup($oGroup->getId());
			$this->oDb->AddAccesses($oAccess);
		}
		return true;
	}
	public function DeleteAccesses($iGroupId){
		$this->Cache_Delete("users_accesses_{$iGroupId}");
		return $this->oDb->DeleteAccesses($iGroupId); //bool
	}


}