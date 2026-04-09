<?php
/**
 * @var int|null $project_id
 * @var array $projects
 * @var array|null $variance
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
.mfg-summary-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    margin-bottom: 20px;
}
.mfg-summary-card .panel-heading {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    border-radius: 12px 12px 0 0;
    font-weight: 600;
    padding: 15px 20px;
}
.mfg-summary-card .panel-body { padding: 0; }
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
.mfg-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    margin-left: 10px;
}
.mfg-badge-success { background: #d4edda; color: #155724; }
.mfg-badge-danger { background: #f8d7da; color: #721c24; }
</style>

<div class="mfg-report-page">
    <div class="mfg-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="<?= site_url() ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?= site_url('manufacturing') ?>"><?= lang('Manufacturing.module') ?></a></li>
            <li><a href="<?= site_url('manufacturing/reports') ?>"><?= lang('Manufacturing.reports') ?></a></li>
            <li class="active"><?= lang('Manufacturing.cost_variance_report') ?></li>
        </ol>
    </div>

    <div class="mfg-page-header">
        <h1><span class="glyphicon glyphicon-warning-sign" style="color: #667eea; margin-right: 10px;"></span><?= lang('Manufacturing.cost_variance_report') ?></h1>
    </div>

    <div class="mfg-report-card panel panel-default">
        <div class="panel-body">
            <form class="form-inline" method="get">
                <div class="mfg-report-form">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="project_id"><?= lang('Manufacturing.project') ?>:</label>
                                <?= form_dropdown('project_id',
                                    ['' => lang('Common.select')] + array_column($projects, 'project_name', 'project_id'),
                                    $project_id,
                                    ['class' => 'form-control', 'style' => 'width: 100%;']
                                ) ?>
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

            <?php if (!empty($variance)): ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="mfg-summary-card panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title"><?= lang('Manufacturing.cost_summary') ?></h4>
                        </div>
                        <div class="panel-body">
                            <table class="table table-bordered" style="margin-bottom: 0;">
                                <tr>
                                    <td><strong><?= lang('Manufacturing.budgeted_cost') ?></strong></td>
                                    <td class="text-right"><?= to_currency($variance['budgeted_cost']) ?></td>
                                </tr>
                                <tr>
                                    <td><strong><?= lang('Manufacturing.actual_cost') ?></strong></td>
                                    <td class="text-right"><?= to_currency($variance['actual_cost']) ?></td>
                                </tr>
                                <tr class="<?= $variance['variance'] > 0 ? 'danger' : 'success' ?>">
                                    <td><strong><?= lang('Manufacturing.variance') ?></strong></td>
                                    <td class="text-right">
                                        <strong><?= to_currency($variance['variance']) ?></strong>
                                        <?php if ($variance['variance'] > 0): ?>
                                        <span class="mfg-badge mfg-badge-danger"><?= lang('Manufacturing.over_budget') ?></span>
                                        <?php else: ?>
                                        <span class="mfg-badge mfg-badge-success"><?= lang('Manufacturing.under_budget') ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mfg-summary-card panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title"><?= lang('Manufacturing.cost_breakdown') ?></h4>
                        </div>
                        <div class="panel-body">
                            <table class="table table-bordered" style="margin-bottom: 0;">
                                <thead>
                                    <tr>
                                        <th><?= lang('Manufacturing.cost_type') ?></th>
                                        <th class="text-right"><?= lang('Manufacturing.budgeted') ?></th>
                                        <th class="text-right"><?= lang('Manufacturing.actual') ?></th>
                                        <th class="text-right"><?= lang('Manufacturing.variance') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($variance['breakdown'] as $item): ?>
                                    <tr class="<?= $item['variance'] > 0 ? 'warning' : '' ?>">
                                        <td><?= esc($item['cost_type']) ?></td>
                                        <td class="text-right"><?= to_currency($item['budgeted']) ?></td>
                                        <td class="text-right"><?= to_currency($item['actual']) ?></td>
                                        <td class="text-right"><?= to_currency($item['variance']) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php elseif ($project_id): ?>
            <div class="alert alert-info">
                <?= lang('Manufacturing.no_variance_data') ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= view('partial/footer') ?>
