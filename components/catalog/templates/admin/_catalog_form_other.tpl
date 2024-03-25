<div>
	<div>
		<label class="control-label">Название</label>
			<div class="controls">
				<input name="attrtitle" value="{$aRequest.attrtitle}" type="text" class="span6 m-wrap popovers validate" data-trigger="hover" data-original-title="Заголовок" data-content="Заголовок {$aLang.items_genitive}. Используются любые символы и буквы." data-validate-rule="^.+$" data-validate-content="Пожалуйста, заполните поле.">
			</div>
		<lable class="control-label">Тип</lable>
		<div class="controls" >
			<select name="combotype">
				<option value="1">
					Текстовое поле
				</option>
				<option value="2">
					Многострочное текстовое поле
				</option>
				<option value="3">
					текстовое поле с редактором
				</option>
				<option value="4">
					дата/время
				</option>
			</select>
		</div>
		<label class="control-label">Показывать</label>
			                  <div class="controls">
			                     <div class="basic-toggle-button">
			                        <input name="attractive" type="checkbox" class="toggle"{if $aRequest.attractive} checked="checked"{/if} value="1" />
			                     </div>
			                  </div>
	</div>
	<div>
	</div>
	<table class="table table-striped table-bordered table-advance table-hover" id="table1">
	<thead>
		<tr>
			<th width="10">ID</th>
			<th width="10"><input type="checkbox" class="group-checkable" data-set="#table1 .checkboxes" /></th>
			<th width="10"><i class="icon-reorder"></i></th>
			<th width="80">Название</th>
			<th width="100"><i class="icon-eye-open"></i> Показывать</th>
		</tr>
	</thead>
	<tbody>
		{foreach $aAttrs as $oItem}
		{mpr($aAttrs)}
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
					{$oItem->Title()}
				</td>
				<td>
					{$oItem->getType()}
				</td>
				<td>{if $oItem->getActive()==1}<a href="#" onclick="return ajaxActivate('{$aTemplate.node_url}deactivateItem/{$oItem->getId()}/', this, {$oItem->getId()});"><span class="badge badge-success">Да</span></a>{else}<a href="#" onclick="return ajaxActivate('{$aTemplate.node_url}activateItem/{$oItem->getId()}/', this, {$oItem->getId()});"><span class="badge badge-important">Нет</span></a>{/if}</td>
			</tr>
		{/foreach}
	</tbody>
</table>
</div>