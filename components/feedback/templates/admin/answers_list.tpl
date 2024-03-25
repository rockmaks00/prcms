{include file="components/admin/templates/default/header.tpl"}
<div class="portlet">
	<div class="portlet-title">
		<h4><i class="icon-cogs"></i>{$sFormTitle}</h4>
	</div>
	<div class="portlet-body">
		<p>	
			<a href="{$aTemplate.node_url}answer_add/{$oResult->getId()}/" class="btn green"><i class="icon-edit"></i> Написать ответ</a>
			<a href="{$aTemplate.node_url}" class="btn">Назад</a>
		</p>
		<div class="tabbable tabbable-custom">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#portlet_tab1" data-toggle="tab"><i class="icon-cog"></i> Основные</a></li>
				<li class=""><a href="#portlet_tab2" data-toggle="tab"><i class="icon-envelope"></i> Сообщение</a></li>
			</ul>
			<!-- BEGIN TAB CONTENT-->
			<div class="tab-content">
				<div class="tab-pane active" id="portlet_tab1">
					<table class="table table-striped table-bordered table-advance table-hover">
						<thead>
							<tr>
								<th width="10">ID</th>
								<th width="100">Редактировать</th>
								<th><i class="icon-user"></i> Автор</th>
								<th><i class="icon-bookmark"></i> Содержимое</th>
								<th width="150"><i class="icon-calendar"></i> Дата</th>
								<th width="100"><i class="icon-eye-open"></i> Показывать</th>
								<th width="100"><i class="icon-external-link"></i> Отправлен</th>
							</tr>
						</thead>
						<tbody>
							{foreach from=$aAnswers item=item}
								<tr>
									<td>{$item->getId()}</td>
									<td>
										<a href="{$aTemplate.node_url}answer_edit/{$item->getId()}/"><i class="icon-pencil"></i></a>
									</td>
									<td>
										{$item->getAuthor()}
									</td>
									<td>
										{$item->getPreview()}
									</td>
									<td>
										{$item->getDatetime()|date_format}
									</td>
									<td>
										<a href="#" class="activation" data-component="{$aTemplate.node_url}answer_activate" data-action="{if $item->getActive()}de{/if}activate" data-id="{$item->getId()}">
											<span class="badge {if $item->getActive()==1}badge-success{else}badge-important{/if}">
												{if $item->getActive()==1}Да{else}Нет{/if}
											</span>
										</a>
									</td>
									<td>
										<span class="badge {if $item->getSent()==1}badge-success{else}badge-important{/if}">
											{if $item->getSent()==1}Да{else}Нет{/if}
										</span>
									</td>
								</tr>
							{foreachelse}
								<tr>
									<td colspan="6"> Ответов нет</td>
								</tr>
							{/foreach}
						</tbody>
					</table>
				</div>
				<div class="tab-pane" id="portlet_tab2">
					{foreach from=$oResult->getValues() item="aValue" name="foo"}
						{$aValue.field->getTitle()}: {$aValue.value->getValue()}<br>
					{/foreach}
				</div>
			</div>
		</div>



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
	</div>
</div>
{include file="components/admin/templates/default/footer.tpl"}