<?php
/**
 * @var string $title
 * @var string $subtitle
 * @var array $overall_summary_data
 * @var array $details_data
 * @var array $headers
 * @var array $summary_data
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
        <?php foreach ($overall_summary_data as $name => $value) { ?>
            <div class="summary_row"><strong><?= lang("Reports.$name") ?></strong>: <?= esc(to_currency($value)) ?></div>
        <?php } ?>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        <?= view('partial/bootstrap_tables_locale') ?>

        var details_data = <?= json_encode(esc($details_data)) ?>;
        <?php if ($config['customer_reward_enable'] && !empty($details_data_rewards)) { ?>
            var details_data_rewards = <?= json_encode(esc($details_data_rewards)) ?>;
        <?php } ?>
        <?= view('partial/visibility_js') ?>

        var init_dialog = function () {
            <?php if (isset($editable)) { ?>
                table_support.submit_handler('<?= esc(site_url("reports/get_detailed_$editable" . '_row')) ?>');
                dialog_support.init("a.modal-dlg");
            <?php } ?>
        };

        $('#table')
            .addClass("table-striped")
            .addClass("table-bordered")
            .bootstrapTable({
                columns: applyColumnVisibility(<?= transform_headers(esc($headers['summary']), true) ?>),
                stickyHeader: true,
                stickyHeaderOffsetLeft: $('#table').offset().left + 'px',
                stickyHeaderOffsetRight: $('#table').offset().right + 'px',
                pageSize: <?= $config['lines_per_page'] ?>,
                pagination: true,
                sortable: true,
                showColumns: true,
                uniqueId: 'id',
                showExport: true,
                exportDataType: 'all',
                exportTypes: ['json', 'xml', 'csv', 'txt', 'sql', 'excel', 'pdf'],
                data: <?= json_encode($summary_data) ?>,
                iconSize: 'sm',
                paginationVAlign: 'bottom',
                detailView: true,
                escape: true,
                search: true,
                onPageChange: init_dialog,
                onPostBody: function () {
                    dialog_support.init("a.modal-dlg");
                },
                onExpandRow: function (index, row, $detail) {
                    $detail.html('<table></table>').find("table").bootstrapTable({
                        columns: <?= transform_headers_readonly(esc($headers['details'])) ?>,
                        data: details_data[(!isNaN(row.id) && row.id) || $(row[0] || row.id).text().replace(
                            /(POS|RECV)\s*/g, '')]
                    });

                    <?php if ($config['customer_reward_enable'] && !empty($details_data_rewards)) { ?>
                        $detail.append('<table></table>').find("table").bootstrapTable({
                            columns: <?= transform_headers_readonly(esc($headers['details_rewards'])) ?>,
                            data: details_data_rewards[(!isNaN(row.id) && row.id) || $(row[0] || row.id).text().replace(
                                /(POS|RECV)\s*/g, '')]
                        });
                    <?php } ?>
                }
            });

        init_dialog();
    });
</script>

<?= view('partial/footer') ?>
