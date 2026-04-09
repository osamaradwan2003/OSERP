<?php
/**
 * @var int|null $project_id
 * @var array $projects
 * @var array|null $mrp
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
.mfg-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}
.mfg-badge-success { background: #d4edda; color: #155724; }
.mfg-badge-warning { background: #fff3cd; color: #856404; }
</style>

<div class="mfg-report-page">
    <div class="mfg-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="<?= site_url() ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?= site_url('manufacturing') ?>"><?= lang('Manufacturing.module') ?></a></li>
            <li><a href="<?= site_url('manufacturing/reports') ?>"><?= lang('Manufacturing.reports') ?></a></li>
            <li class="active"><?= lang('Manufacturing.mrp_report') ?></li>
        </ol>
    </div>

    <div class="mfg-page-header">
        <h1><span class="glyphicon glyphicon-list-alt" style="color: #667eea; margin-right: 10px;"></span><?= lang('Manufacturing.mrp_report') ?></h1>
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

            <?php if (!empty($mrp)): ?>
            <h4 style="margin-bottom: 15px;"><?= lang('Manufacturing.material_requirements') ?></h4>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th><?= lang('Manufacturing.item') ?></th>
                        <th class="text-right"><?= lang('Manufacturing.required_quantity') ?></th>
                        <th class="text-right"><?= lang('Manufacturing.available_quantity') ?></th>
                        <th class="text-right"><?= lang('Manufacturing.shortage') ?></th>
                        <th class="text-right"><?= lang('Manufacturing.estimated_cost') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($mrp as $item): ?>
                    <tr class="<?= $item['shortage'] > 0 ? 'warning' : '' ?>">
                        <td><?= esc($item['item_name']) ?></td>
                        <td class="text-right"><?= to_quantity_decimals($item['required_quantity']) ?></td>
                        <td class="text-right"><?= to_quantity_decimals($item['available_quantity']) ?></td>
                        <td class="text-right">
                            <?php if ($item['shortage'] > 0): ?>
                            <span class="mfg-badge mfg-badge-warning"><?= to_quantity_decimals($item['shortage']) ?></span>
                            <?php else: ?>
                            <span class="mfg-badge mfg-badge-success">0</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-right"><?= to_currency($item['estimated_cost']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php elseif ($project_id): ?>
            <div class="alert alert-info">
                <?= lang('Manufacturing.no_mrp_data') ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= view('partial/footer') ?>
