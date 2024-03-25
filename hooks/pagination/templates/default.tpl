{if $iPages>1 }
	<ul class="hook-pagintion-default">
		{for $foo=1 to $iPages}
			<li>
				<a href="{$oCurrentNode->getFullUrl()}page/{$foo}/" {if $foo==$iCurrent}class="current"{/if}>{$foo}</a>
			</li>
		{/for}
	</ul>
{/if}