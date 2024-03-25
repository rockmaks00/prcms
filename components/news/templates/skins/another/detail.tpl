{include file="header.tpl"}
{if $oCurrentNode->getUrl()=="attendance"}
<section class="com-news-detail">
	<article class="com-news-detail-item">
		{if $oNews->getImage()}<img class="com-news-detail-item-image" src="{$oNews->getImage()}" alt="{$oNews->getTitle()|escape:"html"}">{/if}
		<div{if $oNews->getImage()} class="com-news-detail-item-desc-att"{/if}>
			<h1 class="com-news-detail-item-title">{$oNews->getTitle()}</h1>
			<div class="com-news-detail-item-text">{$oNews->getBody()}</div>
			<p class="com-news-detail-item-back"><a href="{$aTemplate.node_url}">Вернуться к списку</a></p>
		</div>
	</article>
	
</section>




{else}
<section class="com-news-detail">
	<article class="com-news-detail-item">
		{if $oNews->getImage()}<img class="com-news-detail-item-image" src="{$oNews->getImage()}" alt="{$oNews->getTitle()|escape:"html"}">{/if}
		<div{if $oNews->getImage()} class="com-news-detail-item-desc"{/if}>
			<p class="com-news-detail-item-date">{$oNews->getDate()|date_format:"%B %e, %Y"}</p>
			<h2 class="com-news-detail-item-title">{$oNews->getTitle()}</h2>
			<div class="com-news-detail-item-text">{$oNews->getBody()}</div>
		</div>
	</article>
	<p class="com-news-detail-item-back"><a href="{$aTemplate.node_url}">Вернуться к списку</a></p>
</section>
{/if}
{include file="footer.tpl"}
