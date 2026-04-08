<?php
/**
 * @var array $attendance_records
 * @var string $current_date
 * @var array $employees
 * @var array $stats
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
    flex-wrap: wrap;
    gap: 15px;
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
.date-display {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    padding: 8px 16px;
    border-radius: 25px;
    font-weight: 600;
}
.stats-card {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    transition: transform 0.2s;
}
.stats-card:hover {
    transform: translateY(-2px);
}
.stats-card .number {
    font-size: 36px;
    font-weight: 700;
    line-height: 1;
}
.stats-card .label {
    font-size: 13px;
    color: #6c757d;
    text-transform: uppercase;
    margin-top: 8px;
}
.stats-card.present .number { color: #28a745; }
.stats-card.late .number { color: #ffc107; }
.stats-card.absent .number { color: #dc3545; }
.stats-card.overtime .number { color: #17a2b8; }
.action-card {
    background: #fff;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
.action-card h4 {
    margin-bottom: 20px;
    font-weight: 600;
}
.action-card .btn {
    margin-top: 15px;
}
.hr-table {
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    margin-top: 25px;
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
.time-display {
    font-family: monospace;
    font-size: 15px;
    background: #f8f9fa;
    padding: 5px 10px;
    border-radius: 5px;
    display: inline-block;
    min-width: 60px;
    text-align: center;
}
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}
.status-badge.present { background: #d4edda; color: #155724; }
.status-badge.late { background: #fff3cd; color: #856404; }
.status-badge.absent { background: #f8d7da; color: #721c24; }
.status-badge.on_leave { background: #d1ecf1; color: #0c5460; }
.status-badge.early_out { background: #ffe5d0; color: #833c00; }
.status-badge.holiday { background: #e2e3e5; color: #383d41; }
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
            <li class="active"><?= lang('Hr.attendance') ?></li>
        </ol>
    </div>

    <div class="page-header-bar">
        <h1>
            <span class="glyphicon glyphicon-time"></span>
            <?= lang('Hr.attendance') ?>
        </h1>
        <div class="date-display">
            <span class="glyphicon glyphicon-calendar"></span>
            <?= date('d M Y', strtotime($current_date)) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 col-sm-6">
            <div class="stats-card present">
                <div class="number"><?= $stats['present'] ?? 0 ?></div>
                <div class="label"><?= lang('Hr.status_present') ?></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stats-card late">
                <div class="number"><?= $stats['late'] ?? 0 ?></div>
                <div class="label"><?= lang('Hr.status_late') ?></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stats-card absent">
                <div class="number"><?= $stats['absent'] ?? 0 ?></div>
                <div class="label"><?= lang('Hr.status_absent') ?></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stats-card overtime">
                <div class="number"><?= $stats['overtime'] ?? 0 ?></div>
                <div class="label"><?= lang('Hr.overtime') ?></div>
            </div>
        </div>
    </div>

    <div class="row" style="margin-top: 25px;">
        <div class="col-md-4">
            <div class="action-card">
                <h4 class="text-success">
                    <span class="glyphicon glyphicon-log-in"></span>
                    <?= lang('Hr.clock_in') ?>
                </h4>
                <?= form_open('hr/clock_in', ['id' => 'clock_in_form']) ?>
                <div class="form-group">
                    <label><?= lang('Hr.employee') ?></label>
                    <select name="employee_id" class="form-control" required>
                        <option value=""><?= lang('Common.select') ?></option>
                        <?php foreach ($employees as $emp): ?>
                            <option value="<?= $emp['employee_id'] ?>">
                                <?= esc($emp['first_name'] . ' ' . $emp['last_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label><?= lang('Hr.time') ?></label>
                    <input type="time" name="time" class="form-control" value="<?= date('H:i') ?>">
                </div>
                <input type="hidden" name="date" value="<?= $current_date ?>">
                <button type="submit" class="btn btn-success btn-block btn-lg">
                    <span class="glyphicon glyphicon-log-in"></span> <?= lang('Hr.clock_in') ?>
                </button>
                <?= form_close() ?>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="action-card">
                <h4 class="text-warning">
                    <span class="glyphicon glyphicon-log-out"></span>
                    <?= lang('Hr.clock_out') ?>
                </h4>
                <?= form_open('hr/clock_out', ['id' => 'clock_out_form']) ?>
                <div class="form-group">
                    <label><?= lang('Hr.employee') ?></label>
                    <select name="employee_id" class="form-control" required>
                        <option value=""><?= lang('Common.select') ?></option>
                        <?php foreach ($employees as $emp): ?>
                            <option value="<?= $emp['employee_id'] ?>">
                                <?= esc($emp['first_name'] . ' ' . $emp['last_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label><?= lang('Hr.time') ?></label>
                    <input type="time" name="time" class="form-control" value="<?= date('H:i') ?>">
                </div>
                <input type="hidden" name="date" value="<?= $current_date ?>">
                <button type="submit" class="btn btn-warning btn-block btn-lg">
                    <span class="glyphicon glyphicon-log-out"></span> <?= lang('Hr.clock_out') ?>
                </button>
                <?= form_close() ?>
            </div>
        </div>

        <div class="col-md-4">
            <div class="action-card">
                <h4 class="text-info">
                    <span class="glyphicon glyphicon-filter"></span>
                    <?= lang('Common.filter') ?>
                </h4>
                <div class="form-group">
                    <label><?= lang('Hr.start_date') ?></label>
                    <input type="date" id="filter_date" class="form-control" value="<?= $current_date ?>">
                </div>
                <button class="btn btn-primary btn-block btn-lg" onclick="filterByDate()">
                    <span class="glyphicon glyphicon-search"></span> <?= lang('Common.search') ?>
                </button>
            </div>
        </div>
    </div>

    <div class="hr-table">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th><?= lang('Hr.employee') ?></th>
                    <th style="width: 100px;"><?= lang('Hr.clock_in') ?></th>
                    <th style="width: 100px;"><?= lang('Hr.clock_out') ?></th>
                    <th style="width: 110px;"><?= lang('Hr.status') ?></th>
                    <th style="width: 100px;"><?= lang('Hr.worked_hours') ?></th>
                    <th style="width: 100px;"><?= lang('Hr.overtime') ?></th>
                    <th><?= lang('Hr.late_minutes') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($attendance_records as $record): ?>
                    <tr>
                        <td>
                            <strong><?= esc($record['first_name'] . ' ' . $record['last_name']) ?></strong>
                        </td>
                        <td>
                            <?php if ($record['clock_in']): ?>
                                <span class="time-display"><?= date('H:i', strtotime($record['clock_in'])) ?></span>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($record['clock_out']): ?>
                                <span class="time-display"><?= date('H:i', strtotime($record['clock_out'])) ?></span>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="status-badge <?= $record['status'] ?>"><?= lang("Hr.status_{$record['status']}") ?></span>
                        </td>
                        <td>
                            <?= $record['worked_hours'] ? number_format($record['worked_hours'], 1) . 'h' : '<span class="text-muted">—</span>' ?>
                        </td>
                        <td>
                            <?= $record['overtime_minutes'] ? number_format($record['overtime_minutes'] / 60, 1) . 'h' : '<span class="text-muted">—</span>' ?>
                        </td>
                        <td>
                            <?= $record['late_minutes'] ? '<span class="text-warning">' . $record['late_minutes'] . ' min</span>' : '<span class="text-muted">—</span>' ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($attendance_records)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted" style="padding: 40px;">
                            <span class="glyphicon glyphicon-folder-open" style="font-size: 48px; opacity: 0.3;"></span>
                            <p style="margin-top: 15px;"><?= lang('Common.no_data') ?></p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('#clock_in_form, #clock_out_form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function(response) {
                alert(response.message);
                if (response.success) {
                    location.reload();
                }
            }
        });
    });
});

function filterByDate() {
    var date = $('#filter_date').val();
    window.location.href = '<?= site_url('hr/attendance') ?>?date=' + date;
}
</script>

<?= view('partial/footer') ?>
