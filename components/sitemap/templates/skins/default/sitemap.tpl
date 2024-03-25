{include file="header.tpl"}
<div class="comp-sitemap-default">
{foreach from=$aSitemap item=menu}
	{if count($menu.items) }
		<div class="title">{$menu.menu->getTitle()}</div>
		<ul class="structure">
			{foreach from=$menu.items item=foo}
				{include file="components/sitemap/templates/skins/default/item_li.tpl" item=$foo class="item"}
			{/foreach}
		</ul>
	{/if}
{/foreach}

{include file="footer.tpl"}