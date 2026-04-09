<?php
/**
 * @var int $employeeId
 * @var array $allRules
 * @var array $employeeRules
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
.card-panel {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    overflow: hidden;
}
.card-panel .panel-heading {
    padding: 20px 25px;
    font-size: 18px;
    font-weight: 600;
}
.card-panel .panel-body { padding: 25px; }
.card-panel.assigned .panel-heading {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    color: #fff;
}
.card-panel.assign .panel-heading {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
}
.type-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
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
    color: #28a745;
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
    </div>

    <div class="row">
        <div class="col-md-7">
            <div class="card-panel assigned">
                <div class="panel-heading">
                    <span class="glyphicon glyphicon-check"></span> <?= lang('Hr.assigned_rules') ?>
                    <span class="badge pull-right" style="background: rgba(255,255,255,0.3);"><?= count($employeeRules) ?></span>
                </div>
                <div class="panel-body">
                    <?php if (empty($employeeRules)): ?>
                        <div class="text-center text-muted" style="padding: 30px;">
                            <span class="glyphicon glyphicon-folder-open" style="font-size: 48px; opacity: 0.3;"></span>
                            <p style="margin-top: 15px;"><?= lang('Hr.no_rules_assigned') ?></p>
                        </div>
                    <?php else: ?>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th><?= lang('Hr.rule_name') ?></th>
                                    <th style="width: 120px;"><?= lang('Hr.rule_type') ?></th>
                                    <th style="width: 110px;"><?= lang('Hr.custom_value') ?></th>
                                    <th style="width: 80px;"><?= lang('Common.actions') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($employeeRules as $er): ?>
                                    <tr>
                                        <td><strong><?= esc($er['name']) ?></strong></td>
                                        <td>
                                            <span class="type-badge <?= $er['rule_type'] ?>">
                                                <?= lang("Hr.type_{$er['rule_type']}") ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($er['custom_value'] !== null): ?>
                                                <span class="value-display"><?= to_currency($er['custom_value']) ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">—</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-danger btn-xs btn-remove-rule" data-rule-id="<?= $er['rule_id'] ?>">
                                                <span class="glyphicon glyphicon-trash"></span>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card-panel assign">
                <div class="panel-heading">
                    <span class="glyphicon glyphicon-plus"></span> <?= lang('Hr.assign_rule') ?>
                </div>
                <div class="panel-body">
                    <?= form_open('hr/assign_salary_rule', ['id' => 'assign_rule_form']) ?>
                    
                    <div class="form-group">
                        <?= form_label(lang('Hr.rule'), 'rule_id', ['class' => 'control-label']) ?>
                        <?= form_dropdown('rule_id', $allRules, '', ['class' => 'form-control input-lg', 'id' => 'rule_id']) ?>
                    </div>
                    
                    <div class="form-group">
                        <?= form_label(lang('Hr.custom_value'), 'custom_value', ['class' => 'control-label']) ?>
                        <?= form_input([
                            'name' => 'custom_value',
                            'id' => 'custom_value',
                            'class' => 'form-control input-lg',
                            'type' => 'number',
                            'step' => '0.01',
                            'placeholder' => lang('Hr.leave_empty_default')
                        ]) ?>
                        <span class="help-block text-muted"><?= lang('Hr.leave_empty_default') ?></span>
                    </div>
                    
                    <?= form_hidden('employee_id', $employeeId) ?>
                    
                    <div class="form-group" style="margin-top: 20px;">
                        <button type="submit" class="btn btn-success btn-lg btn-block">
                            <span class="glyphicon glyphicon-plus"></span> <?= lang('Hr.assign_rule') ?>
                        </button>
                    </div>
                    
                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('#assign_rule_form').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '<?= site_url('hr/assign_salary_rule') ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                alert(response.message);
                if (response.success) {
                    location.reload();
                }
            }
        });
    });
    
    $('.btn-remove-rule').on('click', function() {
        if (!confirm('<?= lang('Common.confirm_delete') ?>')) return;
        
        $.ajax({
            url: '<?= site_url('hr/remove_salary_rule') ?>',
            type: 'POST',
            data: {
                employee_id: <?= $employeeId ?>,
                rule_id: $(this).data('rule-id')
            },
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
</script>

<?= view('partial/footer') ?>
