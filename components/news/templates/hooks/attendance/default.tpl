<h2><a href="/attendance/">Участники выставки</a></h2>
<a class="slider-left" href="#"><img src="{$aTemplate.path}img/arr-left.png"></a>
<div class="slider">
	<div class="slider-runner">
		<ul>
			{foreach from=$aNews item="oNews" name="foo"}
			<li><a href="/attendance/{$oNews->getId()}/"><img src="{$oNews->getImage()}"></a></li>
			{/foreach}
		</ul>
	</div>
</div>
<a class="slider-right" href="#"><img src="{$aTemplate.path}img/arr-right.png"></a>
{literal}
<script language="javascript">
	var animation=false;
	jQuery(".slider-right").click(function(){	
		var w=0;
		jQuery(this).parent().find(".slider-runner li").each(function(){
			w+=30+jQuery(this).width();
		});
		jQuery(this).parent().find(".slider-runner").width(w);
		if (animation==false && (w+parseInt(jQuery(this).parent().find(".slider-runner").css("left")))>jQuery(this).parent().find(".slider").width()){
			animation=true;
			jQuery(this).parent().find(".slider-runner").animate({left: "-=216px"}, function(){animation=false;});
		}
		return false;
	});
	jQuery(".slider-left").click(function(){	
		var w=0;
		jQuery(this).parent().find(".slider-runner li").each(function(){
			w+=30+jQuery(this).width();
		});
		jQuery(this).parent().find(".slider-runner").width(w);
		if (animation==false && parseInt(jQuery(this).parent().find(".slider-runner").css("left"))<0){
			animation=true;
			jQuery(this).parent().find(".slider-runner").animate({left: "+=216px"}, function(){animation=false;});
			
		}
		return false;
	});
</script>
{/literal}