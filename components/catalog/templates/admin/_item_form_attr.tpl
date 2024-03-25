
	{foreach $aAttrs as $oItem}
			{if $oItem->getType()==1}
			<label class="control-label">{$oItem->getTitle()}</label>
			                  <div class="controls">
			                     <input name="{$oItem->getTitle()}" value="{$aRequest.count}" type="text" class="span6 m-wrap popovers " data-trigger="hover" data-original-title="Заголовок" data-content="Название {$aLang.items_genitive}. Используются любые символы и буквы." data--rule="^.+$" data--content="Пожалуйста, заполните поле.">
			                  </div>
			{/if}
			{if $oItem->getType()==2}
			<label class="control-label">{$oItem->getTitle()}</label>
			                  <div class="controls">
			                     <input name="{$oItem->getTitle()}" value="{$aRequest.count}" type="text" class="span6 m-wrap popovers " data-trigger="hover" data-original-title="Заголовок" data-content="Название {$aLang.items_genitive}. Используются любые символы и буквы." data--rule="^.+$" data--content="Пожалуйста, заполните поле.">
			                  </div>
			{/if}
			{if $oItem->getType()==3}
			<label class="control-label">{$oItem->getTitle()}</label>
			                  <div class="controls">
			                     <textarea name="{$oItem->getTitle()}" value="{$aRequest.count}" class="span6 m-wrap ckeditor popovers " data-trigger="hover" data-original-title="Заголовок" data-content="Название {$aLang.items_genitive}. Используются любые символы и буквы." data--rule="^.+$" data--content="Пожалуйста, заполните поле."></textarea>
			                  </div>
			{/if}
			{if $oItem->getType()==4}
			<label class="control-label">{$oItem->getTitle()}</label>
			                  <div class="controls">
			                     <input name="{$oItem->getTitle()}" value="{$aRequest.count}" type="date" class="span6 m-wrap popovers " data-trigger="hover" data-original-title="Заголовок" data-content="Название {$aLang.items_genitive}. Используются любые символы и буквы." data--rule="^.+$" data--content="Пожалуйста, заполните поле.">
			                  </div>
			{/if}
			{if $oItem->getType()==5}
			<label class="control-label">{$oItem->getTitle()}</label>
			                  <div class="controls" style="clear:false">
			                     <select name="{$oItem->getTitle()}">
			                     	<option value="asdasd">asd</option>
			                     </select>
			                  </div>
			{/if}
	{/foreach}