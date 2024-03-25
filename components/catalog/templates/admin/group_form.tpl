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
	                  <li><a href="#portlet_tab2" data-toggle="tab"><i class="icon-cogs"></i> Дополнительные</a></li>
	               </ul>
	               <!-- BEGIN TAB CONTENT-->
	               <div class="tab-content">
	               	 	 <!-- BEGIN PORTLET TAB1-->
	                     <div class="tab-pane active" id="portlet_tab1">
			               <div class="control-group">
			                  <label class="control-label">Родительская группа</label>
			                  <div class="controls">
			                     <select name="parent" class="span6 m-wrap chosen" data-placeholder="Выберите родительскую группу" tabindex="1">
			                        <option value="">--</option>
			                        {foreach from=$aGroups item="oGroup"}
			                        <option value="{$oGroup->getId()}"{if $aRequest.parent==$oGroup->getId()} selected="selected"{/if}>{$oGroup->getTitle()}</option>
			                        {/foreach}
			                     </select>
			                  </div>
			               </div>
			               
			               <div class="control-group">
			                  <label class="control-label">Заголовок</label>
			                  <div class="controls">
			                     <input name="title" value="{$aRequest.title}" type="text" class="span6 m-wrap popovers validate" data-trigger="hover" data-original-title="Заголовок" data-content="Заголовок {$aLang.items_genitive}. Используются любые символы и буквы." data-validate-rule="^.+$" data-validate-content="Пожалуйста, заполните поле.">
			                  </div>
			               </div>
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

							{include file="components/admin/templates/default/form/textarea.tpl" title="Описание" name="desc" value=$aRequest.desc editor="true"}
			              <div class="control-group">
			                  <label class="control-label">Показывать</label>
			                  <div class="controls">
			                     <div class="basic-toggle-button">
			                        <input name="active" type="checkbox" class="toggle"{if $aRequest.active} checked="checked"{/if} value="1" />
			                     </div>
			                  </div>
			               </div>
			          </div>
			          <!-- END PORTLET TAB1-->
			          <!-- BEGIN PORTLET TAB2-->
	                  <div class="tab-pane" id="portlet_tab2">
					  		
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
