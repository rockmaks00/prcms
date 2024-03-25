{include file="header.tpl"}
<section class="com-news-list">
	{foreach from=$aNews item="oNews" name="foo"}
		<article class="com-news-list-item">
			{if $oNews->getImage()}<img class="com-news-list-item-image" src="{$oNews->getImage()}" alt="{$oNews->getTitle()|escape:"html"}">{/if}
			<div{if $oNews->getImage()} class="com-news-list-item-desc"{/if}>
				<p class="com-news-list-item-date">{$oNews->getDate()|date_format:"%B %e, %Y"}</p>
				<h2 class="com-news-list-item-title"><a href="{$aTemplate.node_url}{$oNews->getId()}/">{$oNews->getTitle()}</a></h2>
				<p class="com-news-list-item-text">{$oNews->getAnnouncement()}</p>
			</div>
		</article>
	{/foreach}

	{if $iPage && $iCount && iOnPage}
		{hook name="pagination" template="default" page=$iPage count=$iCount onpage=$iOnPage}
	{/if}
</section>
{include file="footer.tpl"}