{include file="components/admin/templates/default/header.tpl"}

<div class="container">
    <div class="row-fluid">
        <div class="span10">
            <form id="fieldForm" class="form-horizontal">
                <div class="control-group">
                    <label class="control-label" for="field_id">ID</label>
                    <div class="controls">
                        <input type="text" id="field_id" name="field_id" readonly value="{$aField['field_id']}">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="field_group">Группа</label>
                    <div class="controls">
                        <input type="text" id="field_group" name="field_group" value="{$aField['field_group']}">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="field_task">Задача</label>
                    <div class="controls">
                        <textarea id="field_task" name="field_task" rows="3">{$aField['field_task']}</textarea>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="field_spent_time">Время факт.</label>
                    <div class="controls">
                        <input type="text" id="field_spent_time" name="field_spent_time" value="{$aField['field_spent_time']}">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="field_planned_time">Время план.</label>
                    <div class="controls">
                        <input type="text" id="field_planned_time" name="field_planned_time" value="{$aField['field_planned_time']}">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="field_amount">Сумма</label>
                    <div class="controls">
                        <input type="text" id="field_amount" name="field_amount" value="{$aField['field_amount']}">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="field_creation_date">Дата создания</label>
                    <div class="controls">
                        <input type="date" id="field_creation_date" name="field_creation_date" value="{$aField['field_creation_date']}">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="field_link">Ссылка</label>
                    <div class="controls">
                        <input type="url" id="field_link" name="field_link" value="{$aField['field_link']}">
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn green" id="fieldSave" data-url="{$sUrl}">Сохранить</button>
                    <button type="button" class="btn red" id="fieldDelete" data-url="{$sUrl}">Удалить</button>
                </div>
            </form>
        </div>
    </div>
</div>

{include file="components/admin/templates/default/footer.tpl"}