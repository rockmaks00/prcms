{include file="header.tpl"}
<section id="com-gallery">
	<div class="row-fluid">
		{foreach from=$aGalleries item="oGallery" name="foo"}
			<h2 class="fileGroupName">{$oGallery->getTitle()}</h2>
		    <table class="norm_borders information-table">
		    	{foreach from=$oGallery->getImages() item="oImage"}
		    		<tr>
		    			{if $oImage->getUrl()}
			    			<td style="min-width: 136px;"><a target="_blank" href="{$oImage->getUrl()}">{$oImage->getTitle()}</a></td>
			    		{/if}
		    			<td>{$oImage->getDescription()}</td>
		    		</tr>
		    	{/foreach}
		    </table>
		{/foreach}
	</div>
</section>
{include file="footer.tpl"}
