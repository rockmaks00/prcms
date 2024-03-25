	{foreach $aAttrs as $oItem}
			{if $oItem['attr_type']==1}
			<label class="control-label">{$oItem['attr_title']}</label>
			                  <div class="controls">
			                     <input name="{$oItem['attr_title']}" value="{$oItem['attritem_value']}" type="text" class="span6 m-wrap popovers " data-trigger="hover" data-original-title="Заголовок" data-content="Название {$aLang.items_genitive}. Используются любые символы и буквы." data--rule="^.+$" data--content="Пожалуйста, заполните поле.">
			                  </div>
			{/if}
			{if $oItem['attr_type']==2}
			<label class="control-label">{$oItem['attr_title']}</label>
			                  <div class="controls">
			                     <input name="{$oItem['attr_title']}" value="{$oItem['attritem_value']}" type="text" class="span6 m-wrap popovers " data-trigger="hover" data-original-title="Заголовок" data-content="Название {$aLang.items_genitive}. Используются любые символы и буквы." data--rule="^.+$" data--content="Пожалуйста, заполните поле.">
			                  </div>
			{/if}
			{if $oItem['attr_type']==3}
			<label class="control-label">{$oItem['attr_title']}</label>
			                  <div class="controls">
			                     <textarea name="{$oItem['attr_title']}" value="{$oItem['attritem_value']}" class="span6 m-wrap ckeditor popovers " data-trigger="hover" data-original-title="Заголовок" data-content="Название {$aLang.items_genitive}. Используются любые символы и буквы." data--rule="^.+$" data--content="Пожалуйста, заполните поле."></textarea>
			                  </div>
			{/if}
			{if $oItem['attr_type']==4}
			<label class="control-label">{$oItem['attr_title']}</label>
			                  <div class="controls">
			                     <input name="{$oItem['attr_title']}" value="{$oItem['attritem_value']}" type="date" class="span6 m-wrap popovers " data-trigger="hover" data-original-title="Заголовок" data-content="Название {$aLang.items_genitive}. Используются любые символы и буквы." data--rule="^.+$" data--content="Пожалуйста, заполните поле.">
			                  </div>
			{/if}
			{if $oItem['attr_type']==5}
				<label class="control-label" style="clear:left">{$oItem['attr_title']}</label>
			                  <div class="controls" style="clear:left">
			                     <select name="{$oItem['attr_title']}">
			                     	<option></option>
			                     	{foreach $oItem['param'] as $oAttr}
			                     	<option value="{$oAttr['news_id']}">{$oAttr['news_title']}</option>
			                     	{/foreach}
			                     </select>
			                  </div>
			{/if}
	{/foreach}