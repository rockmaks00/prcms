<div class="hook-feedback-default">
	<div class="text">{$oPage->getBody()}</div>
	<form action="{$oNode->getFullUrl()}submit/" method="post">	
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
			<h3>Вы можете оставить свою заявку здесь и сейчас</h3>
		</div>
		<div {*class="modal-body"*}>
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
								<textarea name="{$oField->getName()}" {if $oField->getRequired()}data-required="1"{/if}>{$oField->getValue()}</textarea>
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
								{*if $oField->isArray()*}
									{assign var="aValues" value=$oField->getValueArray()}
									{foreach from=$aValues item="sValue" name="foo"}
										<input id="field_{$oField->getId()}_{$smarty.foreach.foo.index}" type="{$oField->getType()}" name="{$oField->getName()}" value="{$sValue}">
										<label for="field_{$oField->getId()}_{$smarty.foreach.foo.index}">{$sValue}</label><br>
									{/foreach}
								{*else}
									<input id="field_{$oField->getId()}" type="{$oField->getType()}" name="{$oField->getName()}" value="{$oField->getValue()}">
									<label for="field_{$oField->getId()}">{$oField->getTitle()}</label>
								{/if*}
							{elseif $oField->getType()=="select"}
								<select name="{$oField->getName()}" {if $oField->getRequired()}data-required="1"{/if}>
									{assign var="aValues" value=$oField->getValueArray()}
									{foreach from=$aValues item="sValue" name="foo"}
										<option value="{$sValue}">{$sValue}</option>
									{/foreach}
								</select>
							{else}
								<input type="{$oField->getType()}" name="{$oField->getName()}" value="{$oField->getValue()}" {if $oField->getRequired()}data-required="1"{/if}>
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
			<input class="btn green" type="submit" name="submit" value="Отправить">
		</div>
		<div class="overlay">
			<div class="sending"></div>
			<div class="error">По техническим причинам Ваша заявка не была отправлена.</div>
			<div class="success">Ваша заявка принята, спасибо за обращение.</div>
		</div>
	</form>
</div>