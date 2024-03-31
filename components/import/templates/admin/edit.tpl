{include file="components/admin/templates/default/header.tpl"}

<div class="container">
    <div class="row-fluid">
        <div class="span10">
            <form action="{$sUrl}/update" id="fieldForm" class="form-horizontal" method="post" enctype="multipart/form-data">
                <div class="control-group">
                    <label class="control-label" for="id">ID</label>
                    <div class="controls">
                        <input type="text" id="id" name="id" readonly value="{$aField['id']}">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="group">Группа</label>
                    <div class="controls">
                        <input type="text" id="group" name="group" value="{$aField['group']}">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="task">Задача</label>
                    <div class="controls">
                        <textarea id="task" name="task" rows="3">{$aField['task']}</textarea>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="spent_time">Время факт.</label>
                    <div class="controls">
                        <input type="text" id="spent_time" name="spent_time" value="{$aField['spent_time']}">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="planned_time">Время план.</label>
                    <div class="controls">
                        <input type="text" id="planned_time" name="planned_time" value="{$aField['planned_time']}">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="amount">Сумма</label>
                    <div class="controls">
                        <input type="text" id="amount" name="amount" value="{$aField['amount']}">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="creation_date">Дата создания</label>
                    <div class="controls">
                        <input type="date" id="creation_date" name="creation_date" value="{$aField['creation_date']}">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="link">Ссылка</label>
                    <div class="controls">
                        <input type="url" id="link" name="link" value="{$aField['link']}">
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn" id="fieldSave" data-url="">Сохранить</button>
                    <button type="button" class="btn" id="fieldDelete" data-url="{$sUrl}/delete">Удалить</button>
                </div>
            </form>
        </div>
    </div>
</div>

{include file="components/admin/templates/default/footer.tpl"}