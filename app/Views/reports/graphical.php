<?php
/**
 * @var string $title
 * @var string $subtitle
 * @var string $chart_type
 * @var array $summary_data_1
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
.reports-chart-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    padding: 20px;
    margin-bottom: 20px;
}
.reports-chart-card .ct-chart { min-height: 400px; }
.reports-toolbar-card {
    background: #fff;
    border-radius: 12px;
    padding: 15px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
.reports-toolbar-card .btn { border-radius: 8px; }
#chart_report_summary {
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
        <h1><span class="glyphicon glyphicon-stats" style="color: #667eea; margin-right: 10px;"></span><?= esc($title) ?></h1>
    </div>

    <div class="reports-chart-card">
        <div class="ct-chart ct-golden-section" id="chart1"></div>
    </div>

    <div class="reports-toolbar-card">
        <div class="pull-left form-inline" role="toolbar">
            <button id="toggleCostProfitButton" class="btn btn-default btn-sm print_hide">
                <?php echo lang('Reports.toggle_cost_and_profit'); ?>
            </button>
        </div>
    </div>

    <?= view($chart_type) ?>

    <div id="chart_report_summary">
        <?php foreach ($summary_data_1 as $name => $value) { ?>
            <div class="summary_row"><strong><?= lang("Reports.$name") ?></strong>: <?= esc(to_currency($value)) ?></div>
        <?php } ?>
    </div>
</div>

<script src="<?= base_url('js/hide_cost_profit.js') ?>"></script>

<?= view('partial/footer') ?>
