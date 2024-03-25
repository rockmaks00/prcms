<p>	
	<a href="{$aTemplate.node_url}addparent/{Router::GetParam(Count(Router::GetParams())-1)}/" class="btn green"><i class="icon-plus"></i> Добавить {$aLang.item}</a>
	<a href="#" class="btn"><i class="icon-trash"></i> Удалить выбранные</a>
</p>
<table class="table table-striped table-bordered table-advance table-hover" id="table1">
	<thead>
		<tr>
			<th width="10">ID</th>
			<th width="10"><input type="checkbox" class="group-checkable" data-set="#table1 .checkboxes" /></th>
			<th width="10"><i class="icon-reorder"></i></th>
			<th width="80"></th>
			<th><i class="icon-bookmark"></i> Название</th>
			<th width="100"><i class="icon-eye-open"></i> Показывать</th>
		</tr>
	</thead>
	<tbody>
		{foreach from=$aCatalogs item=item name=childs}
			<tr>
				<td>{$item->getId()}</td>
				<td><input name="item-checkbox[]" type="checkbox" class="checkboxes item-checkbox" value="{$item->getId()}"></td>
				<td>
					<div class="btn-group no-margin">
						<a class="btn no-padding no-background" href="#" data-toggle="dropdown">
						<i class="icon-reorder"></i>
						</a>
						<ul class="dropdown-menu nodes-dropdown">
							<li><a href="{$aTemplate.node_url}edit/{$item->getId()}/"><i class="icon-pencil"></i> Редактировать</a></li>
							<li class="divider"></li>
							<li><a href="#modalDelete" data-toggle="modal" onclick="modalConfirm(null, null, '{$aTemplate.node_url}delete/{$item->getId()}/');"><i class="icon-trash"></i> Удалить</a></li>
						</ul>
					</div>
				</td>
				<td>
					<a class="btn blue mini" href="{$aTemplate.node.url}{$item->getId()}/">Товары</a>
				</td>
				<td>
					<a href="{$aTemplate.node_url}edit/{$item->getId()}/">{$item->getTitle()}</a>
				</td>
				<td>{if $item->getActive()==1}<a href="#" onclick="return ajaxActivate('{$aTemplate.node_url}deactivate/{$item->getId()}/', this, {$item->getId()});"><span class="badge badge-success">Да</span></a>{else}<a href="#" onclick="return ajaxActivate('{$aTemplate.node_url}activate/{$item->getId()}/', this, {$item->getId()});"><span class="badge badge-important">Нет</span></a>{/if}</td>
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