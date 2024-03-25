<li class="{$class} {if $item.item->getCurrent()}current{/if}">
	<a class="{$class} {if count($item.childs)}childs{/if}" href="{$item.item->getItemUrl()}">{$item.item->getImg()} {$item.item->getTitle()}</a>
	{if count($item.childs)}
		<ul class="childs">
			{foreach from=$item.childs item=foo}
				{include file="hooks/menu/templates/{$sTemplate}/item.tpl" item=$foo class="child"}
			{/foreach}
		</ul>
	{/if}
</li>