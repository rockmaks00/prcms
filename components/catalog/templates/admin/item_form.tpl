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
	                     <div class="tab-pane active" id="portlet_tab1">
			               <div class="control-group">
			                  <label class="control-label">Название</label>
			                  <div class="controls">
			                     <input name="title" value="{$aRequest.title}" type="text" class="span6 m-wrap popovers validate" data-trigger="hover" data-original-title="Заголовок" data-content="Название {$aLang.items_genitive}. Используются любые символы и буквы." data-validate-rule="^.+$" data-validate-content="Пожалуйста, заполните поле.">
			                  </div>
			               </div>
			               {if $isIm}
			               <div class="control-group">
			                  <label class="control-label">Стоимость</label>
			                  <div class="controls">
			                     <input name="price" value="{$aRequest.price}" type="text" class="span6 m-wrap"><span class="help-inline">руб.</span>
			                  </div>
			               </div>
			               <div class="control-group">
			                  <label class="control-label">Имеется в наличии</label>
			                  <div class="controls">
			                     <input name="count" value="{$aRequest.count}" type="text" class="span6 m-wrap"> <span class="help-inline">шт.</span>
			                  </div>
			               </div>
			               {/if}
			               <div class="control-group">
	                          <label class="control-label">Изображение</label>
	                          <div class="controls">
	                          	 {if $aRequest.image}
	                          	 <div class="item" style="width: 200px;">
									<a class="fancybox-button" data-rel="fancybox-button" href="{$aRequest.image}" target="_blank">
										<div class="zoom">
											<img src="{$aRequest.image}" alt="" />							
											<div class="zoom-icon"></div>
										</div>
									</a>
									<div class="details">
										<a href="/admin/files/" target="_blank" class="icon"><i class="icon-pencil"></i></a>
										<a href="#" class="icon" onclick="return removeImage('{$aTemplate.node_url}removeimage/{$aRequest.id}/', this);"><i class="icon-remove"></i></a>		
									</div>
								 </div>
								 {/if}
	                             <div class="fileupload fileupload-new" data-provides="fileupload">
	                                <div class="input-append">
	                                   <div class="uneditable-input">
	                                      <i class="icon-file fileupload-exists"></i> 
	                                      <span class="fileupload-preview"></span>
	                                   </div>
	                                   <span class="btn btn-file">
	                                   <span class="fileupload-new">Выберите файл</span>
	                                   <span class="fileupload-exists">Изменить</span>
	                                   <input name="image" type="file" class="default" />
	                                   </span>
	                                   <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Удалить</a>
	                                </div>
	                             </div>
	                          </div>
	                      </div>
	                      <div class="control-group">
			                  <label class="control-label">Сортировка</label>
			                  <div class="controls">
			                     <input name="sort" value="{if $aRequest.sort}{$aRequest.sort}{else}500{/if}" type="text" class="span6 m-wrap"> 			                  </div>
			              </div>
			              <div class="control-group">
			                  <label class="control-label">Показывать</label>
			                  <div class="controls">
			                     <div class="basic-toggle-button">
			                        <input name="active" type="checkbox" class="toggle"{if $aRequest.active} checked="checked"{/if} value="1" />
			                     </div>
			                  </div>
			              </div>
			               
						  <hr>
			              {foreach from=$aAttributes item="item" name="foo"}
		                  	 {if $item->getType()=="text"}
		                     	{include file="components/admin/templates/default/form/text.tpl" title=$item->getTitle() name="attribute_{$item->getId()}" value=$item->getDefault()}
		                     {else if $item->getType()=="textarea"}
		                     	{include file="components/admin/templates/default/form/textarea.tpl" title=$item->getTitle() name="attribute_{$item->getId()}" value=$item->getDefault() editor="true"}
		                     {else if $item->getType()=="select"}
			                     <div class="control-group">
					                  <label class="control-label">{$item->getTitle()}</label>
					                  <div class="controls">
					                  	<select name="attribute_{$item->getId()}" class="span6 m-wrap chosen">
										  	<option value="">--</option>
										</select>
					                  </div>
			                     </div>
			                 {else if $item->getType()=="checkbox"}
			                     <div class="control-group">
					                  <label class="control-label">{$item->getTitle()}</label>
					                  <div class="controls">
					                     <div class="basic-toggle-button">
					                        <input name="{$item->getName()}" type="checkbox" class="toggle"{if $item->getDefault()} checked="checked"{/if} value="{$item->getDefault()}" />
					                     </div>
					                  </div>
					              </div>
			                 {else if $item->getType()=="file"}
			                    <div class="control-group">
		                          <label class="control-label">{$item->getTitle()}</label>
		                          <div class="controls">
		                          	 {if $item->getDefault()}
		                          	 <div class="item" style="width: 200px;">
										<a class="fancybox-button" data-rel="fancybox-button" href="{$item->getDefault()}" target="_blank">
											<div class="zoom">
												<img src="{$item->getDefault()}" alt="" />							
												<div class="zoom-icon"></div>
											</div>
										</a>
										<div class="details">
											<a href="/admin/files/" target="_blank" class="icon"><i class="icon-pencil"></i></a>
											<a href="#" class="icon" onclick="return removeImage('{$aTemplate.node_url}removefile/{$item->getId()}/', this);"><i class="icon-remove"></i></a>		
										</div>
									 </div>
									 {/if}
		                             <div class="fileupload fileupload-new" data-provides="fileupload">
		                                <div class="input-append">
		                                   <div class="uneditable-input">
		                                      <i class="icon-file fileupload-exists"></i> 
		                                      <span class="fileupload-preview"></span>
		                                   </div>
		                                   <span class="btn btn-file">
		                                   <span class="fileupload-new">Выберите файл</span>
		                                   <span class="fileupload-exists">Изменить</span>
		                                   <input name="image" type="file" class="default" />
		                                   </span>
		                                   <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Удалить</a>
		                                </div>
		                             </div>
		                          </div>
		                        </div>
		                     {else}
		                     	{include file="components/admin/templates/default/form/text.tpl" title=$item->getTitle() name="attribute_{$item->getId()}" value=$item->getDefault()}

		                     {/if}
			              {/foreach}
			          </div>
			          <!-- END PORTLET TAB1-->
			          <!-- BEGIN PORTLET TAB2-->
	                  <div class="tab-pane" id="portlet_tab2">
	                  	{*if $aRequest.id}
	                  	{include file="components/catalog/templates/admin/item_form_attr2.tpl"} {else}
	                  	{include file="components/catalog/templates/admin/item_form_attr.tpl"}
	                  	{/if*}
	                  </div>	
	                  <!-- END PORTLET TAB2-->
			          <!-- BEGIN PORTLET TAB3-->
	                  <div class="tab-pane" id="portlet_tab3">
	                  	
	                  </div>	
	                  <!-- END PORTLET TAB3-->
	                  <div class="form-actions">
		                  <button type="submit" class="btn blue"><i class="icon-ok"></i> Сохранить</button>
		                  <button type="button" class="btn apply">Применить</button>
		                  <button type="button" class="btn" onclick="document.location='{$aTemplate.node_url}';">Отмена</button>
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
{include file="components/admin/templates/default/footer.tpl"}