<?php
/**
 * @var array $groups
 */
?>

<?= view('partial/header') ?>

<div id="title_bar" class="btn-toolbar">
    <h2 class="pull-left"><?= lang('Hr.salary_rule_groups') ?></h2>
    <div class="pull-right">
        <a href="<?= site_url('hr/salary_rules') ?>" class="btn btn-default btn-sm">
            <span class="glyphicon glyphicon-arrow-left"></span> <?= lang('Common.back') ?>
        </a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th><?= lang('Common.id') ?></th>
                <th><?= lang('Hr.group_name') ?></th>
                <th><?= lang('Hr.group_type') ?></th>
                <th><?= lang('Hr.calculation_order') ?></th>
                <th><?= lang('Common.status') ?></th>
                <th><?= lang('Common.actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($groups as $group): ?>
                <tr>
                    <td><?= $group['id'] ?></td>
                    <td><?= esc($group['name']) ?></td>
                    <td>
                        <?php if ($group['type'] === 'earning'): ?>
                            <span class="label label-success"><?= lang('Hr.earnings') ?></span>
                        <?php else: ?>
                            <span class="label label-danger"><?= lang('Hr.deductions') ?></span>
                        <?php endif; ?>
                    </td>
                    <td><?= $group['calculation_order'] ?></td>
                    <td>
                        <?php if ($group['is_active']): ?>
                            <span class="label label-success"><?= lang('Common.active') ?></span>
                        <?php else: ?>
                            <span class="label label-default"><?= lang('Common.inactive') ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <button class="btn btn-xs btn-warning btn-edit-group" data-id="<?= $group['id'] ?>" 
                                data-name="<?= esc($group['name']) ?>" data-type="<?= $group['type'] ?>" 
                                data-order="<?= $group['calculation_order'] ?>" data-active="<?= $group['is_active'] ?>">
                            <span class="glyphicon glyphicon-edit"></span>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="panel panel-default" style="margin-top: 20px;">
    <div class="panel-heading">
        <h3 class="panel-title"><?= lang('Hr.add_group') ?></h3>
    </div>
    <div class="panel-body">
        <?= form_open('hr/save_salary_rule_group', ['id' => 'group_form', 'class' => 'form-horizontal']) ?>
        
        <div class="form-group">
            <?= form_label(lang('Hr.group_name'), 'name', ['class' => 'control-label col-xs-3']) ?>
            <div class="col-xs-8">
                <?= form_input([
                    'name' => 'name',
                    'id' => 'name',
                    'class' => 'form-control'
                ]) ?>
            </div>
        </div>
        
        <div class="form-group">
            <?= form_label(lang('Hr.group_type'), 'type', ['class' => 'control-label col-xs-3']) ?>
            <div class="col-xs-8">
                <?= form_dropdown('type', [
                    'earning' => lang('Hr.earnings'),
                    'deduction' => lang('Hr.deductions')
                ], 'earning', ['class' => 'form-control']) ?>
            </div>
        </div>
        
        <div class="form-group">
            <?= form_label(lang('Hr.calculation_order'), 'calculation_order', ['class' => 'control-label col-xs-3']) ?>
            <div class="col-xs-8">
                <?= form_input([
                    'name' => 'calculation_order',
                    'id' => 'calculation_order',
                    'class' => 'form-control',
                    'type' => 'number',
                    'value' => 0
                ]) ?>
            </div>
        </div>
        
        <div class="form-group">
            <div class="col-xs-offset-3 col-xs-8">
                <div class="checkbox">
                    <label>
                        <?= form_checkbox('is_active', 1, true) ?>
                        <?= lang('Common.active') ?>
                    </label>
                </div>
            </div>
        </div>
        
        <?= form_hidden('id', '') ?>
        
        <div class="form-group">
            <div class="col-xs-offset-3 col-xs-8">
                <button type="submit" class="btn btn-primary"><?= lang('Common.submit') ?></button>
            </div>
        </div>
        
        <?= form_close() ?>
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
