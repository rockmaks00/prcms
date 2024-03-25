<form action="/registration/" method="post">	
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
		<h3 id="modalDeleteLabel">Регистрация</h3>
	</div>
	<div class="modal-body">
		{foreach from=$aFields item="oField" name="foo"}
			{if $oField->getActive()==1}
				{if  $oField->getType()=="label"}
					<div class="control-group">
						<p class="label">{$oField->getTitle()}</p>
					</div>
				{else}
					<div class="control-group">
						<label class="control-label">{if $oField->isArray() || $oField->getType()!="checkbox"}{$oField->getTitle()}{/if}</label>
						<div class="controls">
						{if $oField->getType()=="textarea"}
							<textarea name="{$oField->getName()}">{$oField->getValue()}</textarea>
						{elseif $oField->getType()=="checkbox"}
							{if $oField->isArray()}
								{assign var="aValues" value=$oField->getValueArray()}
								{foreach from=$aValues item="sValue" name="foo"}
									<input id="field_{$oField->getId()}_{$smarty.foreach.foo.index}" type="{$oField->getType()}" name="{$oField->getName()}[]" value="{$sValue}">
									<label for="field_{$oField->getId()}_{$smarty.foreach.foo.index}">{$sValue}</label><br>
								{/foreach}
							{else}
								<input id="field_{$oField->getId()}" type="{$oField->getType()}" name="{$oField->getName()}" value="{$oField->getValue()}">
								<label for="field_{$oField->getId()}">{$oField->getTitle()}</label>
							{/if}
						{elseif $oField->getType()=="radio"}
							{if $oField->isArray()}
								{assign var="aValues" value=$oField->getValueArray()}
								{foreach from=$aValues item="sValue" name="foo"}
									<input id="field_{$oField->getId()}_{$smarty.foreach.foo.index}" type="{$oField->getType()}" name="{$oField->getName()}[]" value="{$sValue}">
									<label for="field_{$oField->getId()}_{$smarty.foreach.foo.index}">{$sValue}</label><br>
								{/foreach}
							{else}
								<input id="field_{$oField->getId()}" type="{$oField->getType()}" name="{$oField->getName()}" value="{$oField->getValue()}">
								<label for="field_{$oField->getId()}">{$oField->getTitle()}</label>
							{/if}
						{else}
							<input type="{$oField->getType()}" name="{$oField->getName()}" value="{$oField->getValue()}">
						{/if}
						</div>
					</div>
				{/if}
			{/if}
		{/foreach}
	</div>
	<div class="modal-footer">
		<input type="text" name="security" value="" style="display: none;">
		<input type="hidden" name="feedback" value="{$oFeedback->getId()}">
		<input class="btn green" type="submit" name="submit" value="Зарегистрироваться">
	</div>
</form>