<?php
/**
 * @var array $leave_types
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
.code-badge {
    background: #e8eaf6;
    color: #3949ab;
    padding: 4px 10px;
    border-radius: 4px;
    font-weight: 600;
    font-family: monospace;
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
.status-badge.active { background: #d4edda; color: #155724; }
.status-badge.inactive { background: #e2e3e5; color: #6c757d; }
.paid-badge {
    background: #d4edda;
    color: #155724;
    padding: 4px 10px;
    border-radius: 4px;
    font-weight: 600;
}
.unpaid-badge {
    background: #f8d7da;
    color: #721c24;
    padding: 4px 10px;
    border-radius: 4px;
    font-weight: 600;
}
</style>

<div class="hr-page">
    <div class="breadcrumb-bar">
        <ol class="breadcrumb">
            <li><a href="<?= site_url() ?>"><?= lang('Module.home') ?></a></li>
            <li><a href="<?= site_url('hr') ?>"><?= lang('Hr.hr_dashboard') ?></a></li>
            <li class="active"><?= lang('Hr.leave_types') ?></li>
        </ol>
    </div>

    <div class="page-header-bar">
        <h1><span class="glyphicon glyphicon-list"></span> <?= lang('Hr.leave_types') ?></h1>
        <div>
            <button class="btn btn-primary btn-lg modal-dlg" data-btn-submit="<?= lang('Common.submit') ?>" 
                    data-href="<?= site_url('hr/leave_type') ?>" title="<?= lang('Hr.new_leave_type') ?>">
                <span class="glyphicon glyphicon-plus"></span> <?= lang('Hr.new_leave_type') ?>
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover" id="leave_type_table">
            <thead>
                <tr>
                    <th style="width: 60px;"><?= lang('Common.id') ?></th>
                    <th><?= lang('Hr.name') ?></th>
                    <th style="width: 100px;"><?= lang('Hr.code') ?></th>
                    <th style="width: 110px;"><?= lang('Hr.paid_unpaid') ?></th>
                    <th style="width: 110px;"><?= lang('Hr.default_days') ?></th>
                    <th style="width: 100px;"><?= lang('Common.status') ?></th>
                    <th style="width: 80px;"><?= lang('Common.actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($leave_types as $type): ?>
                    <tr>
                        <td><?= str_pad($type['id'], 4, '0', STR_PAD_LEFT) ?></td>
                        <td><strong><?= esc($type['name']) ?></strong></td>
                        <td><span class="code-badge"><?= esc($type['code']) ?></span></td>
                        <td>
                            <?php if ($type['paid_unpaid'] === 'paid'): ?>
                                <span class="paid-badge"><?= lang('Hr.paid') ?></span>
                            <?php else: ?>
                                <span class="unpaid-badge"><?= lang('Hr.unpaid') ?></span>
                            <?php endif; ?>
                        </td>
                        <td><?= $type['default_days'] ?> <?= lang('Hr.days') ?></td>
                        <td>
                            <?php if ($type['is_active']): ?>
                                <span class="status-badge active"><?= lang('Common.active') ?></span>
                            <?php else: ?>
                                <span class="status-badge inactive"><?= lang('Common.inactive') ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="btn btn-warning btn-xs modal-dlg" data-btn-submit="<?= lang('Common.submit') ?>"
                                    data-href="<?= site_url("hr/leave_type/{$type['id']}") ?>" title="<?= lang('Common.edit') ?>">
                                <span class="glyphicon glyphicon-pencil"></span>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($leave_types)): ?>
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
