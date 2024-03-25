{if count($aItems) }
	<ul class="hook-menu-default">
		{foreach from=$aItems item=foo}
			{include file="hooks/menu/templates/{$sTemplate}/item.tpl" item=$foo class="item"}
		{/foreach}
	</ul>
{/if}