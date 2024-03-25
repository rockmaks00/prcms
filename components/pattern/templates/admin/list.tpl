{include file="components/admin/templates/default/header.tpl"}

<div class="row-fluid">
	<div class="span12">
	<!-- BEGIN SAMPLE FORM PORTLET-->   
		<div class="portlet box blue">
			<div class="portlet-title">
				<h4><i class="icon-reorder"></i>{$sFormTitle}</h4>
				<div class="tools">
					<a href="javascript:;" class="collapse"></a>
					<a href="#portlet-config" data-toggle="modal" class="config"></a>
					<a href="javascript:;" class="reload"></a>
					<a href="javascript:;" class="remove"></a>
				</div>
			</div>
			<div class="portlet-body form">
			<!-- BEGIN FORM-->
				<form action="{$aTemplate.node_url}{$sFormAction}/" class="form-horizontal validate" method="post" enctype="multipart/form-data">
					<input type="hidden" name="id" value="{$aRequest.id}">
					<input type="hidden" name="apply" value="0">
					<div class="tabbable tabbable-custom">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#portlet_tab1" data-toggle="tab"><i class="icon-map-marker"></i> Области на карте</a></li>
							<li><a href="#portlet_tab2" data-toggle="tab"><i class="icon-cog"></i> Основное</a></li>
						</ul>
						<!-- BEGIN TAB CONTENT-->
						<div class="tab-content">

							<!-- BEGIN PORTLET TAB1-->
							<div class="tab-pane active" id="portlet_tab1">
								<p>	
									<a href="{$aTemplate.node_url}add/" class="btn green"><i class="icon-plus"></i> Добавить область</a>
									<a href="#" class="btn" data-set="{$aTemplate.node_url}deleteall/" id="deleteChosen"><i class="icon-trash"></i> Удалить выбранные</a>
								</p>
								<table id="table1" class="table table-striped table-bordered table-advance table-hover">
									<thead>
										<tr>
											<th width="10">ID</th>
											<th width="10"><input type="checkbox" class="group-checkable" data-set="#table1 .checkboxes"></th>
											<th width="10"><i class="icon-reorder"></i></th>
											<th><i class="icon-bookmark"></i> Заголовок</th>
											<th><i class="icon-info-sign"></i> Описание</th>
										</tr>
									</thead>
									<tbody>
										{foreach from=$aPatterns item=oPattern}
											<tr>
												
												<td>{$oPattern->getId()}</td>
												<td><input name="item-checkbox[]" type="checkbox" class="checkboxes item-checkbox" value="{$oPattern->getId()}"></td>
												<td>
													<div class="btn-group no-margin">
														<a class="btn no-padding no-background" href="#" data-toggle="dropdown">
														<i class="icon-reorder"></i>
														</a>
														<ul class="dropdown-menu nodes-dropdown">
															<li><a href="{$aTemplate.node_url}edit/{$oPattern->getId()}/"><i class="icon-pencil"></i> Редактировать</a></li>
															<li class="divider"></li>
															<li><a href="#modalDelete" data-toggle="modal" onclick="modalConfirm('Подтверждение удаления', 'Вы действительно хотите удалить область?', '{$aTemplate.node_url}delete/{$oPattern->getId()}');"><i class="icon-trash"></i> Удалить</a></li>
														</ul>
													</div>
												</td>
												<td>
													<a href="{$aTemplate.node_url}edit/{$oPattern->getId()}/">{$oPattern->getTitle()}</a>
												</td>
												<td>{$oPattern->getDesc()}</td>
											</tr>
										{/foreach}
									</tbody>
								</table>

							</div>
							<!-- END PORTLET TAB1-->

							<!-- BEGIN PORTLET TAB2-->
							<div class="tab-pane" id="portlet_tab2">
								{include file="components/admin/templates/default/form/editor.tpl" title="Описание" name="desc" value=$oMap->getDesc() }
								{include file="components/admin/templates/default/form/image.tpl" title="Изображение" name="image" image=$oMap->getImg() remove="{$aTemplate.node_url}removeimage/{$oMap->getId()}/" }
							</div>
							<!-- END PORTLET TAB2-->



							<div class="form-actions">
								<button type="submit" class="btn blue"><i class="icon-ok"></i> Сохранить</button>
								<button type="button" class="btn apply">Применить</button>
								<button type="button" class="btn" onclick="document.location='{$aTemplate.host}admin/{$sAction}/';">Отмена</button>
							</div>
						</div>
						<!-- END TAB CONTENT-->
					</div>   
				</form>
				<!-- END FORM-->       
			</div>
		</div>
		<!-- END SAMPLE FORM PORTLET-->
	</div>
</div>
<div id="modalDelete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="modalDeleteLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
		<h3 id="modalDeleteLabel">Подтверждение удаления</h3>
	</div>
	<div class="modal-body">
		<p></p>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Нет</button>
		<button data-dismiss="modal" class="btn blue submit">Да</button>
	</div>
</div>
{include file="components/admin/templates/default/footer.tpl"}