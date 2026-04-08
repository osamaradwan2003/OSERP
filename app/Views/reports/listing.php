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

<script type="text/javascript">
    dialog_support.init("a.modal-dlg");
</script>

<?php
if (isset($error)) {
    echo '<div class="alert alert-dismissible alert-danger">' . esc($error) . '</div>';
}
?>

<div class="row">
    <div class="col-md-4">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><span class="glyphicon glyphicon-stats">&nbsp;</span><?= lang('Reports.graphical_reports') ?></h3>
            </div>
            <div class="list-group">
                <?php foreach ($permission_ids as $permission_id) {
                    if (can_show_report($permission_id, ['inventory', 'receiving', 'cashflow', 'financial_overview'])) {
                        $link = get_report_link($permission_id, 'graphical_summary');
                ?>
                        <a class="list-group-item" href="<?= $link['path'] ?>"><?= esc($link['label']) ?></a>
                <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><span class="glyphicon glyphicon-list">&nbsp;</span><?= lang('Reports.summary_reports') ?></h3>
            </div>
            <div class="list-group">
                <?php foreach ($permission_ids as $permission_id) {
                    if (can_show_report($permission_id, ['inventory', 'receiving', 'cashflow', 'financial_overview'])) {
                        $link = get_report_link($permission_id, 'summary');
                ?>
                        <a class="list-group-item" href="<?= $link['path'] ?>"><?= esc($link['label']) ?></a>
                <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><span class="glyphicon glyphicon-list-alt">&nbsp;</span><?= lang('Reports.detailed_reports') ?></h3>
            </div>
            <div class="list-group">
                <?php foreach ($detailed_reports as $report_name => $prefix) {
                    if (in_array($report_name, $permission_ids, true)) {
                        $link = get_report_link($report_name, $prefix);
                ?>
                        <a class="list-group-item" href="<?= $link['path'] ?>"><?= esc($link['label']) ?></a>
                <?php
                    }
                }
                ?>
                <?php if (in_array('reports_money_transactions', $permission_ids, true)) { ?>
                    <a class="list-group-item" href="<?= site_url('reports/moneytransactions') ?>"><?= esc(lang('Reports.money_transactions')) ?></a>
                <?php } ?>
            </div>
        </div>

        <?php if (in_array('reports_inventory', $permission_ids, true)) { ?>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><span class="glyphicon glyphicon-book">&nbsp;</span><?= lang('Reports.inventory_reports') ?></h3>
                </div>
                <div class="list-group">
                    <?php
                    $inventory_low_report = get_report_link('reports_inventory_low');
                    $inventory_items_flow_report = get_report_link('reports_inventory_items_flow');
                    $inventory_summary_report = get_report_link('reports_inventory_summary');
                    ?>
                    <a class="list-group-item" href="<?= $inventory_low_report['path'] ?>"><?= esc($inventory_low_report['label']) ?></a>
                    <a class="list-group-item" href="<?= $inventory_items_flow_report['path'] ?>"><?= esc($inventory_items_flow_report['label']) ?></a>
                    <a class="list-group-item" href="<?= $inventory_summary_report['path'] ?>"><?= esc($inventory_summary_report['label']) ?></a>
                </div>
            </div>
        <?php } ?>

        <?php if (in_array('reports_cashflow', $permission_ids, true) || in_array('reports_financial_overview', $permission_ids, true)) { ?>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><span class="glyphicon glyphicon-piggy-bank">&nbsp;</span><?= lang('Reports.cashflow_reports') ?></h3>
                </div>
                <div class="list-group">
                    <?php if (in_array('reports_cashflow', $permission_ids, true)) { ?>
                        <a class="list-group-item" href="<?= get_report_link('reports_cashflow_ledger')['path'] ?>"><?= esc(get_report_link('reports_cashflow_ledger')['label']) ?></a>
                        <a class="list-group-item" href="<?= get_report_link('reports_cashflow_summary')['path'] ?>"><?= esc(get_report_link('reports_cashflow_summary')['label']) ?></a>
                        <a class="list-group-item" href="<?= get_report_link('reports_cashflow_account_balance')['path'] ?>"><?= esc(get_report_link('reports_cashflow_account_balance')['label']) ?></a>
                    <?php } ?>
                    <?php if (in_array('reports_financial_overview', $permission_ids, true)) { ?>
                        <a class="list-group-item" href="<?= get_report_link('reports_financial_overview')['path'] ?>"><?= esc(get_report_link('reports_financial_overview')['label']) ?></a>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<?= view('partial/footer') ?>


