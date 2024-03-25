{include file="header.tpl"}
	{hook name="search" component="search" template="default" node=$oCurrentNode->getId()}
	<div  class="component-search-default">
		{if $iEmpty}<p>Поиск не дал результатов.</p>{/if}
		{if $aResult}
			<ol>
				{foreach from=$aResult item="oResult"}
					<li>
						
						<a href="{$oResult->getUrl()}"><strong>{$oResult->getTitle()}</strong></a>
						<p>{$oResult->getTextCut()}</p>
						<p>количество совпадений: {$oResult->getCount()}</p>
						
					</li>
				{/foreach}
			</ol>
		{/if}
	</div>
{include file="footer.tpl"}