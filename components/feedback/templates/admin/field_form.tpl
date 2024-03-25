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
					<input type="hidden" name="id" value="{$oField->getId()}">
					<input type="hidden" name="apply" value="0">
					<input type="hidden" name="parent" value="{$oField->getFeedback()}">
					<input type="hidden" name="required" value="0">
					<input type="hidden" name="active" value="0">
					<div class="tabbable tabbable-custom">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#portlet_tab1" data-toggle="tab"><i class="icon-cog"></i> Основные</a></li>
						</ul>
						<!-- BEGIN TAB CONTENT-->
						<div class="tab-content">
							<!-- BEGIN PORTLET TAB1-->
							<div class="tab-pane active" id="portlet_tab1">
								{include file="components/admin/templates/default/form/text.tpl" title="Заголовок" name="title" value=$oField->getTitle() popover=true hint="Заголовок поля. Используются любые символы и буквы." validate=true validate_rule="^.+$"}

								{include file="components/admin/templates/default/form/text.tpl" title="Название поля" name="name" value=$oField->getName() popover=true hint="Используются только английские буквы и '_'." validate=true validate_rule="^\w+$"}


								{include file="components/admin/templates/default/form/select.start.tpl" title="Тип поля" name="type"}
									<option value="text"	{if $oField->getType()=="text"} 	selected="selected"{/if}>Строка (text)</option>
									<option value="textarea"{if $oField->getType()=="textarea"} selected="selected"{/if}>Текст (textarea)</option>
									<option value="select"	{if $oField->getType()=="select"} 	selected="selected"{/if}>Выпадающий список (select)</option>
									<option value="checkbox"{if $oField->getType()=="checkbox"} selected="selected"{/if}>Флажок (checkbox)</option>
									<option value="radio"	{if $oField->getType()=="radio"} 	selected="selected"{/if}>Переключатель (radio)</option>
									<option value="label"	{if $oField->getType()=="label"} 	selected="selected"{/if}>Заголовок (label)</option>
									<option value="mail"	{if $oField->getType()=="mail"} 	selected="selected"{/if}>Почта (mail)</option>
									<option value="phone"	{if $oField->getType()=="phone"} 	selected="selected"{/if}>Телефон (phone)</option>
									<option value="file"	{if $oField->getType()=="file"} 	selected="selected"{/if}>Файл (file)</option>
								{include file="components/admin/templates/default/form/select.end.tpl"}

								{include file="components/admin/templates/default/form/checkbox.tpl" title="Обязательно" name="required" value=$oField->getRequired()}

								{include file="components/admin/templates/default/form/textarea.tpl" title="Значение по умолчанию" name="value" value=$oField->getValue() popover=true hint="Для списка введите значения через точку с запятой."}

								{include file="components/admin/templates/default/form/text.tpl" title="Сортировка" name="sort" value=$oField->getSort() popover=true hint="Число, по которому сортируется порядок элементов. Чем меньше число, тем выше элемент в списке."}

								{include file="components/admin/templates/default/form/checkbox.tpl" title="Показывать" name="active" value=$oField->getActive()}


							</div>
						</div>
							<!-- END PORTLET TAB1-->
					</div>
					<!-- END TAB CONTENT-->
					<div class="form-actions">
						<button type="submit" class="btn blue"><i class="icon-ok"></i> Сохранить</button>
						<button type="button" class="btn apply">Применить</button>
						<button type="button" class="btn" onclick="document.location='{$aTemplate.node_url}#portlet_tab2';">Отмена</button>
					</div>
				</form>
				<!-- END FORM-->       
			</div>
		</div>
		<!-- END SAMPLE FORM PORTLET-->
	</div>
</div>
{include file="components/admin/templates/default/footer.tpl"}