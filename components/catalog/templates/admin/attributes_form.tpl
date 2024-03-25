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

		    <form action="" class="form-horizontal validate" method="post" enctype="multipart/form-data">

		       <input type="hidden" name="id" value="{$aRequest.id}">
		       <input type="hidden" name="apply" value="0">
		       <input type="hidden" name="sub" value="1">
		       <div class="tabbable tabbable-custom">
         	       <ul class="nav nav-tabs">	
	         	   	  <li class="active"><a href="#portlet_tab1" data-toggle="tab"><i class="icon-cog"></i> Основные</a></li>
	                  <!-- <li><a href="#portlet_tab2" data-toggle="tab"><i class="icon-cogs"></i> Дополнительные</a></li> -->
	               </ul>
	               <!-- BEGIN TAB CONTENT-->
	               <div class="tab-content">
	               <!-- BEGIN PORTLET TAB1-->
				   	   <table class="table table-striped table-bordered table-advance table-hover">
							<thead>
								<tr>
									<th></th>
									<th><i class="icon-bookmark"></i> Название</th>
									<th><i class="icon-bookmark-empty"></i> Псевдоним</th>
									<th><i class="icon-tasks"></i> Тип</th>
									<th><i class="icon-info-sign"></i> По-умолчанию</th>
									<th width="120"><i class="icon-exclamation-sign"></i> Обязательно</th>
									<th width="100"><i class="icon-reorder"></i> Сортировка</th>
									<th width="50">Удалить</th>
								</tr>
							</thead>
							<tbody>
								{foreach from=$aAttributes item="item" name="foo"}
								<tr>
									<td>
										<input name="attribute[id][]" value="{$item->getId()}" type="hidden">
										<span style="line-height: 30px;">{$item->getId()}</span>
									</td>
									<td>
										<input name="attribute[title][]" value="{$item->getTitle()}" type="text" class="span12 m-wrap popovers" data-trigger="hover" data-original-title="Заголовок" data-content="Заголовок {$aLang.items_genitive}. Используются любые символы и буквы.">
									</td>
									<td><input name="attribute[name][]" value="{$item->getName()}" type="text" class="span12 m-wrap"></td>
									<td>
										<select name="attribute[type][]">
											<option value="text"{if $item->getType()=="text"} selected="selected"{/if}>
												Текстовое поле
											</option>
											<option value="textarea"{if $item->getType()=="textarea"} selected="selected"{/if}>
												Многострочное текстовое поле
											</option>
											<!--
<option value="select"{if $item->getType()=="select"} selected="selected"{/if}>
												Выпадающий список
											</option>
-->
											<option value="checkbox"{if $item->getType()=="checkbox"} selected="selected"{/if}>
												Флаг
											</option>
											<!--
<option value="file"{if $item->getType()=="file"} selected="selected"{/if}>
												Файл/Картинка
											</option>
-->
										</select>
									</td>
									<td><input name="attribute[default][]" value="{$item->getDefault()}" type="text" class="span12 m-wrap"></td>
									<td style="text-align: center;">
					                     <select name="attribute[active][]" style="width: 70px;">
											<option value="0"{if $item->getActive()=="0"} selected="selected"{/if}>
												Нет
											</option>
											<option value="1"{if $item->getActive()=="1"} selected="selected"{/if}>
												Да
											</option>
										</select>
		                     		</td>
									<td><input name="attribute[sort][]" value="{if !$item->getId()}500{else}{$item->getSort()}{/if}" type="text" class="span12 m-wrap"></td>
									<td style="text-align: center;">
										 <div class="basic-toggle-button">
					                        <input name="attribute[delete][]" type="checkbox" class="toggle" value="{$item->getId()}" />
					                     </div>
		                     		</td>
								</tr>
								{/foreach}
		
							</tbody>
						</table>
					
				    <!-- END FORM-->  
				</div>
				
			</div>   
			<div class="form-actions" style="padding-left: 20px;">
                <button type="submit" class="btn blue"><i class="icon-ok"></i> Сохранить</button>
                <button type="button" class="btn apply">Применить</button>
                <button type="button" class="btn" onclick="document.location='{$aTemplate.node_url}';">Отмена</button>
            </div>  
            </form>
         </div>
      </div>
      <!-- END SAMPLE FORM PORTLET-->
   </div>
</div>


{*

<div>
	<div>
		<label class="control-label">Название</label>
			<div class="controls">
				<input name="attrtitle" value="{$aRequest.attrtitle}" type="text" class="span6 m-wrap popovers" data-trigger="hover" data-original-title="Заголовок" data-content="Заголовок {$aLang.items_genitive}. Используются любые символы и буквы." data-validate-rule="^.+$" data-validate-content="Пожалуйста, заполните поле.">
			</div>
		<lable class="control-label">Тип</lable>
		<div class="controls" >
			<select name="combotype">
				<option></option>
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
				<option value="5">
					выпадающий список
				</option>
			</select>
		</div>
		<div name="divnews" style="display:none;">
		<lable class="control-label">Раздел</lable>
		<div class="controls" >
			<select name="combonews">
				<option></option>
				{foreach $aNews as $oItem}
				<option value="{$oItem->getId()}">{$oItem->getTitle()}</option>
				{/foreach}
			</select>
		</div>
	</div>
		<lable class="control-label">Копировать Структуру из </lable>
		<div class="controls" >
			<select name="combocopy">
				<option></option>
				{foreach $aCatalogs as $oItem}
				<option value="{$oItem['attr_catalog']}">{$oItem['catalog_title']}</option>
				{/foreach}
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
				<th width="80">Тип</th>
				<th><i class="icon-eye-open"></i> Показывать</th>
			</tr>
		</thead>
		<tbody>
			{foreach $aAttrs as $oItem}
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
								<li><a href="#modalDelete" data-toggle="modal" onclick="modalConfirm(null, null, '{$aTemplate.node_url}deletecatalogattr/{$oItem->getId()}/')"><i class="icon-trash"></i> Удалить</a></li>
							</ul>
						</div>
					</td>
					<td>
						{$oItem->getTitle()}
					</td>
					<td>
						{$oItem->getType()}
					</td>
					<td>{if $oItem->getActive()==1}<a href="#" onclick="return ajaxActivate('{$aTemplate.node_url}deactivateattr/{$oItem->getId()}/', this, {$oItem->getId()});"><span class="badge badge-success">Да</span></a>{else}<a href="#" onclick="return ajaxActivate('{$aTemplate.node_url}activateattr/{$oItem->getId()}/', this, {$oItem->getId()});"><span class="badge badge-important">Нет</span></a>{/if}</td>
				</tr>
			{/foreach}
		</tbody>
	</table>
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
*}
{include file="components/admin/templates/default/footer.tpl"}