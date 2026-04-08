<?php
/**
 * @var array $shifts
 */
?>

<?= view('partial/header') ?>

<style>
.hr-page {
    padding: 20px 0;
}
.page-header-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e9ecef;
}
.page-header-bar h1 {
    margin: 0;
    font-size: 28px;
    font-weight: 600;
    color: #2c3e50;
}
.page-header-bar h1 .glyphicon {
    margin-right: 12px;
    color: var(--primary);
}
.hr-table {
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
.hr-table thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
}
.hr-table thead th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.5px;
    border: none;
    padding: 15px;
}
.hr-table tbody td {
    vertical-align: middle;
    padding: 12px 15px;
    border-color: #f0f0f0;
}
.hr-table tbody tr:hover {
    background-color: #f8f9ff;
}
.hr-table .id-cell {
    font-weight: 600;
    color: #6c757d;
    min-width: 50px;
}
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}
.status-badge.active {
    background: #d4edda;
    color: #155724;
}
.status-badge.inactive {
    background: #e2e3e5;
    color: #6c757d;
}
.status-badge.yes {
    background: #fff3cd;
    color: #856404;
}
.status-badge.no {
    background: #e2e3e5;
    color: #6c757d;
}
.shift-time {
    font-family: monospace;
    font-size: 14px;
    background: #f8f9fa;
    padding: 4px 8px;
    border-radius: 4px;
}
.hours-badge {
    background: #e3f2fd;
    color: #1565c0;
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: 600;
}
.breadcrumb-bar {
    margin-bottom: 20px;
}
.breadcrumb-bar .breadcrumb {
    margin: 0;
    padding: 10px 0;
    background: transparent;
}
</style>

<div class="hr-page">
    <div class="breadcrumb-bar">
        <ol class="breadcrumb">
            <li><a href="<?= site_url() ?>"><?= lang('Module.home') ?></a></li>
            <li><a href="<?= site_url('hr') ?>"><?= lang('Hr.hr_dashboard') ?></a></li>
            <li class="active"><?= lang('Hr.shifts') ?></li>
        </ol>
    </div>

    <div class="page-header-bar">
        <h1>
            <span class="glyphicon glyphicon-time"></span>
            <?= lang('Hr.shifts') ?>
        </h1>
        <div>
            <button class="btn btn-primary btn-lg modal-dlg" data-btn-submit="<?= lang('Common.submit') ?>" 
                    data-href="<?= site_url('hr/shift') ?>" title="<?= lang('Hr.new_shift') ?>">
                <span class="glyphicon glyphicon-plus"></span> <?= lang('Hr.new_shift') ?>
            </button>
        </div>
    </div>

    <div class="hr-table">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th style="width: 60px;"><?= lang('Common.id') ?></th>
                    <th><?= lang('Hr.shift_name') ?></th>
                    <th style="width: 100px;"><?= lang('Hr.code') ?></th>
                    <th style="width: 120px;"><?= lang('Hr.start_time') ?></th>
                    <th style="width: 120px;"><?= lang('Hr.end_time') ?></th>
                    <th style="width: 100px;"><?= lang('Hr.working_hours') ?></th>
                    <th style="width: 110px;"><?= lang('Hr.night_shift') ?></th>
                    <th style="width: 100px;"><?= lang('Common.status') ?></th>
                    <th style="width: 80px;"><?= lang('Common.actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($shifts as $shift): ?>
                    <tr>
                        <td class="id-cell"><?= str_pad($shift['id'], 4, '0', STR_PAD_LEFT) ?></td>
                        <td>
                            <strong><?= esc($shift['name']) ?></strong>
                        </td>
                        <td>
                            <code style="background: #f0f0f0; padding: 2px 6px; border-radius: 3px;"><?= esc($shift['code']) ?></code>
                        </td>
                        <td>
                            <span class="shift-time"><?= date('H:i', strtotime($shift['start_time'])) ?></span>
                        </td>
                        <td>
                            <span class="shift-time"><?= date('H:i', strtotime($shift['end_time'])) ?></span>
                        </td>
                        <td>
                            <span class="hours-badge"><?= number_format($shift['working_hours'], 1) ?>h</span>
                        </td>
                        <td>
                            <?php if ($shift['is_night_shift']): ?>
                                <span class="status-badge yes"><span class="glyphicon glyphicon-moon"></span> <?= lang('Common.yes') ?></span>
                            <?php else: ?>
                                <span class="status-badge no"><?= lang('Common.no') ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($shift['is_active']): ?>
                                <span class="status-badge active"><?= lang('Common.active') ?></span>
                            <?php else: ?>
                                <span class="status-badge inactive"><?= lang('Common.inactive') ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn-group btn-group-xs">
                                <button class="btn btn-warning modal-dlg" data-btn-submit="<?= lang('Common.submit') ?>"
                                        data-href="<?= site_url("hr/shift/{$shift['id']}") ?>" title="<?= lang('Common.edit') ?>">
                                    <span class="glyphicon glyphicon-pencil"></span>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($shifts)): ?>
                    <tr>
                        <td colspan="9" class="text-center text-muted" style="padding: 40px;">
                            <span class="glyphicon glyphicon-folder-open" style="font-size: 48px; opacity: 0.3;"></span>
                            <p style="margin-top: 15px;"><?= lang('Common.no_data') ?></p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= view('partial/footer') ?>
