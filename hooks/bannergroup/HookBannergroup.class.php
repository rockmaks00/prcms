<?
//{hook name="bannergroup" template="slider" group=4 width=400 height=300 crop=1 control=1 navigation=1}
class HookBannergroup extends Hook {
	public function Init() {}
	
	public function Bannergroup($aParams) {
		if( !is_numeric($aParams["group"]) ) return false;

		$oGroup = $this->Banner_GetGroupById( $aParams["group"] );
		$aBanners = $this->Banner_GetBannersByGroup( $aParams["group"] );

		if( !$aBanners ) return false;

		usort($aBanners, function($a, $b){ return $a->getPriority() > $b->getPriority() ? -1 : 1; });

		if($aParams["count"]) $aBanners = array_slice($aBanners, 0, $aParams["count"]);

		$sSize = "";
		if( $aParams["width"] ) $sSize .= ' width="'.$aParams["width"].'px" ';
		if( $aParams["height"] ) $sSize .= ' height="'.$aParams["height"].'px" ';
		
		$this->Template_Assign('aBanners', 	$aBanners);
		$this->Template_Assign('sSize', 	$sSize);
		$this->Template_Assign('aParams', 	$aParams);

		if( !$aParams['template'] ) $aParams['template']="default";
		$sPath = "hooks/bannergroup/templates/".$aParams['template']."/";

		if( file_exists($sPath."script.js")  ) $this->Template_AddJs( "/".$sPath."script.js" );
		if( file_exists($sPath."styles.css") ) $this->Template_AddCss( "/".$sPath."styles.css" );
		return $this->Template_Fetch($sPath."template.tpl");
	}
}