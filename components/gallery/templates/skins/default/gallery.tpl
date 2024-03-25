{include file="header.tpl"}
<script>
	jQuery(function(){
		$(".fancybox").fancybox({

			openEffect : 'elastic',
			openSpeed  : 150,

			closeEffect : 'elastic',
			closeSpeed  : 150,

			closeClick : true,

			helpers : {
				
			}
		});
	});
</script>
<section id="com-gallery">
	<div class="row-fluid">
	{foreach from=$aImages item="oImage" name="foo"}
	  <div class="span4">
		<figure> 
			<a class="popup-photo pretty-photo fancybox" data-fancybox-group="gallery" href="{$aTemplate.host}{$oImage->getUrl()}">
					<div class="overlay"></div>
					<img src="{$oImage->getResizedImage(300,165)}">
					<div class="icon-search"></div>
			</a>
			<figcaption>
				{$oImage->getDescription()}
			</figcaption>
		</figure>
	  </div>
      {if (($smarty.foreach.foo.index+1)%3==0)}</div><div class="row-fluid">{/if}
	{/foreach}
	</div>
	<p class="com-news-detail-item-back"><a href="{$aTemplate.node_url}">Вернуться к списку</a></p>
</section>
<!-- Add fancyBox main JS and CSS files -->
<script type="text/javascript" src="/components/admin/templates/default/assets/fancybox/source/jquery.fancybox.js?v=2.1.3"></script>
<link rel="stylesheet" type="text/css" href="/components/admin/templates/default/assets/fancybox/source/jquery.fancybox.css?v=2.1.2" media="screen" />

{include file="footer.tpl"}
