<p>	
	<a href="{$aTemplate.node_url}field_add/{$oFeedback->getId()}/" class="btn green"><i class="icon-plus"></i> Добавить поле</a>
	<a href="#" class="btn deleteChosen" data-set="{$aTemplate.node_url}field_deleteall/"><i class="icon-trash"></i> Удалить выбранные</a>
</p>
<table class="table table-striped table-bordered table-advance table-hover" id="table2">
	<thead>
		<tr>
			<th width="10">ID</th>
			<th width="10"><input type="checkbox" class="group-checkable" data-set="#table2 .checkboxes" /></th>
			<th width="10"><i class="icon-reorder"></i></th>
			<th><i class="icon-bookmark"></i> Название</th>
			<th><i class="icon-tasks"></i> Тип поля</th>
			<th width="120"><i class="icon-asterisk"></i> Обязательно</th>
			<th width="100"><i class="icon-eye-open"></i> Показывать</th>
			<th width="100"><i class="icon-sort"></i> Сортировка</th>
		</tr>
	</thead>
	<tbody>
		
		{foreach from=$aFields item=item name=childs}
			<tr>
				<td>{$item->getId()}</td>
				<td><input name="item-checkbox[]" type="checkbox" class="checkboxes item-checkbox" value="{$item->getId()}"></td>
				<td>
					<div class="btn-group no-margin">
						<a class="btn no-padding no-background" href="#" data-toggle="dropdown">
						<i class="icon-reorder"></i>
						</a>
						<ul class="dropdown-menu nodes-dropdown">
							<li><a href="{$aTemplate.node_url}field_edit/{$item->getId()}/"><i class="icon-pencil"></i> Редактировать</a></li>
							<li class="divider"></li>
							<li><a href="#modalDelete" data-toggle="modal" onclick="modalConfirm(null, null, '{$aTemplate.node_url}field_delete/{$item->getId()}/');"><i class="icon-trash"></i> Удалить</a></li>
						</ul>
					</div>
				</td>
				<td>
					<a href="{$aTemplate.node_url}field_edit/{$item->getId()}/">{$item->getTitle()}</a>
				</td>
				<td>
					{$item->getType()}
				</td>
				<td>
					<a href="#" class="activation" data-component="{$aTemplate.node_url}field_required" data-action="{if $item->getRequired()}de{/if}activate" data-id="{$item->getId()}">
						<span class="badge {if $item->getRequired()==1}badge-success{else}badge-important{/if}">
							{if $item->getRequired()==1}Да{else}Нет{/if}
						</span>
					</a>
				</td>
				<td>
					<a href="#" class="activation" data-component="{$aTemplate.node_url}field_activate" data-action="{if $item->getActive()}de{/if}activate" data-id="{$item->getId()}">
						<span class="badge {if $item->getActive()==1}badge-success{else}badge-important{/if}">
							{if $item->getActive()==1}Да{else}Нет{/if}
						</span>
					</a>
				</td>
				<td>
					<a href="#" class="sort-button-ajax arrow-down" data-url="{$aTemplate.node_url}field_sort/{$item->getId()}/down/" ><i class="icon-chevron-down"></i></a>
					<a href="#" class="sort-button-ajax arrow-up"   data-url="{$aTemplate.node_url}field_sort/{$item->getId()}/up/"><i class="icon-chevron-up"></i></a>
				</td>
			</tr>
		{foreachelse}
		
			<tr>
				<td colspan="6">Нет данных</td>
			</tr>
		{/foreach}
	</tbody>
</table>