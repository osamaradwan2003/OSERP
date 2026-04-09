<?php
/**
 * @var int|null $project_id
 * @var string $start_date
 * @var string $end_date
 * @var array $projects
 * @var array|null $costs
 */
?>
<?= view('partial/header') ?>

<style>
.mfg-report-page { padding: 20px 0; }
.mfg-breadcrumb { padding: 15px 0; }
.mfg-breadcrumb .breadcrumb { margin: 0; padding: 0; background: transparent; }
.mfg-page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e9ecef;
}
.mfg-page-header h1 { margin: 0; font-size: 28px; font-weight: 600; color: #2c3e50; }
.mfg-report-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
.mfg-report-card .panel-heading {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    border-radius: 12px 12px 0 0;
    font-weight: 600;
    padding: 15px 20px;
}
.mfg-report-card .panel-body { padding: 20px; }
.mfg-report-form {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}
.mfg-report-form .form-group { margin-bottom: 15px; }
.mfg-report-form .form-control { border-radius: 6px; }
.mfg-table { margin-bottom: 0; }
.mfg-table thead th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    border: none;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.5px;
    padding: 12px 10px;
}
.mfg-table tbody tr:hover { background-color: #f8f9fa; }
.mfg-table tbody td { vertical-align: middle; padding: 12px; border-color: #e9ecef; }
.mfg-total-row { background: #e8f4fd !important; font-weight: 600; }
</style>

<div class="mfg-report-page">
    <div class="mfg-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="<?= site_url() ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?= site_url('manufacturing') ?>"><?= lang('Manufacturing.module') ?></a></li>
            <li><a href="<?= site_url('manufacturing/reports') ?>"><?= lang('Manufacturing.reports') ?></a></li>
            <li class="active"><?= lang('Manufacturing.project_costs_report') ?></li>
        </ol>
    </div>

    <div class="mfg-page-header">
        <h1><span class="glyphicon glyphicon-usd" style="color: #667eea; margin-right: 10px;"></span><?= lang('Manufacturing.project_costs_report') ?></h1>
    </div>

    <div class="mfg-report-card panel panel-default">
        <div class="panel-body">
            <form class="form-inline" method="get">
                <div class="mfg-report-form">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="project_id"><?= lang('Manufacturing.project') ?>:</label>
                                <?= form_dropdown('project_id',
                                    ['' => lang('Common.all')] + array_column($projects, 'project_name', 'project_id'),
                                    $project_id,
                                    ['class' => 'form-control', 'style' => 'width: 100%;']
                                ) ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="start_date"><?= lang('Common.start_date') ?>:</label>
                                <input type="text" name="start_date" value="<?= esc($start_date) ?>" class="form-control date-picker" style="width: 100%;">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="end_date"><?= lang('Common.end_date') ?>:</label>
                                <input type="text" name="end_date" value="<?= esc($end_date) ?>" class="form-control date-picker" style="width: 100%;">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <span class="glyphicon glyphicon-search"></span> <?= lang('Common.search') ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <?php if (!empty($costs)): ?>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th><?= lang('Manufacturing.cost_type') ?></th>
                        <th><?= lang('Manufacturing.description') ?></th>
                        <th class="text-right"><?= lang('Manufacturing.amount') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($costs as $cost): ?>
                    <tr>
                        <td><?= esc($cost['cost_type']) ?></td>
                        <td><?= esc($cost['description']) ?></td>
                        <td class="text-right"><?= to_currency($cost['amount']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr class="mfg-total-row">
                        <td colspan="2"><strong><?= lang('Manufacturing.total') ?></strong></td>
                        <td class="text-right"><strong><?= to_currency(array_sum(array_column($costs, 'amount'))) ?></strong></td>
                    </tr>
                </tbody>
            </table>
            <?php elseif ($project_id): ?>
            <div class="alert alert-info">
                <?= lang('Manufacturing.no_costs_found') ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('.date-picker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });
});
</script>

<?= view('partial/footer') ?>
