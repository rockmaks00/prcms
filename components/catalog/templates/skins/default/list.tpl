{include file="header.tpl"}
	<div class="row-fluid" id="cart">
		{hook name="Cart" component="Cart" template="cart"}
	</div>
	<div class="row-fluid">
		{foreach from=$aGroups item="oGroup"}
			<div class="span4"><a href="{$aTemplate.node_url}{$oGroup->getUrl()}">{$oGroup->getTitle()}</a></div>
		{/foreach}
	</div>
	<h2>Товары</h2>
	<div class="row-fluid">
		{foreach from=$aItems item="oItem"}
			<div class="span4 item">
				{if $oItem->getImage()}<a class="link" href="{$aTemplate.node_url}{$oItem->getUrl()}" title="{$oItem->getTitle()}"><span class="new">Новый</span><img class="product_image" src="{$oItem->getImage()}" alt="{$oItem->getTitle()}"></a>{/if}
				<div class="product_details">
					<h4><a href="{$aTemplate.node_url}{$oItem->getUrl()}" title="{$oItem->getTitle()}">{$oItem->getTitle()}</a></h4>
					<p><span class="label label-danger">{$oItem->getPrice()} руб.</span></p>
				</div> 
				<a class="btn btn-mini btn-info add-to-cart" href="/cart/add/{$oItem->getId()}/" title="Добавить в корзину">Добавить в корзину</a></li>
			</div>
		{/foreach}
	</div>
	<p></p>
	{literal}
	<style>
	.item{
		margin: 0;
		padding: 0 0 30px 0;
		text-align: center;
	}
	.item .price {
		color: #da3b44;
		font-size: 14px;
		font-weight: 700;
		cursor: default;
	}
	.item .link {
		display: block;
		position: relative;
		overflow: hidden;
		height: auto;
		margin: 1px 1px 3px 2px;
		-webkit-backface-visibility: hidden;
		border: 8px solid #ffffff;
		-moz-border-radius: 2px;
		-webkit-border-radius: 2px;
		border-radius: 2px;
		-moz-box-shadow: 0 0 0 1px rgba(0,0,0,0.095) ,0 1.5px 1.5px 0 rgba(0,0,0,0.2),0 2px 1.5px 0 rgba(0,0,0,0.1);
		-webkit-box-shadow: 0 0 0 1px rgba(0,0,0,0.095) ,0 1.5px 1.5px 0 rgba(0,0,0,0.2),0 2px 1.5px 0 rgba(0,0,0,0.1);
		box-shadow: 0 0 0 1px rgba(0,0,0,0.095) ,0 1.5px 1.5px 0 rgba(0,0,0,0.2),0 2px 1.5px 0 rgba(0,0,0,0.1);
	}
	.item .image {
		width: 192px;
		height: auto;
		z-index: 1;
	}
	.item .new{
		position: absolute;
		display: block;
		top: 15px;
		right: -30px;
		overflow: hidden;
		width: 101px;
		background-color: #da3b44;
		background: rgba(218,59,68,0.9);
		padding: 1px 4px;
		font-size: 11px;
		line-height: 17px;
		letter-spacing: 1px;
		color: #ffffff;
		text-transform: uppercase;
		text-align: center;
		z-index: 2;
		-webkit-transform: rotate(45deg);
		-moz-transform: rotate(45deg);
		-o-transform: rotate(45deg);
		-ms-transform: rotate(45deg);
	}
	</style>
	<script language="javascript">
		jQuery(".add-to-cart").click(function(){
			var btn = this;
			if (jQuery(this).attr("href")!="{/literal}/cart/{literal}"){
				jQuery.ajax({
					url: jQuery(this).attr("href"),
					success: function(data){
						jQuery("#cart").html(data);
						jQuery(btn).html('В корзине');
						jQuery(btn).removeClass('btn-info');
						jQuery(btn).addClass('btn-success');
						jQuery(btn).attr('href', '{/literal}/cart/{literal}');
						jQuery(btn).click(function(){return true;});
					}
				});
				return false;
			}
		});
	</script>
	{/literal}
{include file="footer.tpl"}