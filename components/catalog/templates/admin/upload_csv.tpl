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
    	<div>
    		<a href="{$aTemplate.node_url}changecsv/" class="btn">Изменить структуру CSV</a>
    	</div>
    	<form <form action="" class="form-horizontal validate" method="post" enctype="multipart/form-data">
    		<input type="hidden" name="id" value="{$aRequest.id}">
		       <input type="hidden" name="apply" value="0">
		       <input type="hidden" name="sub" value="1">
		<table class="table table-striped table-bordered table-advance table-hover" magrin-top=10>
    	<tr>
    	{foreach $aTitles as $value}
    		<th>
    		  {$value['attribute_title']}
    		</th>
    		{/foreach}
            <th>
            	{$aRequest.Title="item_count"}
                item_count
            </th>           
            <th>
                item_price
            </th>
            <th>
               item_title
            </th>
            <th>
                item_node

            </th>
            <th>
               item_active

            </th>    
            <th>
                item_image
            </th>           
            <th>
                item_group
            </th>
            <th>
                item_sor
            </th>
		</tr>
		<tr>
    	{foreach $data as $value}
    		<td>
    		  {if ($value['value_value']!="")} {$value['value_value']} {else }
    		  0
    		  {/if}
    		</td>
    		{/foreach}
            <td>
                item_count
            </td>           
            <td>
                item_price
            </td>
            <td>
               item_title
            </td>
            <td>
                item_node

            </td>            
            <td>
               item_active

            </td>
            <td>
                item_image
            </td>
            <td>
                item_group
            </td>
            <td>
                item_sor
            </td>
        </tr>
	</table>
			<input type="file" name="file"> 
			<input type="submit"  name="btn_save" value="Загрузить" class="btn"></a>
			<input type="reset" class="btn" value="Отмена">
  	<div class="form-actions">
		                  <button type="submit" class="btn blue"><i class="icon-ok"></i> Сохранить</button>
		                  <button type="button" class="btn apply">Применить</button>
		                  <button type="button" class="btn" onclick="document.location='{$aTemplate.node_url}';">Отмена</button>
		              </div>
  </form>
	</div>
	
</div>
{include file="components/admin/templates/default/footer.tpl"}