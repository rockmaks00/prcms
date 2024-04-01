<div class="row-fluid">
    <label for="dateFilter">Фильтр по дате создания:</label>
    <input type="date" id="dateFilter" {if $aFilters['field_creation_date']}value="{$aFilters['field_creation_date']}"{/if}>
</div>
<div class="row-fluid" style="margin-bottom: 5px;">
    <button type="button" class="btn" id="applyFilter">Применить</button>
    <button type="button" class="btn" id="clearFilter">Сбросить</button>
</div>
<div class="row-fluid">
    <div class="span10">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Группа</th>
                    <th>Задача</th>
                    <th>Время факт.</th>
                    <th>Время план.</th>
                    <th>Сумма</th>
                    <th>Дата создания</th>
                    <th>Ссылка</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$aFields item="item"}
                    <tr {if $bEditable} data-id="{$item['field_id']}" name="editable" {/if}>
                        <td>{$item['field_id']}</td>
                        <td>{$item['field_group']}</td>
                        <td>{$item['field_task']}</td>
                        <td>{$item['field_spent_time']}</td>
                        <td>{$item['field_planned_time']}</td>
                        <td>{$item['field_amount']}</td>
                        <td>{$item['field_creation_date']}</td>
                        <td>
                            <a href="{$item['field_link']}">Ссылка</a>
                        </td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
</div>