<?
// {hook name="banner" template="default" group=4 width=150 height=100 crop=1}
class HookBanner extends Hook {
	public function Init() {}
	
	public function Banner($aParams) {
		if( !is_numeric($aParams["group"]) ) return false;

		$oGroup = $this->Banner_GetGroupById( $aParams["group"] );
		$aBanners = $this->Banner_GetBannersByGroup( $aParams["group"] );

		if( !$aBanners ) return false;

		$iPrioritySum = 0;
		foreach( $aBanners as $oBanner ){
			$oBanner->setMin( $iPrioritySum+1 );
			$iPrioritySum += $oBanner->getPriority();
			$oBanner->setMax( $iPrioritySum );
		}

		$iRandom = rand(1, $iPrioritySum);

		foreach( $aBanners as $oBanner ){
			if( $oBanner->getMin() <= $iRandom && $oBanner->getMax() >= $iRandom ){
				break;
			}
		}

		$sSize = "";
		if( $aParams["width"] ) $sSize .= ' width="'.$aParams["width"].'px" ';
		if( $aParams["height"] ) $sSize .= ' height="'.$aParams["height"].'px" ';

		if( !in_array($oBanner->getExtension(), array("jpg", "jpeg", "gif", "png", "swf")) ) return false;

		$this->Template_Assign('oBanner', 	$oBanner);
		$this->Template_Assign('sSize', 	$sSize);
		$this->Template_Assign('aParams', 	$aParams);
		if(!$aParams['template']) $aParams['template']="default";
		return $this->Template_Fetch("hooks/banner/templates/".$aParams['template'].".tpl");
	}
}