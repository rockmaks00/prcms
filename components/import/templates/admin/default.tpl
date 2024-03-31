{include file="components/admin/templates/default/header.tpl"}

<div class="container">
    <div class="row-fluid">
        <div class="span10">
            <input type="file" name="uploadCsv" data-set="{$aTemplate.node_url}upload/">
            <button type="button" class="btn" id="uploadCsv">Импорт CSV</button>
            <hr>
        </div>
    </div>
    {include file="components/import/templates/table.tpl"}
</div>

{include file="components/admin/templates/default/footer.tpl"}