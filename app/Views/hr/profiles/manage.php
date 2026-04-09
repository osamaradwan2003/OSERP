<?php
/**
 * @var array $profiles
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
.hr-table tbody tr:hover { background-color: #f8f9ff; }
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
.employee-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}
.employee-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
}
.emp-number {
    font-family: monospace;
    background: #f0f0f0;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 12px;
}
.salary-badge {
    background: #e8f5e9;
    color: #2e7d32;
    padding: 4px 10px;
    border-radius: 4px;
    font-weight: 600;
}
.breadcrumb-bar { margin-bottom: 20px; }
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
            <li class="active"><?= lang('Hr.employee_profiles') ?></li>
        </ol>
    </div>

    <div class="page-header-bar">
        <h1><span class="glyphicon glyphicon-user"></span> <?= lang('Hr.employee_profiles') ?></h1>
    </div>

    <div class="hr-table">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th style="width: 50px;"><?= lang('Common.id') ?></th>
                    <th><?= lang('Hr.employee') ?></th>
                    <th style="width: 120px;"><?= lang('Hr.employee_number') ?></th>
                    <th style="width: 130px;"><?= lang('Hr.department') ?></th>
                    <th style="width: 130px;"><?= lang('Hr.position') ?></th>
                    <th style="width: 120px;"><?= lang('Hr.basic_salary') ?></th>
                    <th style="width: 100px;"><?= lang('Hr.hire_date') ?></th>
                    <th style="width: 110px;"><?= lang('Hr.status') ?></th>
                    <th style="width: 80px;"><?= lang('Common.actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($profiles as $profile): ?>
                    <tr>
                        <td><?= $profile['employee_id'] ?></td>
                        <td>
                            <div class="employee-cell">
                                <div class="employee-avatar">
                                    <?= strtoupper(substr($profile['first_name'] ?? 'U', 0, 1)) ?>
                                </div>
                                <strong><?= esc($profile['first_name'] . ' ' . $profile['last_name']) ?></strong>
                            </div>
                        </td>
                        <td>
                            <?php if (!empty($profile['employee_number'])): ?>
                                <code class="emp-number"><?= esc($profile['employee_number']) ?></code>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td><span class="text-primary"><?= esc($profile['department_name'] ?? '—') ?></span></td>
                        <td><span class="text-info"><?= esc($profile['position_name'] ?? '—') ?></span></td>
                        <td><span class="salary-badge"><?= to_currency($profile['basic_salary'] ?? 0) ?></span></td>
                        <td>
                            <?php if (!empty($profile['hire_date'])): ?>
                                <span class="text-muted"><?= date('d M Y', strtotime($profile['hire_date'])) ?></span>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php $status = $profile['employment_status'] ?? 'active'; ?>
                            <span class="status-badge <?= $status ?>"><?= lang("Hr.status_{$status}") ?></span>
                        </td>
                        <td>
                            <button class="btn btn-warning btn-xs modal-dlg" data-btn-submit="<?= lang('Common.submit') ?>"
                                    data-href="<?= site_url("hr/profile/{$profile['employee_id']}") ?>" title="<?= lang('Common.edit') ?>">
                                <span class="glyphicon glyphicon-pencil"></span>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($profiles)): ?>
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
                size: BootstrapDialog.SIZE_NORMAL,
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
