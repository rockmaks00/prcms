{if count($aCrumbs) }
	<ul class="hook-breadcrumbs-default">
		{foreach from=$aCrumbs item=aCrumb}
			<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
				<a href="{$aCrumb.href}" itemprop="url">
					<span itemprop="title">
						{$aCrumb.title}
					</span>
				</a>
			</li>
		{/foreach}
	</ul>
{/if}