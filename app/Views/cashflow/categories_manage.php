<?php
/**
 * @var string $controller_name
 * @var string $table_headers
 * @var array $config
 */
?>

<?= view('partial/header') ?>

<script type="text/javascript">
    $(document).ready(function() {
        <?= view('partial/bootstrap_tables_locale') ?>

        table_support.init({
            resource: '<?= esc($controller_name) ?>',
            headers: <?= $table_headers ?>,
            pageSize: <?= $config['lines_per_page'] ?>,
            uniqueId: 'category_id'
        });
    });
</script>

<div id="title_bar" class="btn-toolbar">
    <button class="btn btn-default btn-sm pull-right" onclick="window.location='<?= site_url('cashflow_category_types') ?>'">
        <span class="glyphicon glyphicon-list-alt">&nbsp;</span><?= lang('Cashflow.manage_category_types') ?>
    </button>
    <button class="btn btn-default btn-sm pull-right" onclick="window.location='<?= site_url('cashflow') ?>'">
        <span class="glyphicon glyphicon-arrow-left">&nbsp;</span><?= lang('Cashflow.back_to_entries') ?>
    </button>
    <button class="btn btn-info btn-sm pull-right modal-dlg" data-btn-submit="<?= lang('Common.submit') ?>" data-href="<?= site_url('cashflow_categories/view') ?>" title="<?= lang('Cashflow.new_category') ?>">
        <span class="glyphicon glyphicon-plus">&nbsp;</span><?= lang('Cashflow.new_category') ?>
    </button>
</div>

<div id="toolbar">
    <div class="pull-left form-inline">
        <button id="delete" class="btn btn-default btn-sm">
            <span class="glyphicon glyphicon-trash">&nbsp;</span><?= lang('Cashflow.archive') ?>
        </button>
    </div>
</div>

<div id="table_holder">
    <table id="table"></table>
</div>

<?= view('partial/footer') ?>

