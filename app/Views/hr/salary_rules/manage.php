<?php
/**
 * @var array $rules
 * @var array $groups
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
.hr-table .id-cell {
    font-weight: 600;
    color: #6c757d;
    min-width: 50px;
}
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
.type-badge {
    display: inline-flex;
    align-items: center;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}
.type-badge.fixed { background: #e2e3e5; color: #383d41; }
.type-badge.percentage { background: #e3f2fd; color: #1565c0; }
.type-badge.formula { background: #fff3cd; color: #856404; }
.type-badge.conditional { background: #f3e5f5; color: #6a1b9a; }
.value-display {
    font-weight: 600;
    color: #2c3e50;
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
            <li class="active"><?= lang('Hr.salary_rules') ?></li>
        </ol>
    </div>

    <div class="page-header-bar">
        <h1><span class="glyphicon glyphicon-cog"></span> <?= lang('Hr.salary_rules') ?></h1>
        <div class="btn-group">
            <button class="btn btn-outline btn-default" onclick="showGroupsModal()">
                <span class="glyphicon glyphicon-folder-open"></span> <?= lang('Hr.manage_groups') ?>
            </button>
            <button class="btn btn-primary btn-lg modal-dlg" data-btn-submit="<?= lang('Common.submit') ?>" 
                    data-href="<?= site_url('hr/salary_rule') ?>" title="<?= lang('Hr.new_rule') ?>">
                <span class="glyphicon glyphicon-plus"></span> <?= lang('Hr.new_rule') ?>
            </button>
        </div>
    </div>

    <div class="hr-table">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th style="width: 60px;"><?= lang('Common.id') ?></th>
                    <th style="width: 110px;"><?= lang('Hr.rule_code') ?></th>
                    <th><?= lang('Hr.rule_name') ?></th>
                    <th style="width: 130px;"><?= lang('Hr.rule_group') ?></th>
                    <th style="width: 120px;"><?= lang('Hr.rule_type') ?></th>
                    <th style="width: 110px;"><?= lang('Hr.value') ?></th>
                    <th style="width: 150px;"><?= lang('Hr.scope') ?></th>
                    <th style="width: 100px;"><?= lang('Common.status') ?></th>
                    <th style="width: 80px;"><?= lang('Common.actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rules as $rule): ?>
                    <tr>
                        <td class="id-cell"><?= str_pad($rule['id'], 4, '0', STR_PAD_LEFT) ?></td>
                        <td><code class="code-badge"><?= esc($rule['code']) ?></code></td>
                        <td><strong><?= esc($rule['name']) ?></strong></td>
                        <td><span class="text-muted"><?= esc($rule['group_name'] ?? '—') ?></span></td>
                        <td>
                            <span class="type-badge <?= $rule['rule_type'] ?>">
                                <?= lang("Hr.type_{$rule['rule_type']}") ?>
                            </span>
                        </td>
                        <td>
                            <?php
                            if ($rule['rule_type'] === 'percentage') {
                                echo '<span class="value-display">' . $rule['value'] . '%</span>';
                            } elseif ($rule['rule_type'] === 'fixed') {
                                echo '<span class="value-display">' . to_currency($rule['value']) . '</span>';
                            } elseif ($rule['rule_type'] === 'formula') {
                                echo '<span class="text-muted">—</span>';
                            } else {
                                echo '<span class="value-display">' . to_currency($rule['value']) . '</span>';
                            }
                            ?>
                        </td>
                        <td><span class="text-info"><?= lang("Hr.scope_{$rule['scope']}") ?></span></td>
                        <td>
                            <?php if ($rule['is_active']): ?>
                                <span class="status-badge active"><?= lang('Common.active') ?></span>
                            <?php else: ?>
                                <span class="status-badge inactive"><?= lang('Common.inactive') ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn-group btn-group-xs">
                                <button class="btn btn-warning modal-dlg" data-btn-submit="<?= lang('Common.submit') ?>"
                                        data-href="<?= site_url("hr/salary_rule/{$rule['id']}") ?>" title="<?= lang('Common.edit') ?>">
                                    <span class="glyphicon glyphicon-pencil"></span>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($rules)): ?>
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

function showGroupsModal() {
    window.location.href = '<?= site_url('hr/salary_rule_groups') ?>';
}
</script>

<?= view('partial/footer') ?>
