{include file="header.tpl"}
{assign var=iNodeId value=$oCurrentNode->getId()}
<div class="com_pattern_default">
	{$oMap->getDesc()}
	<div class="map">
		<img class="map" src="{$oMap->getImg()}" alt=""/>
		<canvas id="canv" {$oMap->getImgSizeAttr()}></canvas>
		<map id="map_node{$iNodeId}" name="map_node{$iNodeId}">
			{foreach from=$aPatterns item="oPattern"}
				<area coords="{$oPattern->getCoords()}" shape="poly" id="area{$oPattern->getId()}_node{$iNodeId}" data-desc="{$oPattern->getDesc()}" data-title="{$oPattern->getTitle()}" href="#"/>
			{/foreach }
		</map>
		<img class="hover" src="/components/{$oCurrentNode->getComponentObject()->getName()}/templates/skins/1x1.gif" alt="" style="height:{$oMap->getImgHeight()}px; width:{$oMap->getImgWidth()}px;" usemap="#map_node{$iNodeId}" />
	</div>
</div>
<script>
	var coords = [];
	{foreach from=$aPatterns item="oPattern"}
		coords.push( [{$oPattern->getCoords()}] );
	{/foreach}
</script>
{include file="footer.tpl"}
