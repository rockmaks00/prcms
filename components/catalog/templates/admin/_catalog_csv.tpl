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
                     <li><a href="#portlet_tab2" data-toggle="tab"><i class="icon-cogs"></i> Структура каталога</a></li>
                  </ul>
	               <!-- BEGIN TAB CONTENT-->

	               <div class="tab-content">
						<!-- BEGIN PORTLET TAB1-->
						<div class="tab-pane active" id="portlet_tab1">
							<div class="control-group">
                        <label class="control-label">CSV файл</label>
                        <div class="controls">
                          <div class="fileupload fileupload-new" data-provides="fileupload">
                             <div class="input-append">
                                <div class="uneditable-input">
                                   <i class="icon-file fileupload-exists"></i> 
                                   <span class="fileupload-preview"></span>
                                </div>
                                <span class="btn btn-file">
                                <span class="fileupload-new">Выберите файл</span>
                                <span class="fileupload-exists">Изменить</span>
                                <input name="csv" type="file" class="default" />
                                </span>
                                <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Удалить</a>
                             </div>
                          </div>
                       </div>
                     </div>
                     <!--
                     {*if $aCsvHeader}
                       <div class="control-group">
                       		<label class="control-label">Загруженная таблица</label>
                           <div class="controls">
   									<table class="table table-striped table-bordered" id="sample_1">
   										<thead>
   											<tr>
   												<th style="width:8px;"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
   												{foreach from=$aCsvHeader item=sItem}
   													<th class="hidden-phone">{$sItem}</th>
   								            {/foreach}
   											</tr>
   										</thead>
   										<tbody>
   											{foreach from=$aCsv item="oItem"}
   											<tr class="odd gradeX">
   												<td><input type="checkbox" class="checkboxes" value="1" /></td>
   								            	{foreach from=$aCsvHeader key=k item=sProp}
   													<td class="hidden-phone">{$oItem->GetValueByKey($sProp)}</td>
   								            	{/foreach}
   											</tr>
   								            {/foreach}
   										</tbody>
   									</table>
   								</div>
                       </div>
                     {/if*}-->
						</div>
						<!-- END PORTLET TAB1-->

						<!-- BEGIN PORTLET TAB2-->
						<div class="tab-pane" id="portlet_tab2">
							<!--{*foreach from=$aCsvHeader item=sItem}
								<div class="control-group">
             			<label class="control-label">{$sItem}</label>
                 	<div class="controls">
                    <select class="chosen fields" data-placeholder="Выберите тип поля" tabindex="1" style="width: 400px;">
                      <option value=""></option>
                      {foreach from=$aFields item=oField}
                        <option value="{$oField->getId()}" name="{$oField->getType}">{$oField->getName()} ({$oField->getType()})</option>
                      {/foreach}
                    </select>
                    <select class="chosen fields" data-placeholder="Выберите связь" tabindex="1" style="width: 400px;">
                      <option value=""></option>
                      {foreach from=$aCsvHeader item=sItem}
                        <option value="{$sItem}" name="{$sItem}">{$sItem}</option>
                      {/foreach}
                    </select>
							     </div>
								</div>
							{/foreach*}-->
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
{*mpr($aCsvHeader)*}
{*mpr($aCsv)*}
{include file="components/admin/templates/default/footer.tpl"}
