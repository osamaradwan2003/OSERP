<?php
/**
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
.type-badge {
    display: inline-flex;
    align-items: center;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}
.type-badge.earning { background: #d4edda; color: #155724; }
.type-badge.deduction { background: #f8d7da; color: #721c24; }
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
.order-badge {
    background: #e3f2fd;
    color: #1565c0;
    padding: 4px 10px;
    border-radius: 4px;
    font-weight: 600;
}
.group-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    overflow: hidden;
}
.group-card .panel-heading {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    color: #fff;
    padding: 20px 25px;
    font-size: 18px;
    font-weight: 600;
}
.group-card .panel-body {
    padding: 25px;
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
            <li><a href="<?= site_url('hr/salary_rules') ?>"><?= lang('Hr.salary_rules') ?></a></li>
            <li class="active"><?= lang('Hr.salary_rule_groups') ?></li>
        </ol>
    </div>

    <div class="page-header-bar">
        <h1><span class="glyphicon glyphicon-folder-open"></span> <?= lang('Hr.salary_rule_groups') ?></h1>
        <a href="<?= site_url('hr/salary_rules') ?>" class="btn btn-outline btn-default">
            <span class="glyphicon glyphicon-arrow-left"></span> <?= lang('Common.back') ?>
        </a>
    </div>

    <div class="hr-table" style="margin-bottom: 30px;">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th style="width: 60px;"><?= lang('Common.id') ?></th>
                    <th><?= lang('Hr.group_name') ?></th>
                    <th style="width: 130px;"><?= lang('Hr.group_type') ?></th>
                    <th style="width: 140px;"><?= lang('Hr.calculation_order') ?></th>
                    <th style="width: 100px;"><?= lang('Common.status') ?></th>
                    <th style="width: 80px;"><?= lang('Common.actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($groups as $group): ?>
                    <tr>
                        <td class="id-cell"><?= str_pad($group['id'], 4, '0', STR_PAD_LEFT) ?></td>
                        <td><strong><?= esc($group['name']) ?></strong></td>
                        <td>
                            <span class="type-badge <?= $group['type'] ?>">
                                <?= $group['type'] === 'earning' ? lang('Hr.earnings') : lang('Hr.deductions') ?>
                            </span>
                        </td>
                        <td><span class="order-badge">#<?= $group['calculation_order'] ?></span></td>
                        <td>
                            <?php if ($group['is_active']): ?>
                                <span class="status-badge active"><?= lang('Common.active') ?></span>
                            <?php else: ?>
                                <span class="status-badge inactive"><?= lang('Common.inactive') ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="btn btn-warning btn-xs btn-edit-group" data-id="<?= $group['id'] ?>" 
                                    data-name="<?= esc($group['name']) ?>" data-type="<?= $group['type'] ?>" 
                                    data-order="<?= $group['calculation_order'] ?>" data-active="<?= $group['is_active'] ?>">
                                <span class="glyphicon glyphicon-pencil"></span>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($groups)): ?>
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

    <div class="group-card">
        <div class="panel-heading">
            <span class="glyphicon glyphicon-plus"></span> <?= lang('Hr.add_group') ?>
        </div>
        <div class="panel-body">
            <?= form_open('hr/save_salary_rule_group', ['id' => 'group_form', 'class' => 'form-horizontal']) ?>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?= form_label(lang('Hr.group_name'), 'name', ['class' => 'control-label']) ?>
                        <?= form_input([
                            'name' => 'name',
                            'id' => 'name',
                            'class' => 'form-control input-lg',
                            'placeholder' => lang('Hr.group_name')
                        ]) ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <?= form_label(lang('Hr.group_type'), 'type', ['class' => 'control-label']) ?>
                        <?= form_dropdown('type', [
                            'earning' => lang('Hr.earnings'),
                            'deduction' => lang('Hr.deductions')
                        ], 'earning', ['class' => 'form-control input-lg']) ?>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?= form_label(lang('Hr.calculation_order'), 'calculation_order', ['class' => 'control-label']) ?>
                        <?= form_input([
                            'name' => 'calculation_order',
                            'id' => 'calculation_order',
                            'class' => 'form-control input-lg',
                            'type' => 'number',
                            'value' => 0
                        ]) ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div class="checkbox" style="margin-top: 8px;">
                            <label class="checkbox-inline">
                                <?= form_checkbox('is_active', 1, true) ?>
                                <?= lang('Common.active') ?>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <?= form_hidden('id', '') ?>
            
            <div class="form-group" style="margin-top: 20px;">
                <button type="submit" class="btn btn-success btn-lg">
                    <span class="glyphicon glyphicon-save"></span> <?= lang('Common.submit') ?>
                </button>
            </div>
            
            <?= form_close() ?>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('#group_form').validate({
        submitHandler: function(form) {
            $(form).ajaxSubmit({
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    }
                },
                dataType: 'json'
            });
        },
        rules: {
            name: 'required'
        }
    });
});
</script>

<?= view('partial/footer') ?>
