<div class="row-fluid">
    <label for="dateFilter">Фильтр по дате создания:</label>
    <input type="date" id="dateFilter" {if $aFilters['creation_date']}value="{$aFilters['creation_date']}"{/if}>
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
                    <tr>
                        <td>{$item['id']}</td>
                        <td>{$item['group']}</td>
                        <td>{$item['task']}</td>
                        <td>{$item['spent_time']}</td>
                        <td>{$item['planned_time']}</td>
                        <td>{$item['amount']}</td>
                        <td>{$item['creation_date']}</td>
                        <td>
                            <a href="{$item['link']}">Ссылка</a>
                        </td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
</div>