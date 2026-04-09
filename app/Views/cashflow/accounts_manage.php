<?php
/**
 * @var string $controller_name
 * @var string $table_headers
 * @var array $config
 */
?>

<?= view('partial/header') ?>

<style>
.cashflow-page { padding: 20px 0; }
.cashflow-breadcrumb { padding: 15px 0; }
.cashflow-breadcrumb .breadcrumb { margin: 0; padding: 0; background: transparent; }
.cashflow-page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e9ecef;
}
.cashflow-page-header h1 { margin: 0; font-size: 28px; font-weight: 600; color: #2c3e50; }
.cashflow-toolbar-card {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
.cashflow-toolbar-card .btn { border-radius: 8px; font-weight: 500; transition: all 0.2s; }
.cashflow-toolbar-card .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
.cashflow-table-card {
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
            headers: <?= $table_headers ?>,
            pageSize: <?= $config['lines_per_page'] ?>,
            uniqueId: 'account_id'
        });
    });
</script>

<div class="cashflow-page">
    <div class="cashflow-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="<?= site_url() ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?= site_url('cashflow') ?>"><?= lang('Cashflow.entries') ?></a></li>
            <li class="active"><?= lang('Cashflow.manage_accounts') ?></li>
        </ol>
    </div>

    <div class="cashflow-page-header">
        <h1><span class="glyphicon glyphicon-briefcase" style="color: #667eea; margin-right: 10px;"></span><?= lang('Cashflow.manage_accounts') ?></h1>
    </div>

    <div class="cashflow-toolbar-card">
        <div class="btn-toolbar">
            <div class="pull-right btn-group">
                <button class="btn btn-default btn-sm" onclick="window.location='<?= site_url('cashflow') ?>'">
                    <span class="glyphicon glyphicon-arrow-left">&nbsp;</span><?= lang('Cashflow.back_to_entries') ?>
                </button>
                <button class="btn btn-info btn-sm modal-dlg" data-btn-submit="<?= lang('Common.submit') ?>" data-href="<?= site_url('cashflow_accounts/view') ?>" title="<?= lang('Cashflow.new_account') ?>">
                    <span class="glyphicon glyphicon-plus">&nbsp;</span><?= lang('Cashflow.new_account') ?>
                </button>
            </div>
        </div>
        <div class="btn-toolbar" style="margin-top: 15px;">
            <div class="pull-left btn-group">
                <button id="delete" class="btn btn-default btn-sm">
                    <span class="glyphicon glyphicon-trash">&nbsp;</span><?= lang('Cashflow.archive') ?>
                </button>
            </div>
        </div>
    </div>

    <div class="cashflow-table-card">
        <div id="table_holder">
            <table id="table"></table>
        </div>
    </div>
</div>

<?= view('partial/footer') ?>
