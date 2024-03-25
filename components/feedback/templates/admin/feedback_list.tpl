{include file="components/admin/templates/default/header.tpl"}
	{*include file="components/feedback/templates/admin/feedback_list_portlet.tpl"*}
		<div class="row-fluid">
			<div class="span12">
				<!-- BEGIN SAMPLE FORM PORTLET-->   
				<div class="portlet box blue">
					<div class="portlet-title">
						<h4><i class="icon-reorder"></i>{$sFormTitle}</h4>
					</div>
					<div class="portlet-body form">
						<!-- BEGIN FORM-->
						<form action="{$aTemplate.node_url}{$sFormAction}/" class="form-horizontal validate" method="post" enctype="multipart/form-data">
							<input type="hidden" name="id" value="{$oFeedback->getId()}">
							<input type="hidden" name="apply" value="0">
							<input type="hidden" name="sub" value="1">
							<div class="tabbable tabbable-custom">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#portlet_tab1" data-toggle="tab"><i class="icon-inbox"></i> Результаты</a></li>
									<li><a href="#portlet_tab2" data-toggle="tab"><i class="icon-tasks"></i> Поля формы</a></li>
									<li><a href="#portlet_tab3" data-toggle="tab"><i class="icon-cog"></i> Настройки</a></li>
									<li><a href="#portlet_tab4" data-toggle="tab"><i class="icon-cog"></i> Дополнительно</a></li>
								</ul>
								<!-- BEGIN TAB CONTENT-->
								<div class="tab-content">
									<!-- BEGIN PORTLET TAB1-->
									<div class="tab-pane active" id="portlet_tab1">
										<p>	
											{*<a href="{$aTemplate.node_url}result_add/" class="btn green"><i class="icon-plus"></i> Добавить {$aLang.item}</a>*}
											<a href="#" class="btn deleteChosen" data-set="{$aTemplate.node_url}result_deleteall/"><i class="icon-trash"></i> Удалить выбранные</a>
										</p>
										<!-- BEGIN ROW FLUID-->
										<div class="row-fluid">
											<div class="span12">
												<table class="table table-striped table-bordered table-advance table-hover" id="table1">
													<thead>
														<tr>
															<th width="10">ID</th>
															<th width="10"><input type="checkbox" class="group-checkable" data-set="#table1 .checkboxes" /></th>
															<th width="10"><i class="icon-reorder"></i></th>
															<th>{if is_object($aFields[0]) }{$aFields[0]->getTitle()}{/if}</th>
															<th width="150"><i class="icon-calendar"></i> Дата</th>
															<th width="150"><i class="icon-envelope"></i> Ответов</th>
															<th width="100"><i class="icon-eye-open"></i> Показывать</th>
														</tr>
													</thead>
													<tbody>
														{foreach from=$aResults item=item name=childs}
															<tr>
																<td>{$item->getId()}</td>
																<td><input name="item-checkbox[]" type="checkbox" class="checkboxes item-checkbox" value="{$item->getId()}"></td>
																<td>
																	<div class="btn-group no-margin">
																		<a class="btn no-padding no-background" href="#" data-toggle="dropdown">
																		<i class="icon-reorder"></i>
																		</a>
																		<ul class="dropdown-menu nodes-dropdown">
																			<li><a href="{$aTemplate.node_url}result_edit/{$item->getId()}/"><i class="icon-pencil"></i> Редактировать</a></li>
																			<li><a href="{$aTemplate.node_url}answers/{$item->getId()}/"><i class="icon-envelope"></i> Ответы</a></li>
																			<li><a href="{$aTemplate.node_url}answer_add/{$item->getId()}/"><i class="icon-edit"></i> Написать ответ</a></li>
																			<li class="divider"></li>
																			<li><a href="#modalDelete" data-toggle="modal" onclick="modalConfirm(null, null, '{$aTemplate.node_url}result_delete/{$item->getId()}/');"><i class="icon-trash"></i> Удалить</a></li>
																		</ul>
																	</div>
																</td>
																<td>
																	{foreach from=$item->getValues() item="aValue" name="foo"}
																		{$aValue.field->getTitle()}: {$aValue.value->getValue()}<br>
																	{/foreach}
																</td>
																
																<td>
																	{$item->getDatetime()|date_format}
																</td>
																<td>
																	{$item->getAnswers()|count}
																</td>
																<td>
																	<a href="#" class="activation" data-component="{$aTemplate.node_url}result_activate" data-action="{if $item->getActive()}de{/if}activate" data-id="{$item->getId()}">
																		<span class="badge {if $item->getActive()==1}badge-success{else}badge-important{/if}">
																			{if $item->getActive()==1}Да{else}Нет{/if}
																		</span>
																	</a>
																</td>
															</tr>
														{foreachelse}
															<tr>
													  			<td colspan="6">Нет данных</td>
															</tr>
														{/foreach}
													</tbody>
												</table>
											</div>
											{*<div class="span4">
												<div class="well">
													{foreach from=$aFields item=item name=childs}
														<label class="checkbox">
															<input name="" type="checkbox" class="checkboxes item-checkbox" value="{$item->getId()}"> {$item->getTitle()}
														</label>
													{/foreach}
												</div>
											</div>*}
											
										</div>
										<!-- END ROW FLUID-->
									</div>
									<!-- END PORTLET TAB1-->
									<!-- BEGIN PORTLET TAB2-->
									<div class="tab-pane" id="portlet_tab2">
										<div class="portlet">
											<div class="portlet-body">
												{include file="components/feedback/templates/admin/feedback_fields_list_portlet.tpl"}
											</div>
										</div>
									</div>
									<!-- END PORTLET TAB2-->
									<!-- BEGIN PORTLET TAB3-->
									<div class="tab-pane" id="portlet_tab3">
										<div class="portlet-body form">
											
											{include file="components/admin/templates/default/form/text.tpl" title="Почта для уведомлений" name="mails" value=$oFeedback->getMails() popover=true hint="Электронна почта администратора сайта. Можно указать несколько через запятую"}

											{include file="components/admin/templates/default/form/editor.tpl" title="Текст описания на странице" name="text" value=$oFeedback->getText()}

										</div>
									</div>
									<!-- END PORTLET TAB3-->
									<!-- BEGIN PORTLET TAB4-->
									<div class="tab-pane" id="portlet_tab4">
										<div id="params-portlet" data-node="{$oContentNode->getId()}" data-component="{$oContentNode->getComponent()}">Загрузка параметров...</div>
									</div>
									<!-- END PORTLET TAB4-->
									<div class="form-actions">
										<button type="submit" class="btn blue"><i class="icon-ok"></i> Сохранить</button>
										<button type="button" class="btn apply">Применить</button>
										<a href="{$aTemplate.host}admin/{$sAction}/" class="btn">Отмена</a>
									</div>
								</div>
								<!-- END TAB CONTENT-->
							</div>   
						</form>
						<!-- END FORM-->  
					</div>
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
{include file="components/admin/templates/default/footer.tpl"}