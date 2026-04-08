<?php
/**
 * @var array $positions
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
.level-badge {
    background: #e3f2fd;
    color: #1565c0;
    padding: 4px 10px;
    border-radius: 4px;
    font-weight: 600;
}
.dept-badge {
    background: #f3e5f5;
    color: #6a1b9a;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 13px;
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
            <li class="active"><?= lang('Hr.positions') ?></li>
        </ol>
    </div>

    <div class="page-header-bar">
        <h1>
            <span class="glyphicon glyphicon-briefcase"></span>
            <?= lang('Hr.positions') ?>
        </h1>
        <div>
            <button class="btn btn-primary btn-lg modal-dlg" data-btn-submit="<?= lang('Common.submit') ?>" 
                    data-href="<?= site_url('hr/position') ?>" title="<?= lang('Hr.new_position') ?>">
                <span class="glyphicon glyphicon-plus"></span> <?= lang('Hr.new_position') ?>
            </button>
        </div>
    </div>

    <div class="hr-table">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th style="width: 60px;"><?= lang('Common.id') ?></th>
                    <th><?= lang('Hr.name') ?></th>
                    <th style="width: 150px;"><?= lang('Hr.department') ?></th>
                    <th style="width: 100px;"><?= lang('Hr.level') ?></th>
                    <th style="width: 100px;"><?= lang('Common.status') ?></th>
                    <th style="width: 80px;"><?= lang('Common.actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($positions as $position): ?>
                    <tr>
                        <td class="id-cell"><?= str_pad($position['id'], 4, '0', STR_PAD_LEFT) ?></td>
                        <td>
                            <strong><?= esc($position['name']) ?></strong>
                        </td>
                        <td>
                            <?php if (!empty($position['department_name'])): ?>
                                <span class="dept-badge"><?= esc($position['department_name']) ?></span>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="level-badge">Lv. <?= $position['level'] ?></span>
                        </td>
                        <td>
                            <?php if ($position['is_active']): ?>
                                <span class="status-badge active"><?= lang('Common.active') ?></span>
                            <?php else: ?>
                                <span class="status-badge inactive"><?= lang('Common.inactive') ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn-group btn-group-xs">
                                <button class="btn btn-warning modal-dlg" data-btn-submit="<?= lang('Common.submit') ?>"
                                        data-href="<?= site_url("hr/position/{$position['id']}") ?>" title="<?= lang('Common.edit') ?>">
                                    <span class="glyphicon glyphicon-pencil"></span>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($positions)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted" style="padding: 40px;">
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
