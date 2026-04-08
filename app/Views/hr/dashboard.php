<?php
/**
 * @var int $total_departments
 * @var int $total_positions
 * @var int $total_shifts
 * @var int $total_salary_rules
 * @var int $pending_leave_requests
 * @var int $total_employees
 * @var array $recent_attendance
 * @var array $stats
 */
?>

<?= view('partial/header') ?>

<style>
.hr-dashboard {
    padding: 20px 0;
}
.stat-card {
    border-radius: 12px;
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: transform 0.2s, box-shadow 0.2s;
    overflow: hidden;
}
.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
}
.stat-card .panel-body {
    padding: 25px;
}
.stat-card .stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}
.stat-card .stat-number {
    font-size: 32px;
    font-weight: 700;
    line-height: 1;
    margin-bottom: 8px;
}
.stat-card .stat-label {
    font-size: 14px;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.quick-action-card {
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    transition: all 0.2s;
    border: 2px solid transparent;
}
.quick-action-card:hover {
    border-color: var(--primary);
    background: rgba(var(--primary-rgb), 0.05);
}
.quick-action-card .action-icon {
    font-size: 32px;
    margin-bottom: 12px;
    color: var(--primary);
}
.section-card {
    border-radius: 12px;
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
.section-card .panel-heading {
    border-radius: 12px 12px 0 0;
    padding: 15px 20px;
    font-weight: 600;
}
.alert-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 24px;
    height: 24px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}
.employee-chip {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    background: #f8f9fa;
    border-radius: 20px;
    margin: 4px;
    font-size: 13px;
}
.employee-chip .avatar {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: var(--primary);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 8px;
    font-size: 12px;
}
.breadcrumb-nav {
    padding: 15px 0;
}
.breadcrumb-nav .breadcrumb {
    margin: 0;
    padding: 0;
    background: transparent;
}
</style>

<div class="hr-dashboard">
    <div class="row breadcrumb-nav">
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li><a href="<?= site_url() ?>"><?= lang('Module.home') ?></a></li>
                <li class="active"><?= lang('Hr.hr_dashboard') ?></li>
            </ol>
        </div>
        <div class="col-md-6 text-right">
            <span class="text-muted"><?= date('l, F j, Y') ?></span>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h1 class="page-header" style="margin-top: 0;">
                <span class="glyphicon glyphicon-blackboard"></span>
                <?= lang('Hr.hr_dashboard') ?>
            </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2 col-sm-4 col-xs-6">
            <div class="stat-card panel panel-primary">
                <div class="panel-body">
                    <div class="stat-icon bg-primary">
                        <span class="glyphicon glyphicon-th-large"></span>
                    </div>
                    <div class="stat-number text-primary"><?= $total_departments ?></div>
                    <div class="stat-label"><?= lang('Hr.departments') ?></div>
                </div>
                <div class="panel-footer text-center">
                    <a href="<?= site_url('hr/departments') ?>" class="btn btn-xs btn-primary btn-block">
                        <span class="glyphicon glyphicon-cog"></span> <?= lang('Common.manage') ?>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-2 col-sm-4 col-xs-6">
            <div class="stat-card panel panel-success">
                <div class="panel-body">
                    <div class="stat-icon bg-success">
                        <span class="glyphicon glyphicon-briefcase"></span>
                    </div>
                    <div class="stat-number text-success"><?= $total_positions ?></div>
                    <div class="stat-label"><?= lang('Hr.positions') ?></div>
                </div>
                <div class="panel-footer text-center">
                    <a href="<?= site_url('hr/positions') ?>" class="btn btn-xs btn-success btn-block">
                        <span class="glyphicon glyphicon-cog"></span> <?= lang('Common.manage') ?>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-2 col-sm-4 col-xs-6">
            <div class="stat-card panel panel-info">
                <div class="panel-body">
                    <div class="stat-icon bg-info">
                        <span class="glyphicon glyphicon-time"></span>
                    </div>
                    <div class="stat-number text-info"><?= $total_shifts ?></div>
                    <div class="stat-label"><?= lang('Hr.shifts') ?></div>
                </div>
                <div class="panel-footer text-center">
                    <a href="<?= site_url('hr/shifts') ?>" class="btn btn-xs btn-info btn-block">
                        <span class="glyphicon glyphicon-cog"></span> <?= lang('Common.manage') ?>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-2 col-sm-4 col-xs-6">
            <div class="stat-card panel panel-warning">
                <div class="panel-body">
                    <div class="stat-icon bg-warning">
                        <span class="glyphicon glyphicon-usd"></span>
                    </div>
                    <div class="stat-number text-warning"><?= $total_salary_rules ?></div>
                    <div class="stat-label"><?= lang('Hr.salary_rules') ?></div>
                </div>
                <div class="panel-footer text-center">
                    <a href="<?= site_url('hr/salary_rules') ?>" class="btn btn-xs btn-warning btn-block">
                        <span class="glyphicon glyphicon-cog"></span> <?= lang('Common.manage') ?>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-2 col-sm-4 col-xs-6">
            <div class="stat-card panel panel-danger">
                <div class="panel-body">
                    <div class="stat-icon bg-danger">
                        <span class="glyphicon glyphicon-alert"></span>
                    </div>
                    <div class="stat-number text-danger"><?= $pending_leave_requests ?></div>
                    <div class="stat-label"><?= lang('Hr.pending_leave_requests') ?></div>
                </div>
                <div class="panel-footer text-center">
                    <a href="<?= site_url('hr/leave_requests') ?>" class="btn btn-xs btn-danger btn-block">
                        <span class="glyphicon glyphicon-cog"></span> <?= lang('Common.manage') ?>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-2 col-sm-4 col-xs-6">
            <div class="stat-card panel panel-default">
                <div class="panel-body">
                    <div class="stat-icon bg-default">
                        <span class="glyphicon glyphicon-user"></span>
                    </div>
                    <div class="stat-number text-default"><?= $total_employees ?? 0 ?></div>
                    <div class="stat-label"><?= lang('Module.employees') ?></div>
                </div>
                <div class="panel-footer text-center">
                    <a href="<?= site_url('hr/profiles') ?>" class="btn btn-xs btn-default btn-block">
                        <span class="glyphicon glyphicon-cog"></span> <?= lang('Common.manage') ?>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row" style="margin-top: 30px;">
        <div class="col-md-12">
            <h3 class="page-sub-header">
                <span class="glyphicon glyphicon-bolt"></span> <?= lang('Hr.quick_actions') ?>
            </h3>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2 col-sm-4 col-xs-6">
            <a href="<?= site_url('hr/profiles') ?>" class="quick-action-card btn btn-default btn-block">
                <div class="action-icon"><span class="glyphicon glyphicon-user"></span></div>
                <div><?= lang('Hr.employee_profiles') ?></div>
            </a>
        </div>

        <div class="col-md-2 col-sm-4 col-xs-6">
            <a href="<?= site_url('hr/calculate') ?>" class="quick-action-card btn btn-default btn-block">
                <div class="action-icon"><span class="glyphicon glyphicon-calculator"></span></div>
                <div><?= lang('Hr.calculate_salary') ?></div>
            </a>
        </div>

        <div class="col-md-2 col-sm-4 col-xs-6">
            <a href="<?= site_url('hr/attendance') ?>" class="quick-action-card btn btn-default btn-block">
                <div class="action-icon"><span class="glyphicon glyphicon-time"></span></div>
                <div><?= lang('Hr.attendance') ?></div>
            </a>
        </div>

        <div class="col-md-2 col-sm-4 col-xs-6">
            <a href="<?= site_url('hr/leave_requests') ?>" class="quick-action-card btn btn-default btn-block">
                <div class="action-icon"><span class="glyphicon glyphicon-calendar"></span></div>
                <div><?= lang('Hr.leave_requests') ?></div>
            </a>
        </div>

        <div class="col-md-2 col-sm-4 col-xs-6">
            <a href="<?= site_url('hr/leave_types') ?>" class="quick-action-card btn btn-default btn-block">
                <div class="action-icon"><span class="glyphicon glyphicon-list"></span></div>
                <div><?= lang('Hr.leave_types') ?></div>
            </a>
        </div>

        <div class="col-md-2 col-sm-4 col-xs-6">
            <a href="<?= site_url('hr/salary_rules') ?>" class="quick-action-card btn btn-default btn-block">
                <div class="action-icon"><span class="glyphicon glyphicon-cog"></span></div>
                <div><?= lang('Hr.salary_rules') ?></div>
            </a>
        </div>
    </div>

    <div class="row" style="margin-top: 30px;">
        <div class="col-md-6">
            <div class="section-card panel panel-default">
                <div class="panel-heading bg-warning">
                    <span class="glyphicon glyphicon-exclamation-sign"></span>
                    <?= lang('Hr.pending_leave_requests') ?>
                    <?php if ($pending_leave_requests > 0): ?>
                        <span class="alert-badge bg-danger pull-right"><?= $pending_leave_requests ?></span>
                    <?php endif; ?>
                </div>
                <div class="panel-body">
                    <?php if ($pending_leave_requests > 0): ?>
                        <div class="alert alert-warning">
                            <strong><?= $pending_leave_requests ?></strong> <?= lang('Hr.pending_requests') ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-success">
                            <span class="glyphicon glyphicon-ok-sign"></span>
                            <?= lang('Hr.no_pending_requests') ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="panel-footer text-center">
                    <a href="<?= site_url('hr/leave_requests') ?>" class="btn btn-warning btn-sm">
                        <?= lang('Common.view_all') ?> <span class="glyphicon glyphicon-arrow-right"></span>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="section-card panel panel-default">
                <div class="panel-heading bg-info">
                    <span class="glyphicon glyphicon-calendar"></span>
                    <?= lang('Hr.today_attendance') ?>
                </div>
                <div class="panel-body">
                    <?php if (!empty($recent_attendance)): ?>
                        <p class="mb-2">
                            <strong class="text-success"><?= count($recent_attendance) ?></strong>
                            <?= lang('Hr.employees_clocked_in') ?>
                        </p>
                        <div class="text-center">
                            <?php foreach (array_slice($recent_attendance, 0, 5) as $att): ?>
                                <span class="employee-chip">
                                    <span class="avatar">
                                        <?= strtoupper(substr($att['first_name'] ?? 'U', 0, 1)) ?>
                                    </span>
                                    <?= esc($att['first_name'] ?? 'Unknown') ?>
                                </span>
                            <?php endforeach; ?>
                            <?php if (count($recent_attendance) > 5): ?>
                                <span class="employee-chip">
                                    <span class="text-muted">+<?= count($recent_attendance) - 5 ?> more</span>
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <span class="glyphicon glyphicon-info-sign"></span>
                            <?= lang('Hr.no_attendance_records') ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="panel-footer text-center">
                    <a href="<?= site_url('hr/attendance') ?>" class="btn btn-info btn-sm">
                        <?= lang('Common.manage') ?> <span class="glyphicon glyphicon-arrow-right"></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= view('partial/footer') ?>
