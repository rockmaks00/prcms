<li>
	<a href="{$item.item->getItemUrl()}">{$item.item->getTitle()}</a>
	{if count($item.childs)}
		<ul>
			{foreach from=$item.childs item=foo}
				{include file="components/sitemap/templates/skins/default/item_li.tpl" item=$foo class="child"}
			{/foreach}
		</ul>
	{/if}
</li>