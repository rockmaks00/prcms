{include file="components/admin/templates/default/header.tpl"}
{include file="components/catalog/templates/admin/catalog_parent.tpl"}
<p>	
	<a href="{$aTemplate.node_url}additem/{Router::GetParam(Count(Router::GetParams())-1)}/" class="btn green"><i class="icon-plus"></i>Добавить</a>
	<a href="#" class="btn"><i class="icon-trash"></i> Удалить выбранные</a>
</p>
<table class="table table-striped table-bordered table-advance table-hover" id="table1">
	<thead>
		<tr>
			<th width="10">ID</th>
			<th width="10"><input type="checkbox" class="group-checkable" data-set="#table1 .checkboxes" /></th>
			<th width="10"><i class="icon-reorder"></i></th>
			<th width="80">Название</th>
			<th width="80">Цена</th>
			<th width="80">Имеется</th>
			<th width="100"><i class="icon-eye-open"></i> Показывать</th>
		</tr>
	</thead>
	<tbody>
		{foreach from=$aItems item=oItem name=childs}
			<tr>
				<td>{$oItem->getId()}</td>
				<td><input name="item-checkbox[]" type="checkbox" class="checkboxes item-checkbox" value="{$oItem->getId()}"></td>
				<td>
					<div class="btn-group no-margin">
						<a class="btn no-padding no-background" href="#" data-toggle="dropdown">
						<i class="icon-reorder"></i>
						</a>
						<ul class="dropdown-menu nodes-dropdown">
							<li><a href="{$aTemplate.node_url}edititem/{$oItem->getId()}/"><i class="icon-pencil"></i> Редактировать</a></li>
							<li class="divider"></li>
							<li><a href="#modalDelete" data-toggle="modal" onclick="modalConfirm(null, null, '{$aTemplate.node_url}deleteitem/{$oItem->getId()}/');"><i class="icon-trash"></i> Удалить</a></li>
						</ul>
					</div>
				</td>
				<td>
					<a href="{$aTemplate.node_url}edititem/{$oItem->getId()}/">{$oItem->getTitle()}</a>
				</td>
				<td>
					{$oItem->getCost()}
				</td>
				<td>
					{$oItem->getCount()}
				</td>
				<td>{if $oItem->getActive()==1}<a href="#" onclick="return ajaxActivate('{$aTemplate.node_url}deactivateItem/{$oItem->getId()}/', this, {$oItem->getId()});"><span class="badge badge-success">Да</span></a>{else}<a href="#" onclick="return ajaxActivate('{$aTemplate.node_url}activateItem/{$oItem->getId()}/', this, {$oItem->getId()});"><span class="badge badge-important">Нет</span></a>{/if}</td>
			</tr>
		{/foreach}
	</tbody>
</table>
<div id="modalDelete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="modalDeleteLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
		<h3 id="modalDeleteLabel">Подтверждение удаления</h3>
	</div>
	<div class="modal-body">
		<p>Вы действительно хотите удалить этот элемент?</p>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Нет</button>
		<button data-dismiss="modal" class="btn blue submit">Да</button>
	</div>
</div>
{include file="components/admin/templates/default/footer.tpl"}