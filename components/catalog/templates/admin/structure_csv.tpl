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
    </div>
    <div class="add_csv">
    <form <form action="" class="form-horizontal validate" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="{$aRequest.id}">
               <input type="hidden" name="apply" value="0">
               <input type="hidden" name="sub" value="1">
    <table id="draggable" class="table table-striped table-bordered table-advance table-hover" id="cr_csv">
    	<tr>
    		<th width="50">
    		активно	
    		</th>
    		<th  width="200">
    		Название
    		</th>
    		<th  >
    		Тип
    		</th>
    	</td>
		
        {foreach $aTitles['current'] as $value}
		{if (gettype($value)=="array")}
		{$iId=$value['attribute_id']}
		{$sTitle=$value['attribute_title']}
		{else }
		{$iId=$key}
		{$sTitle=$value}
		{/if}
    	<tr>
    		<td>
    			<input {if ((int)$value['attribute_id']==0)} type="hidden" name="Active[{$sTitle}]" {else } name="Active[{$iId}]" type="checkbox" checked{/if} class="group-checkable" data-set="#table1 .checkboxes" name="Active[{$iId}]" />
    		</td>
    		<td>
    		  {$sTitle}
    		</td>
    		<td width="200">
    			<select {if ((int)$value['attribute_id']==0)} name="type[{$sTitle}]" {else } name="type[{$iId}]" {/if}>
    				<option value="">
    				</optin>
    				<option  value="int">
    					Целое число
    				</optin>
    				<option value="text" selected>
    					текстовое поле
    				</optin>
                    <option value="textarea">
                        текстовое поле 2
                    </optin>
    				<option value="float">
    					с плавающей точкой
    				</optin>
    				<option value="date">
    					дата/время
    				</optin>
    			</select>
    		</td>
    	</tr>
        {/foreach}
		
		{foreach $aTitles as $key=>$value}
		{if (gettype($value)=="array")}
		{$iId=$value['attribute_id']}
		{$chc="checked"}
		{$sTitle=$value['attribute_title']}
		{else }
		{$iId=$key}
		{$chc=""}
		{$sTitle=$value}
		{/if}
			<tr>
    		<td>
    			<input {if ((int)$value['attribute_id']==0)} type="hidden" name="Active[{$sTitle}]" {else } name="Active[{$iId}]" type="checkbox"{/if}  class="group-checkable" data-set="#table1 .checkboxes"  />
    		</td>
    		<td>
    		  {$sTitle}
    		</td>
    		<td width="200">
    			<select {if ((int)$value['attribute_id']==0)} name="type[{$sTitle}]" {else } name="type[{$iId}]" {/if}>
    				<option value="">
    				</optin>
    				<option  value="int">
    					Целое число
    				</optin>
    				<option value="text" selected>
    					текстовое поле
    				</optin>
                    <option value="textarea">
                        текстовое поле 2
                    </optin>
    				<option value="float">
    					с плавающей точкой
    				</optin>
    				<option value="date">
    					дата/время
    				</optin>
    			</select>
    		</td>
    	</tr>
		{/foreach}
    </table>
    <div class="form-actions">
                          <button type="submit" class="btn blue"><i class="icon-ok"></i> Сохранить</button>
                          <button type="button" class="btn apply">Применить</button>
                          <button type="button" class="btn" onclick="document.location='{$aTemplate.node_url}';">Отмена</button>
                      </div>
    </form>
	</div>
</div>
</div>
{include file="components/admin/templates/default/footer.tpl"}