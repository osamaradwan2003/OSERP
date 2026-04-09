<?php
/**
 * @var array $projects
 */
?>
<?= view('partial/header') ?>

<style>
.mfg-reports-page { padding: 20px 0; }
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
.mfg-reports-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
.mfg-reports-card .panel-heading {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    border-radius: 12px 12px 0 0;
    font-weight: 600;
    padding: 15px 20px;
}
.mfg-reports-card .panel-body { padding: 0; }
.mfg-report-item {
    padding: 20px;
    border-bottom: 1px solid #e9ecef;
    transition: all 0.2s;
}
.mfg-report-item:last-child { border-bottom: none; }
.mfg-report-item:hover { background: #f8f9fa; }
.mfg-report-item h4 { margin-top: 0; color: #2c3e50; font-weight: 600; }
.mfg-report-item h4 .glyphicon { color: #667eea; margin-right: 10px; }
.mfg-report-item p { color: #6c757d; margin-bottom: 0; }
.mfg-report-link {
    display: inline-flex;
    align-items: center;
    padding: 8px 16px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s;
}
.mfg-report-link:hover {
    transform: translateX(5px);
    color: #fff;
}
</style>

<div class="mfg-reports-page">
    <div class="mfg-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="<?= site_url() ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?= site_url('manufacturing') ?>"><?= lang('Manufacturing.module') ?></a></li>
            <li class="active"><?= lang('Manufacturing.reports') ?></li>
        </ol>
    </div>

    <div class="mfg-page-header">
        <h1><span class="glyphicon glyphicon-file" style="color: #667eea; margin-right: 10px;"></span><?= lang('Manufacturing.reports') ?></h1>
    </div>

    <div class="mfg-reports-card panel panel-default">
        <div class="panel-body">
            <div class="mfg-report-item">
                <h4><span class="glyphicon glyphicon-usd"></span><?= lang('Manufacturing.project_costs_report') ?></h4>
                <p><?= lang('Manufacturing.project_costs_report_desc') ?></p>
                <a href="<?= site_url('manufacturing/reports/project-costs') ?>" class="mfg-report-link">
                    <span class="glyphicon glyphicon-arrow-right"></span> View Report
                </a>
            </div>
            <div class="mfg-report-item">
                <h4><span class="glyphicon glyphicon-tasks"></span><?= lang('Manufacturing.material_usage_report') ?></h4>
                <p><?= lang('Manufacturing.material_usage_report_desc') ?></p>
                <a href="<?= site_url('manufacturing/reports/material-usage') ?>" class="mfg-report-link">
                    <span class="glyphicon glyphicon-arrow-right"></span> View Report
                </a>
            </div>
            <div class="mfg-report-item">
                <h4><span class="glyphicon glyphicon-stats"></span><?= lang('Manufacturing.project_progress_report') ?></h4>
                <p><?= lang('Manufacturing.project_progress_report_desc') ?></p>
                <a href="<?= site_url('manufacturing/reports/project-progress') ?>" class="mfg-report-link">
                    <span class="glyphicon glyphicon-arrow-right"></span> View Report
                </a>
            </div>
            <div class="mfg-report-item">
                <h4><span class="glyphicon glyphicon-list-alt"></span><?= lang('Manufacturing.mrp_report') ?></h4>
                <p><?= lang('Manufacturing.mrp_report_desc') ?></p>
                <a href="<?= site_url('manufacturing/reports/mrp') ?>" class="mfg-report-link">
                    <span class="glyphicon glyphicon-arrow-right"></span> View Report
                </a>
            </div>
            <div class="mfg-report-item">
                <h4><span class="glyphicon glyphicon-warning-sign"></span><?= lang('Manufacturing.cost_variance_report') ?></h4>
                <p><?= lang('Manufacturing.cost_variance_report_desc') ?></p>
                <a href="<?= site_url('manufacturing/reports/cost-variance') ?>" class="mfg-report-link">
                    <span class="glyphicon glyphicon-arrow-right"></span> View Report
                </a>
            </div>
        </div>
    </div>
</div>

<?= view('partial/footer') ?>
