<p>	
	<a href="{$aTemplate.node_url}group_add/{if $oParent}group/{$oParent->getId()}/{/if}" class="btn green"><i class="icon-plus"></i> Добавить группу</a>
	<a href="{$aTemplate.node_url}item_add/{if $oParent}group/{$oParent->getId()}/{/if}" class="btn blue"><i class="icon-plus"></i> Добавить элемент</a>
	{* <a href="#" class="btn"><i class="icon-trash"></i> Удалить выбранные</a> *}
	<a href="{$aTemplate.node_url}attributes/" class="btn"><i class="icon-tasks"></i> Свойства</a>
	{if $isIm}
	<a href="{$aTemplate.node_url}csv/" class="btn purple"><i class="icon-hdd"></i> CSV</a>
	{/if}
</p>
<table class="table table-striped table-bordered table-advance table-hover" id="table1">
	<thead>
		<tr>
			<th width="10">ID</th>
			<th width="10"><input type="checkbox" class="group-checkable" data-set="#table1 .checkboxes" /></th>
			<th width="10"><i class="icon-reorder"></i></th>
			<th><i class="icon-bookmark"></i> Название</th>
			
			<th width="100"><i class="icon-eye-open"></i> Показывать</th>
			<th width="100"><i class="icon-tasks"></i> Сортировка</th>
		</tr>
	</thead>
	<tbody>
		{if $oParent}
			<tr>
				<td colspan="3"></td>
				<td colspan="3"><a href="{$aTemplate.node_url}group/{$oParent->getParent()}/"><i class="icon-share-alt"></i> Вверх</a></td>
			</tr>
			
		{/if}
		{foreach from=$aGroups item=item name=childs}
			<tr>
				<td>{$item->getId()}</td>
				<td><input name="item-checkbox[]" type="checkbox" class="checkboxes item-checkbox" value="{$item->getId()}"></td>
				<td>
					<div class="btn-group no-margin">
						<a class="btn no-padding no-background" href="#" data-toggle="dropdown">
						<i class="icon-reorder"></i>
						</a>
						<ul class="dropdown-menu nodes-dropdown">
							<li><a href="{$aTemplate.node_url}group_edit/{$item->getId()}/"><i class="icon-pencil"></i> Редактировать</a></li>
							<li class="divider"></li>
							<li><a href="#modalDelete" data-toggle="modal" onclick="modalConfirm(null, null, '{$aTemplate.node_url}group_delete/{$item->getId()}/');"><i class="icon-trash"></i> Удалить</a></li>
						</ul>
					</div>
				</td>
				<td>
					<a href="{$aTemplate.node_url}group/{$item->getId()}/"><i class="icon-folder-close"></i> {$item->getTitle()}</a>
				</td>
				<td><center>{if $item->getActive()==1}<a href="#" onclick="return ajaxActivate('{$aTemplate.node_url}group_deactivate/{$item->getId()}/', this, {$item->getId()});"><span class="badge badge-success">Да</span></a>{else}<a href="#" onclick="return ajaxActivate('{$aTemplate.node_url}group_activate/{$item->getId()}/', this, {$item->getId()});"><span class="badge badge-important">Нет</span></a>{/if}</center></td>
				<td><center><span class="badge">{$item->getSort()}</span></center></td>
			</tr>
		{/foreach}
		{foreach from=$aItems item=item name=childs}
			<tr>
				<td>{$item->getId()}</td>
				<td><input name="item-checkbox[]" type="checkbox" class="checkboxes item-checkbox" value="{$item->getId()}"></td>
				<td>
					<div class="btn-group no-margin">
						<a class="btn no-padding no-background" href="#" data-toggle="dropdown">
						<i class="icon-reorder"></i>
						</a>
						<ul class="dropdown-menu nodes-dropdown">
							<li><a href="{$aTemplate.node_url}item_edit/{$item->getId()}/"><i class="icon-pencil"></i> Редактировать</a></li>
							<li class="divider"></li>
							<li><a href="#modalDelete" data-toggle="modal" onclick="modalConfirm(null, null, '{$aTemplate.node_url}item_delete/{$item->getId()}/');"><i class="icon-trash"></i> Удалить</a></li>
						</ul>
					</div>
				</td>
				<td>
					<a href="{$aTemplate.node_url}item_edit/{$item->getId()}/"><i class="icon-file"></i> {$item->getTitle()}</a><div class="pull-right">{if $item->getPrice()} <span class="badge badge-success">{$item->getPrice()} руб.</span>{/if}{if $item->getCount()} <span class="badge badge-info">{$item->getCount()} шт.</span>{/if}</div>
				</td>
				<td><center>{if $item->getActive()==1}<a href="#" onclick="return ajaxActivate('{$aTemplate.node_url}item_deactivate/{$item->getId()}/', this, {$item->getId()});"><span class="badge badge-success">Да</span></a>{else}<a href="#" onclick="return ajaxActivate('{$aTemplate.node_url}item_activate/{$item->getId()}/', this, {$item->getId()});"><span class="badge badge-important">Нет</span></a>{/if}</center></td>
				<td><center><span class="badge">{$item->getSort()}</span></center></td>
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