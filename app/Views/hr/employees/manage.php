<?php
/**
 * @var array $employees
 */
?>

<?= view('partial/header') ?>

<style>
.hr-page { padding: 20px 0; }
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
.page-header-bar h1 .glyphicon { margin-right: 12px; color: var(--primary); }
.breadcrumb-bar { margin-bottom: 20px; }
.breadcrumb-bar .breadcrumb { margin: 0; padding: 10px 0; background: transparent; }
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}
.status-badge.active { background: #d4edda; color: #155724; }
.status-badge.on_leave { background: #fff3cd; color: #856404; }
.status-badge.suspended { background: #f8d7da; color: #721c24; }
.status-badge.terminated { background: #e2e3e5; color: #383d41; }
</style>

<div class="hr-page">
    <div class="breadcrumb-bar">
        <ol class="breadcrumb">
            <li><a href="<?= site_url() ?>"><?= lang('Module.home') ?></a></li>
            <li><a href="<?= site_url('hr') ?>"><?= lang('Hr.hr_dashboard') ?></a></li>
            <li class="active"><?= lang('Hr.employees') ?></li>
        </ol>
    </div>

    <div class="page-header-bar">
        <h1><span class="glyphicon glyphicon-user"></span> <?= lang('Hr.employees') ?></h1>
        <div>
            <button class="btn btn-primary btn-lg modal-dlg" data-btn-submit="<?= lang('Common.submit') ?>" 
                    data-href="<?= site_url('hr/employee') ?>" title="<?= lang('Hr.add_employee') ?>">
                <span class="glyphicon glyphicon-plus"></span> <?= lang('Hr.add_employee') ?>
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th style="width: 60px;"><?= lang('Common.id') ?></th>
                    <th><?= lang('Hr.employee') ?></th>
                    <th style="width: 120px;"><?= lang('Hr.employee_number') ?></th>
                    <th><?= lang('Hr.department') ?></th>
                    <th><?= lang('Hr.position') ?></th>
                    <th style="width: 120px;"><?= lang('Hr.basic_salary') ?></th>
                    <th style="width: 100px;"><?= lang('Hr.hire_date') ?></th>
                    <th style="width: 110px;"><?= lang('Common.status') ?></th>
                    <th style="width: 80px;"><?= lang('Common.actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employees as $emp): ?>
                    <?php 
                    $status = $emp['employment_status'] ?? 'active';
                    $fullName = trim(($emp['first_name'] ?? '') . ' ' . ($emp['last_name'] ?? ''));
                    ?>
                    <tr>
                        <td><?= str_pad($emp['id'], 4, '0', STR_PAD_LEFT) ?></td>
                        <td>
                            <strong><?= esc($fullName ?: '<span class="text-muted">N/A</span>') ?></strong>
                            <br><small class="text-muted"><?= esc($emp['email'] ?? '') ?></small>
                        </td>
                        <td><?= esc($emp['employee_number'] ?? '—') ?></td>
                        <td><?= esc($emp['department_name'] ?? '—') ?></td>
                        <td><?= esc($emp['position_name'] ?? '—') ?></td>
                        <td><?= to_currency($emp['basic_salary'] ?? 0) ?></td>
                        <td><?= $emp['hire_date'] ? date('d M Y', strtotime($emp['hire_date'])) : '—' ?></td>
                        <td>
                            <span class="status-badge <?= $status ?>"><?= lang("Hr.status_{$status}") ?></span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-xs">
                                <a class="btn btn-info" href="<?= site_url("hr/employee/info/{$emp['employee_id']}") ?>" title="<?= lang('Common.view') ?>">
                                    <span class="glyphicon glyphicon-eye-open"></span>
                                </a>
                                <button class="btn btn-warning modal-dlg" data-btn-submit="<?= lang('Common.submit') ?>"
                                        data-href="<?= site_url("hr/employee/{$emp['employee_id']}") ?>" title="<?= lang('Common.edit') ?>">
                                    <span class="glyphicon glyphicon-pencil"></span>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($employees)): ?>
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

<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    var buttons = document.querySelectorAll('button.modal-dlg');
    buttons.forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var href = this.getAttribute('data-href') || this.getAttribute('href');
            var title = this.getAttribute('title') || 'Form';
            var btnSubmit = this.getAttribute('data-btn-submit') || 'Submit';
            
            BootstrapDialog.show({
                title: title,
                message: function(dialog) {
                    var $content = $('<div style="padding:10px;"><div class="text-center"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading...</div></div>');
                    $.get(href, function(data) {
                        $content.html(data);
                    });
                    return $content;
                },
                size: BootstrapDialog.SIZE_WIDE,
                buttons: [{
                    label: btnSubmit,
                    cssClass: 'btn-primary',
                    action: function(dialogRef) {
                        var form = dialogRef.$modalBody.find('form');
                        if (form.length && form[0].checkValidity()) {
                            form.submit();
                            dialogRef.close();
                        } else if (form.length) {
                            form[0].reportValidity();
                        }
                    }
                }, {
                    label: 'Close',
                    action: function(dialogRef) {
                        dialogRef.close();
                    }
                }]
            });
        });
    });
});
</script>

<?= view('partial/footer') ?>
