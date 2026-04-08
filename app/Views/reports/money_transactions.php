<?php
/**
 * @var string $title
 * @var string $subtitle
 * @var string $entity_name
 * @var string $summary_label
 * @var string $list_label
 * @var array $summary
 * @var string $balance_label
 * @var array $headers
 * @var array $sales_data
 * @var array $payments_data
 * @var array $details_headers
 * @var array $details_data
 * @var array $config
 */
?>

<?= view('partial/header') ?>

<div id="page_title"><?= esc($title) ?></div>
<div id="page_subtitle"><?= esc($subtitle) ?></div>
<?php if (!empty($entity_name)) { ?>
    <div id="page_subtitle"><?= esc($entity_name) ?></div>
<?php } ?>

<div id="report_summary">
    <div class="summary_row"><?= esc($summary_label) ?>: <?= to_currency($summary['total_sales'] ?? 0) ?></div>
    <div class="summary_row"><?= lang('Reports.total_payments') ?>: <?= to_currency($summary['total_payments'] ?? 0) ?></div>
    <div class="summary_row"><?= lang('Reports.net_balance') ?>: <?= to_currency(abs($summary['net_balance'] ?? 0)) ?> (<?= esc($balance_label) ?>)</div>
</div>

<div class="panel panel-primary" style="margin-top: 15px;">
    <div class="panel-heading">
        <h3 class="panel-title"><?= esc($list_label) ?></h3>
    </div>
    <div class="panel-body">
        <table id="sales_table"></table>
    </div>
</div>

<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title"><?= lang('Reports.payments') ?></h3>
    </div>
    <div class="panel-body">
        <table id="payments_table"></table>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        <?= view('partial/bootstrap_tables_locale') ?>
        var details_data = <?= json_encode($details_data ?? []) ?>;

        $('#sales_table').bootstrapTable({
            columns: <?= transform_headers($headers['sales'], true, false) ?>,
            pageSize: <?= (int) ($config['lines_per_page'] ?? 25) ?>,
            pagination: true,
            search: true,
            showColumns: true,
            showExport: true,
            exportDataType: 'all',
            exportTypes: ['json', 'xml', 'csv', 'txt', 'sql', 'excel', 'pdf'],
            data: <?= json_encode($sales_data) ?>,
            detailView: true,
            onExpandRow: function(index, row, $detail) {
                var rows = details_data[row.id] || [];
                $detail.html('<table></table>').find('table').bootstrapTable({
                    columns: <?= transform_headers($details_headers, true, false) ?>,
                    data: rows
                });
            }
        });

        $('#payments_table').bootstrapTable({
            columns: <?= transform_headers($headers['payments'], true, false) ?>,
            pageSize: <?= (int) ($config['lines_per_page'] ?? 25) ?>,
            pagination: true,
            search: true,
            showColumns: true,
            showExport: true,
            exportDataType: 'all',
            exportTypes: ['json', 'xml', 'csv', 'txt', 'sql', 'excel', 'pdf'],
            data: <?= json_encode($payments_data) ?>
        });
    });
</script>

<?= view('partial/footer') ?>






