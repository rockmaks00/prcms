{include file="components/admin/templates/default/header.tpl"}
<div class="portlet">
	<div class="portlet-title">
		<h4 style="display: block;"><i class="icon-cogs"></i><a href="{$aTemplate.node_url}">{$sFormTitle}</a> {if $oParent}&nbsp;<i class="icon-angle-right"></i> <i class="icon-folder-close"></i> {$oParent->getTitle()}{/if}</h4>
	</div>
	<div class="portlet-body">
		{include file="components/catalog/templates/admin/groups_list_portlet.tpl"}
	</div>
</div>
{include file="components/admin/templates/default/footer.tpl"}