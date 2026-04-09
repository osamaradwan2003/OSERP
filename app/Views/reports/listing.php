<?php
/**
 * @var int   $person_id
 * @var array $permission_ids
 * @var array $grants
 */

$detailed_reports = [
    'reports_sales'      => 'detailed',
    'reports_receivings' => 'detailed',
    'reports_customers'  => 'specific',
    'reports_discounts'  => 'specific',
    'reports_employees'  => 'specific',
    'reports_suppliers'  => 'specific',
];
?>

<?= view('partial/header') ?>

<style>
.reports-listing-page { padding: 20px 0; }
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
.reports-category-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    transition: transform 0.2s, box-shadow 0.2s;
    margin-bottom: 20px;
}
.reports-category-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
}
.reports-category-card .panel-heading {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    border-radius: 12px 12px 0 0;
    font-weight: 600;
    padding: 15px 20px;
}
.reports-category-card .panel-heading .glyphicon { margin-right: 8px; }
.reports-category-card .list-group { margin: 0; border-radius: 0; }
.reports-category-card .list-group-item {
    border-left: none;
    border-right: none;
    padding: 12px 20px;
    transition: background 0.2s;
}
.reports-category-card .list-group-item:first-child { border-top: none; }
.reports-category-card .list-group-item:last-child { border-bottom: none; }
.reports-category-card .list-group-item:hover { background: #f8f9fa; }
.reports-category-card .list-group-item .glyphicon { margin-right: 10px; color: #667eea; }
.reports-icon-box {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    margin-bottom: 15px;
}
.reports-stat-number {
    font-size: 36px;
    font-weight: 700;
    line-height: 1;
}
</style>

<script type="text/javascript">
    dialog_support.init("a.modal-dlg");
</script>

<?php
if (isset($error)) {
    echo '<div class="alert alert-dismissible alert-danger">' . esc($error) . '</div>';
}
?>

<div class="reports-listing-page">
    <div class="reports-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="<?= site_url() ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li class="active"><?= lang('Reports.reports') ?></li>
        </ol>
    </div>

    <div class="reports-page-header">
        <h1><span class="glyphicon glyphicon-stats" style="color: #667eea; margin-right: 10px;"></span><?= lang('Reports.reports') ?></h1>
    </div>

    <div class="row">
        <div class="col-md-4 col-sm-6">
            <div class="reports-category-card panel panel-primary">
                <div class="panel-heading">
                    <span class="glyphicon glyphicon-stats"></span><?= lang('Reports.graphical_reports') ?>
                </div>
                <div class="list-group">
                    <?php foreach ($permission_ids as $permission_id) {
                        if (can_show_report($permission_id, ['inventory', 'receiving', 'cashflow', 'financial_overview'])) {
                            $link = get_report_link($permission_id, 'graphical_summary');
                    ?>
                            <a class="list-group-item" href="<?= $link['path'] ?>"><span class="glyphicon glyphicon-chevron-right"></span><?= esc($link['label']) ?></a>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-6">
            <div class="reports-category-card panel panel-primary">
                <div class="panel-heading">
                    <span class="glyphicon glyphicon-list"></span><?= lang('Reports.summary_reports') ?>
                </div>
                <div class="list-group">
                    <?php foreach ($permission_ids as $permission_id) {
                        if (can_show_report($permission_id, ['inventory', 'receiving', 'cashflow', 'financial_overview'])) {
                            $link = get_report_link($permission_id, 'summary');
                    ?>
                            <a class="list-group-item" href="<?= $link['path'] ?>"><span class="glyphicon glyphicon-chevron-right"></span><?= esc($link['label']) ?></a>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-6">
            <div class="reports-category-card panel panel-primary">
                <div class="panel-heading">
                    <span class="glyphicon glyphicon-list-alt"></span><?= lang('Reports.detailed_reports') ?>
                </div>
                <div class="list-group">
                    <?php foreach ($detailed_reports as $report_name => $prefix) {
                        if (in_array($report_name, $permission_ids, true)) {
                            $link = get_report_link($report_name, $prefix);
                    ?>
                            <a class="list-group-item" href="<?= $link['path'] ?>"><span class="glyphicon glyphicon-chevron-right"></span><?= esc($link['label']) ?></a>
                    <?php
                        }
                    }
                    ?>
                    <?php if (in_array('reports_money_transactions', $permission_ids, true)) { ?>
                        <a class="list-group-item" href="<?= site_url('reports/moneytransactions') ?>"><span class="glyphicon glyphicon-chevron-right"></span><?= esc(lang('Reports.money_transactions')) ?></a>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-sm-6">
            <?php if (in_array('reports_inventory', $permission_ids, true)) { ?>
                <div class="reports-category-card panel panel-primary">
                    <div class="panel-heading">
                        <span class="glyphicon glyphicon-book"></span><?= lang('Reports.inventory_reports') ?>
                    </div>
                    <div class="list-group">
                        <?php
                        $inventory_low_report = get_report_link('reports_inventory_low');
                        $inventory_items_flow_report = get_report_link('reports_inventory_items_flow');
                        $inventory_summary_report = get_report_link('reports_inventory_summary');
                        ?>
                        <a class="list-group-item" href="<?= $inventory_low_report['path'] ?>"><span class="glyphicon glyphicon-chevron-right"></span><?= esc($inventory_low_report['label']) ?></a>
                        <a class="list-group-item" href="<?= $inventory_items_flow_report['path'] ?>"><span class="glyphicon glyphicon-chevron-right"></span><?= esc($inventory_items_flow_report['label']) ?></a>
                        <a class="list-group-item" href="<?= $inventory_summary_report['path'] ?>"><span class="glyphicon glyphicon-chevron-right"></span><?= esc($inventory_summary_report['label']) ?></a>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="col-md-6 col-sm-6">
            <?php if (in_array('reports_cashflow', $permission_ids, true) || in_array('reports_financial_overview', $permission_ids, true)) { ?>
                <div class="reports-category-card panel panel-primary">
                    <div class="panel-heading">
                        <span class="glyphicon glyphicon-piggy-bank"></span><?= lang('Reports.cashflow_reports') ?>
                    </div>
                    <div class="list-group">
                        <?php if (in_array('reports_cashflow', $permission_ids, true)) { ?>
                            <a class="list-group-item" href="<?= get_report_link('reports_cashflow_ledger')['path'] ?>"><span class="glyphicon glyphicon-chevron-right"></span><?= esc(get_report_link('reports_cashflow_ledger')['label']) ?></a>
                            <a class="list-group-item" href="<?= get_report_link('reports_cashflow_summary')['path'] ?>"><span class="glyphicon glyphicon-chevron-right"></span><?= esc(get_report_link('reports_cashflow_summary')['label']) ?></a>
                            <a class="list-group-item" href="<?= get_report_link('reports_cashflow_account_balance')['path'] ?>"><span class="glyphicon glyphicon-chevron-right"></span><?= esc(get_report_link('reports_cashflow_account_balance')['label']) ?></a>
                        <?php } ?>
                        <?php if (in_array('reports_financial_overview', $permission_ids, true)) { ?>
                            <a class="list-group-item" href="<?= get_report_link('reports_financial_overview')['path'] ?>"><span class="glyphicon glyphicon-chevron-right"></span><?= esc(get_report_link('reports_financial_overview')['label']) ?></a>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<?= view('partial/footer') ?>
