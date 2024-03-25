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
		    <form action="{$aTemplate.node_url}{$action}/" class="form-horizontal validate" method="post" enctype="multipart/form-data">
		       <input type="hidden" name="id" value="{$oNews->getId()}">
		       <input type="hidden" name="node" value="{$oNews->getNode()}">
		       <input type="hidden" name="apply" value="0">
		       <div class="tabbable tabbable-custom">
         	       <ul class="nav nav-tabs">
	         	   	  <li class="active"><a href="#portlet_tab1" data-toggle="tab"><i class="icon-cog"></i> Основные</a></li>
	               </ul>
	               <!-- BEGIN TAB CONTENT-->
	               <div class="tab-content">
	               	 	 <!-- BEGIN PORTLET TAB1-->
	                     <div class="tab-pane active" id="portlet_tab1">
							{include file="components/admin/templates/default/form/text.tpl" title="Заголовок" name="title" value=$oNews->getTitle() popover=true hint="Заголовок баннера. Используются любые символы и буквы." validate=true validate_rule="^.+$"}
							{include file="components/admin/templates/default/form/datetime.tpl" title="Дата и время" datename="date" dateval=$oNews->getDate() timename="time" timeval=$oNews->getTime()}
							{include file="components/admin/templates/default/form/textarea.tpl" title="Анонс новости" name="announcement" value=$oNews->getaAnnouncement() popover=1 hint="Краткое описание новости. Одно-два предложения."}
							{include file="components/admin/templates/default/form/editor.tpl" title="Текст новости" name="body" value=$oNews->getBody()}
							{include file="components/admin/templates/default/form/checkbox.tpl" title="Показывать" name="active" value=$oNews->getActive()}
							{include file="components/admin/templates/default/form/image.tpl" title="Изображение" name="image" image=$oNews->getImage() remove="{$aTemplate.node_url}/removeimage/{$oNews->getId()}/" }
			          </div>
			          <!-- END PORTLET TAB1-->
	                  <div class="form-actions">
		                  <button type="submit" class="btn blue"><i class="icon-ok"></i> Сохранить</button>
		                  <button type="button" class="btn apply">Применить</button>
		                  <a type="button" class="btn" href="{$aTemplate.node_url}">Отмена</a>
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