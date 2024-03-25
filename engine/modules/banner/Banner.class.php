<?
class ModuleBanner extends Module {
	protected $oDb;
	protected $oComponent;
	public function Init() {
		$this->oDb=Engine::GetDb(__CLASS__);
		$this->oDb->Install();
	}
	public function Add(ModuleBanner_EntityBanner $oBanner) {
		if ($iId=$this->oDb->Add($oBanner)){
			$oBanner->setId($iId);
		}
		return $oBanner;
	}
	public function Update(ModuleBanner_EntityBanner $oBanner) {
		$this->Cache_Delete("banner_{$oBanner->getId()}");
		return $this->oDb->Update($oBanner);
	}
	public function Delete($iId) {
		$oBanner = $this->GetBannerById($iId);
		$this->Image_Delete($oBanner->getImg());
		$this->Cache_Delete("banner_{$iId}");
		return $this->oDb->Delete($iId);
	}
	public function GetBannersByGroup($iGroupId) {
		$aIds = $this->oDb->GetBannersByGroup($iGroupId);
		$aResult = array();
		foreach ($aIds as $iId) {
			$aResult[] = $this->GetBannerById($iId);
		}
		return $aResult;
	}
	public function GetBannerById($iId) {
		if (false === ($data = $this->Cache_Get("banner_{$iId}"))) {
			$data=$this->oDb->GetBannerById($iId);
			$this->Cache_Set("banner_{$iId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	public function Activate($iId) {
		$this->Cache_Delete("banner_{$iId}");
		return $this->oDb->Activate($iId);
	}
	
	public function Deactivate($iId) {
		$this->Cache_Delete("banner_{$iId}");
		return $this->oDb->Deactivate($iId);
	}
	

/*groups*/
	public function AddGroup(ModuleBanner_EntityGroup $oGroup) {
		$this->Cache_Delete("banner_group_{$oGroup->getId()}");
		$this->Cache_Delete("banner_groups");
		if ($iId=$this->oDb->AddGroup($oGroup)){
			$oGroup->setId($iId);
		}
		return $oGroup;
	}
	public function UpdateGroup(ModuleBanner_EntityGroup $oGroup) {
		$this->Cache_Delete("banner_group_{$oGroup->getId()}");
		$this->Cache_Delete("banner_groups");
		return $this->oDb->UpdateGroup($oGroup);
	}
	public function DeleteGroup($iGroupId) {
		$this->Cache_Delete("banner_group_{$iGroupId}");
		$this->Cache_Delete("banner_groups");

		$aBannerIds = $this->oDb->GetBannersByGroup($iGroupId);
		foreach($aBannerIds as $iBannerId){
			$this->Delete($iBannerId);
		}
		return $this->oDb->DeleteGroup($iGroupId);
	}
	public function GetGroupById($iGroupId) {
		if (false === ($data = $this->Cache_Get("banner_group_{$iGroupId}"))) {
			$data=$this->oDb->GetGroupById($iGroupId);
			$this->Cache_Set("banner_group_{$iGroupId}", $data, Config::Get("app.cache.expire"));
		}
		return $data;
	}
	public function GetGroups() {
		if (false === ($aResult = $this->Cache_Get("banner_groups"))) {
			$aIds=$this->oDb->GetGroups();
			$aResult = array();
			foreach($aIds as $iId) {
				$aResult[] = $this->oDb->GetGroupById($iId);
			}
			$this->Cache_Set("banner_groups", $aResult, Config::Get("app.cache.expire"));
		}
		return $aResult;
	}
}