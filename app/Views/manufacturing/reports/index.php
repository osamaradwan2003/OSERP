<?php
/**
 * @var array $projects
 */
?>
<?= view('partial/header') ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="glyphicon glyphicon-file"></i>
                    <?= lang('Manufacturing.reports') ?>
                </h3>
            </div>
            <div class="panel-body">
                <div class="list-group">
                    <a href="<?= site_url('manufacturing/reports/project-costs') ?>" class="list-group-item">
                        <h4 class="list-group-item-heading">
                            <i class="glyphicon glyphicon-usd"></i> <?= lang('Manufacturing.project_costs_report') ?>
                        </h4>
                        <p class="list-group-item-text"><?= lang('Manufacturing.project_costs_report_desc') ?></p>
                    </a>
                    <a href="<?= site_url('manufacturing/reports/material-usage') ?>" class="list-group-item">
                        <h4 class="list-group-item-heading">
                            <i class="glyphicon glyphicon-tasks"></i> <?= lang('Manufacturing.material_usage_report') ?>
                        </h4>
                        <p class="list-group-item-text"><?= lang('Manufacturing.material_usage_report_desc') ?></p>
                    </a>
                    <a href="<?= site_url('manufacturing/reports/project-progress') ?>" class="list-group-item">
                        <h4 class="list-group-item-heading">
                            <i class="glyphicon glyphicon-stats"></i> <?= lang('Manufacturing.project_progress_report') ?>
                        </h4>
                        <p class="list-group-item-text"><?= lang('Manufacturing.project_progress_report_desc') ?></p>
                    </a>
                    <a href="<?= site_url('manufacturing/reports/mrp') ?>" class="list-group-item">
                        <h4 class="list-group-item-heading">
                            <i class="glyphicon glyphicon-list-alt"></i> <?= lang('Manufacturing.mrp_report') ?>
                        </h4>
                        <p class="list-group-item-text"><?= lang('Manufacturing.mrp_report_desc') ?></p>
                    </a>
                    <a href="<?= site_url('manufacturing/reports/cost-variance') ?>" class="list-group-item">
                        <h4 class="list-group-item-heading">
                            <i class="glyphicon glyphicon-warning-sign"></i> <?= lang('Manufacturing.cost_variance_report') ?>
                        </h4>
                        <p class="list-group-item-text"><?= lang('Manufacturing.cost_variance_report_desc') ?></p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= view('partial/footer') ?>
