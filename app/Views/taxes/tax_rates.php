<?php
/**
 * @var string $controller_name
 * @var string $tax_rate_table_headers
 * @var array $config
 */
?>

<style>
.tax-rates-section { }
.tax-rates-toolbar {
    background: #fff;
    border-radius: 12px;
    padding: 15px;
    margin-bottom: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
.tax-rates-toolbar .btn { border-radius: 8px; }
.tax-rates-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
#table_holder { padding: 15px; }
#table_holder .bootstrap-table .table { border-radius: 8px; overflow: hidden; }
#table_holder .table thead th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    border: none;
}
</style>

<script type="text/javascript">
    $(document).ready(function() {
        <?= view('partial/bootstrap_tables_locale') ?>
        table_support.init({
            resource: '<?= esc($controller_name) ?>',
            headers: <?= $tax_rate_table_headers ?>,
            pageSize: <?= $config['lines_per_page'] ?>,
            uniqueId: 'tax_rate_id'
        });
    });
</script>

<div class="tax-rates-section">
    <div class="tax-rates-toolbar">
        <div class="btn-toolbar">
            <div class="pull-left">
                <button class="btn btn-info btn-sm modal-dlg" data-btn-submit="<?= lang('Common.submit') ?>" data-href="<?= esc("$controller_name/view") ?>" title="<?= lang(ucfirst($controller_name) . ".new") ?>">
                    <span class="glyphicon glyphicon-plus">&nbsp;</span><?= lang(ucfirst($controller_name) . ".new") ?>
                </button>
                <button id="delete" class="btn btn-default btn-sm">
                    <span class="glyphicon glyphicon-trash">&nbsp;</span><?= lang('Common.delete') ?>
                </button>
            </div>
        </div>
    </div>

    <div class="tax-rates-card">
        <div id="table_holder">
            <table id="table"></table>
        </div>
    </div>
</div>
