<?php
/**
 * @var string $title
 * @var string $subtitle
 * @var array $summary_data
 * @var array $headers
 * @var array $data
 * @var array $config
 */
?>

<?= view('partial/header') ?>

<style>
.reports-page { padding: 20px 0; }
.reports-breadcrumb { padding: 15px 0; }
.reports-breadcrumb .breadcrumb { margin: 0; padding: 0; background: transparent; }
.reports-page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e9ecef;
}
.reports-page-header h1 { margin: 0; font-size: 28px; font-weight: 600; color: #2c3e50; }
.reports-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
.reports-card .panel-body { padding: 20px; }
.reports-toolbar-card {
    background: #fff;
    border-radius: 12px;
    padding: 15px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
.reports-toolbar-card .btn { border-radius: 8px; }
.reports-table-card {
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
#report_summary {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    margin-top: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
.summary_row {
    padding: 10px 15px;
    border-bottom: 1px solid #e9ecef;
    font-weight: 500;
}
.summary_row:last-child { border-bottom: none; }
.summary_row strong { color: #667eea; }
</style>

<script type="text/javascript">
    dialog_support.init("a.modal-dlg");
</script>

<div class="reports-page">
    <div class="reports-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="<?= site_url() ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?= site_url('reports') ?>"><?= lang('Reports.reports') ?></a></li>
            <li class="active"><?= esc($title) ?></li>
        </ol>
    </div>

    <div class="reports-page-header">
        <h1><span class="glyphicon glyphicon-list-alt" style="color: #667eea; margin-right: 10px;"></span><?= esc($title) ?></h1>
    </div>

    <div class="reports-toolbar-card">
        <div class="pull-left form-inline" role="toolbar">
            <button id="toggleCostProfitButton" class="btn btn-default btn-sm print_hide">
                <?php echo lang('Reports.toggle_cost_and_profit'); ?>
            </button>
        </div>
    </div>

    <div class="reports-table-card">
        <div id="table_holder">
            <table id="table" class="table table-striped table-bordered"></table>
        </div>
    </div>

    <div id="report_summary">
        <?php
        foreach ($summary_data as $name => $value) {
            if ($name == "total_quantity") {
                ?>
                <div class="summary_row"><strong><?= lang("Reports.$name") ?></strong>: <?= esc($value) ?></div>
            <?php } else { ?>
                <div class="summary_row"><strong><?= lang("Reports.$name") ?></strong>: <?= to_currency($value) ?></div>
                <?php
            }
        }
        ?>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        <?= view('partial/bootstrap_tables_locale') ?>
        <?= view('partial/visibility_js') ?>

        $('#table')
            .addClass("table-striped")
            .addClass("table-bordered")
            .bootstrapTable({
                columns: applyColumnVisibility(<?= transform_headers(esc($headers), true, false) ?>),
                stickyHeader: true,
                stickyHeaderOffsetLeft: $('#table').offset().left + 'px',
                stickyHeaderOffsetRight: $('#table').offset().right + 'px',
                pageSize: <?= $config['lines_per_page'] ?>,
                sortable: true,
                showExport: true,
                exportDataType: 'all',
                exportTypes: ['json', 'xml', 'csv', 'txt', 'sql', 'excel', 'pdf'],
                pagination: true,
                showColumns: true,
                data: <?= json_encode($data) ?>,
                iconSize: 'sm',
                paginationVAlign: 'bottom',
                escape: true,
                search: true
            });
    });
</script>

<?= view('partial/footer') ?>
