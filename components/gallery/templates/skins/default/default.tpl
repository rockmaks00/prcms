{include file="header.tpl"}
<section id="com-gallery">
	<div class="row-fluid">
	{foreach from=$aGalleries item="oGallery" name="foo"}
	  <div class="span4">
		<figure>
			<a class="popup-photo align-left pretty-photo" href="{$aTemplate.node_url}{$oGallery->getId()}/">
				<div class="overlay"></div>
				<img src="{$oGallery->getResizedImage(300, 165)}">
				<div class="icon-search"></div>
			</a>
			<figcaption>{$oGallery->getTitle()}</figcaption>
		</figure>
	  </div>
      {if (($smarty.foreach.foo.index+1)%3==0)}</div><div class="row-fluid">{/if}
	{/foreach}
	</div>
</section>
{include file="footer.tpl"}
