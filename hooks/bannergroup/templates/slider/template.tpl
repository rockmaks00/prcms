<div class="hook-bannergroup-slider">
	<div class="slider">
		{foreach from=$aBanners item=oBanner}
			<div>
				{if $oBanner->getExtension()=="swf"}
					<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" style="cursor:pointer;" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=4,0,2,0" {$sSize}>
						<param name="movie" value="{$oBanner->getImg()}">
						<param name="wmode" value="opaque">
						<param name="quality" value="high">
						<embed src="{$oBanner->getImg()}" wmode="transparent" quality="high" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" {$sSize}>
					</object>
				{else}
					{if $aParams.width || $aParams.height}
						<img src="{getimage($oBanner->getImg(), {$aParams.width}, {$aParams.height}, {$aParams.crop})}" alt="" {$sSize} />
					{else}
						<img src="{$oBanner->getImg()}" alt="" />
					{/if}
				{/if}
			</div>
		{/foreach}
	</div>
	{if $aParams.control}
		<div class="control">
			<a class="arrow left" href="" >prev</a>
			<a class="arrow right" href="">next</a>
		</div>
	{/if}
	{if $aParams.navigation}
		<div class="nav"></div>
	{/if}
</div>