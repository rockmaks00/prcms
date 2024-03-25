{include file="header.tpl"}
	<div class="feedback-default">
		
		{$oFeedback->getText()}

		{if $sMsg}<div class="msg">{$sMsg}</div>{/if}

		{if $bShowResults}
			<ul>
				{foreach from=$aResults item=oResult}
					{if $oResult && $oResult->getActive()}
						<li class="result">
							<ul class="values">
								{assign var="aValues" value=$oResult->getValues()}
								{foreach from=$aValues item="aValue"}
									{if is_object($aValue["field"]) && is_object($aValue["value"])}
										<li><span>{$aValue['field']->getTitle()}</span> <span>{$aValue['value']->getValue()}</span></li>
									{/if}
								{/foreach}
							</ul>
							<ul class="answers">
								{foreach from=$oResult->getAnswers() item="oAnswer"}
									<li>
										<div class="author">{$oAnswer->getAuthor()}</div>
										<div class="text">{$oAnswer->getText()}</div>
									</li>
								{/foreach}
							</ul>
						</li>
					{/if}
				{/foreach}
			</ul>
		{/if}

		{hook name='Feedback' component="feedback" template='default' node=$oFeedback->getNode()}
	</div>

{include file="footer.tpl"}
