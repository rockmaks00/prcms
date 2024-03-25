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
				<form action="{$aTemplate.node_url}{$sFormAction}/" class="form-horizontal validate" method="post" enctype="multipart/form-data">
					<input type="hidden" name="id" value="{$oResult->getId()}">
					<input type="hidden" name="apply" value="0">
					<div class="tabbable tabbable-custom">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#portlet_tab1" data-toggle="tab"><i class="icon-cog"></i> Основные</a></li>
						</ul>
						<!-- BEGIN TAB CONTENT-->
						<div class="tab-content">
							<!-- BEGIN PORTLET TAB1-->
							<div class="tab-pane active" id="portlet_tab1">
								{assign var="aResultValues" value=$oResult->getValues()}
								{foreach from=$aFields item="oField"}
									{if $aResultValues[$oField->getName()]}
										{assign var="sResultValue" value=$aResultValues[$oField->getName()]["value"]->getValue()}
									{else}
										{assign var="sResultValue" value=""}
									{/if}

									{if  $oField->getType()=="label"}
										<div class="control-group">
											<label class="control-label">{$oField->getValue()}</label>
										</div>
									{else}
										<div class="control-group">
											<label class="control-label">{if $oField->isArray() || $oField->getType()!="checkbox"}{$oField->getTitle()}{/if}</label>
											<div class="controls">
											{if $oField->getType()=="textarea"}
												<textarea class="span6" name="{$oField->getName()}">{$sResultValue}</textarea>
											{elseif $oField->getType()=="checkbox"}
												{if $oField->isArray()}
													{assign var="aValues" value=$oField->getValueArray()}
													{foreach from=$aValues item="sValue" name="foo"}
														<label class="checkbox">
															<input type="{$oField->getType()}" name="{$oField->getName()}[]" value="{$sValue}" {if strpos($sResultValue, trim($sValue))!==false}checked="checked"{/if}>
															{$sValue}
														</label>
													{/foreach}
												{else}
													<label class="checkbox">
														<input type="{$oField->getType()}" name="{$oField->getName()}" value="{$oField->getValue()}" {if $sValue == $sResultValue}checked="checked"{/if}>
														{$oField->getTitle()}
													</label>
												{/if}
											{elseif $oField->getType()=="radio"}
												{*if $oField->isArray()*}
													{assign var="aValues" value=$oField->getValueArray()}
													{foreach from=$aValues item="sValue" name="foo"}
														<label>
															<input type="{$oField->getType()}" name="{$oField->getName()}[]" value="{$sValue}" {if $sValue == $sResultValue}checked="checked"{/if}>
															{$sValue}
														</label>
													{/foreach}
												{*else}
													<label class="radio">
														<input type="{$oField->getType()}" name="{$oField->getName()}" value="{$oField->getValue()}" {if $sValue == $sResultValue}checked="checked"{/if}>
														{$oField->getTitle()}
													</label>
												{/if*}
											{elseif  $oField->getType()=="select"}
												<select class="m-wrap span6 chosen" tabindex="1" name="{$oField->getName()}">
													{assign var="aValues" value=$oField->getValueArray()}
													{foreach from=$aValues item="sValue" name="foo"}
														<option value="{$sValue}" {if $sValue == $sResultValue}selected="selected"{/if}>{$sValue}</option>
													{/foreach}
												</select>
											{else}
												<input type="text" class="span6" name="{$oField->getName()}" value="{$sResultValue}">
											{/if}
											</div>
										</div>
									{/if}
								{/foreach}
							</div>
						</div>
							<!-- END PORTLET TAB1-->
					</div>
					<!-- END TAB CONTENT-->
					<div class="form-actions">
						<button type="submit" class="btn blue"><i class="icon-ok"></i> Сохранить</button>
						<button type="button" class="btn apply">Применить</button>
						<a class="btn" href="{$aTemplate.node_url}#portlet_tab1">Отмена</a>
					</div>
				</form>
				<!-- END FORM-->       
			</div>
		</div>
		<!-- END SAMPLE FORM PORTLET-->
	</div>
</div>
{include file="components/admin/templates/default/footer.tpl"}