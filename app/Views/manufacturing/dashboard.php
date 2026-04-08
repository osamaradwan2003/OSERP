<?php
/**
 * Manufacturing Dashboard View
 */
?>
<?= view('partial/header') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h2><?= lang('Manufacturing.dashboard') ?></h2>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="glyphicon glyphicon-list-alt fa-3x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?= $stats['total_projects'] ?? 0 ?></div>
                            <div><?= lang('Manufacturing.total_projects') ?></div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <span class="pull-right">
                        <a href="<?= site_url('manufacturing/projects') ?>">
                            <?= lang('Manufacturing.projects') ?> <i class="glyphicon glyphicon-circle-arrow-right"></i>
                        </a>
                    </span>
                    <span class="clearfix"></span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="panel panel-green">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="glyphicon glyphicon-cog fa-3x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?= $stats['projects_by_status']['in_progress'] ?? 0 ?></div>
                            <div><?= lang('Manufacturing.projects_in_progress') ?></div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <span class="pull-right">
                        <a href="<?= site_url('manufacturing/projects?status=in_progress') ?>">
                            <?= lang('Common.view') ?> <i class="glyphicon glyphicon-circle-arrow-right"></i>
                        </a>
                    </span>
                    <span class="clearfix"></span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="panel panel-yellow">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="glyphicon glyphicon-transfer fa-3x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?= $stats['pending_transfers'] ?? 0 ?></div>
                            <div><?= lang('Manufacturing.pending_transfers') ?></div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <span class="pull-right">
                        <a href="<?= site_url('manufacturing/transfers?status=draft') ?>">
                            <?= lang('Manufacturing.transfers') ?> <i class="glyphicon glyphicon-circle-arrow-right"></i>
                        </a>
                    </span>
                    <span class="clearfix"></span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="glyphicon glyphicon-usd fa-3x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?= to_currency($stats['monthly_costs'] ?? 0) ?></div>
                            <div><?= lang('Manufacturing.monthly_costs') ?></div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <span class="pull-right">
                        <a href="<?= site_url('manufacturing/reports') ?>">
                            <?= lang('Manufacturing.reports') ?> <i class="glyphicon glyphicon-circle-arrow-right"></i>
                        </a>
                    </span>
                    <span class="clearfix"></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Project Status Overview -->
    <div class="row">
        <div class="col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="glyphicon glyphicon-stats"></i>
                        <?= lang('Manufacturing.projects') ?> - <?= lang('Manufacturing.project_status') ?>
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th><?= lang('Manufacturing.project_status') ?></th>
                                    <th class="text-right"><?= lang('Common.count') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="label label-default"><?= lang('Manufacturing.status_planned') ?></span></td>
                                    <td class="text-right"><?= $stats['projects_by_status']['planned'] ?? 0 ?></td>
                                </tr>
                                <tr>
                                    <td><span class="label label-primary"><?= lang('Manufacturing.status_in_progress') ?></span></td>
                                    <td class="text-right"><?= $stats['projects_by_status']['in_progress'] ?? 0 ?></td>
                                </tr>
                                <tr>
                                    <td><span class="label label-warning"><?= lang('Manufacturing.status_on_hold') ?></span></td>
                                    <td class="text-right"><?= $stats['projects_by_status']['on_hold'] ?? 0 ?></td>
                                </tr>
                                <tr>
                                    <td><span class="label label-success"><?= lang('Manufacturing.status_completed') ?></span></td>
                                    <td class="text-right"><?= $stats['projects_by_status']['completed'] ?? 0 ?></td>
                                </tr>
                                <tr>
                                    <td><span class="label label-info"><?= lang('Manufacturing.status_delivered') ?></span></td>
                                    <td class="text-right"><?= $stats['projects_by_status']['delivered'] ?? 0 ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="glyphicon glyphicon-tasks"></i>
                        <?= lang('Common.quick_actions') ?>
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="list-group">
                        <a href="<?= site_url('manufacturing/projects/view') ?>" class="list-group-item modal-dlg" data-btn-submit="<?= lang('Common.submit') ?>">
                            <i class="glyphicon glyphicon-plus"></i>
                            <?= lang('Manufacturing.add_project') ?>
                        </a>
                        <a href="<?= site_url('manufacturing/transfers/create') ?>" class="list-group-item">
                            <i class="glyphicon glyphicon-transfer"></i>
                            <?= lang('Manufacturing.add_transfer') ?>
                        </a>
                        <a href="<?= site_url('manufacturing/labor/create') ?>" class="list-group-item">
                            <i class="glyphicon glyphicon-time"></i>
                            <?= lang('Manufacturing.add_labor_entry') ?>
                        </a>
                        <a href="<?= site_url('manufacturing/reports') ?>" class="list-group-item">
                            <i class="glyphicon glyphicon-file"></i>
                            <?= lang('Manufacturing.reports') ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= view('partial/footer') ?>
