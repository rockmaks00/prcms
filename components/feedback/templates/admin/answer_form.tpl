{include file="components/admin/templates/default/header.tpl"}
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
					<input type="hidden" name="id" value="{$oAnswer->getId()}">
					<input type="hidden" name="apply" value="0">
					<input type="hidden" name="result" value="{$oAnswer->getResult()}">
					<input type="hidden" name="active" value="0">
					<div class="tabbable tabbable-custom">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#portlet_tab1" data-toggle="tab"><i class="icon-cog"></i> Основные</a></li>
							<li class=""><a href="#portlet_tab2" data-toggle="tab"><i class="icon-envelope"></i> Сообщение</a></li>
						</ul>
						<!-- BEGIN TAB CONTENT-->
						<div class="tab-content">
							<!-- BEGIN PORTLET TAB1-->
							<div class="tab-pane active" id="portlet_tab1">
								{include file="components/admin/templates/default/form/text.tpl" title="Автор" name="author" value=$oAnswer->getAuthor() popover=true hint="Заголовок поля. Используются любые символы и буквы." validate=true validate_rule="^.+$"}

								{include file="components/admin/templates/default/form/editor.tpl" title="Текст ответа" name="text" value=$oAnswer->getText()}

								{include file="components/admin/templates/default/form/checkbox.tpl" title="Показывать" name="active" value=$oAnswer->getActive()}
							</div>
							<!-- END PORTLET TAB1-->

							<!-- BEGIN PORTLET TAB2-->
							<div class="tab-pane" id="portlet_tab2">
								{foreach from=$oResult->getValues() item="aValue" name="foo"}
									{$aValue.field->getTitle()}: {$aValue.value->getValue()}<br>
								{/foreach}
							</div>
							<!-- END PORTLET TAB2-->
						</div>
					</div>
					<!-- END TAB CONTENT-->
					<div class="form-actions">
						<button type="submit" class="btn blue"><i class="icon-ok"></i> Сохранить</button>
						<a class="btn" href="{$aTemplate.node_url}answers/{$oResult->getId()}/">Отмена</a>
					</div>
				</form>
				<!-- END FORM-->       
			</div>
		</div>
		<!-- END SAMPLE FORM PORTLET-->
	</div>
</div>
{include file="components/admin/templates/default/footer.tpl"}