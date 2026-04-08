<?php
/**
 * @var array $leave_types
 */
?>

<?= view('partial/header') ?>

<div id="title_bar" class="btn-toolbar">
    <h2 class="pull-left"><?= lang('Hr.leave_types') ?></h2>
    <div class="pull-right">
        <a href="<?= site_url('hr/leave_types') ?>" class="btn btn-default btn-sm">
            <span class="glyphicon glyphicon-refresh"></span>
        </a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th><?= lang('Common.id') ?></th>
                <th><?= lang('Hr.leave_type_name') ?></th>
                <th><?= lang('Hr.code') ?></th>
                <th><?= lang('Hr.paid_unpaid') ?></th>
                <th><?= lang('Hr.default_days') ?></th>
                <th><?= lang('Common.status') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($leave_types as $type): ?>
                <tr>
                    <td><?= $type['id'] ?></td>
                    <td><?= esc($type['name']) ?></td>
                    <td><?= esc($type['code']) ?></td>
                    <td>
                        <?php if ($type['paid_unpaid'] === 'paid'): ?>
                            <span class="label label-success"><?= lang('Hr.paid') ?></span>
                        <?php else: ?>
                            <span class="label label-default"><?= lang('Hr.unpaid') ?></span>
                        <?php endif; ?>
                    </td>
                    <td><?= $type['default_days'] ?></td>
                    <td>
                        <?php if ($type['is_active']): ?>
                            <span class="label label-success"><?= lang('Common.active') ?></span>
                        <?php else: ?>
                            <span class="label label-default"><?= lang('Common.inactive') ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($leave_types)): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted"><?= lang('Common.no_data') ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><?= lang('Hr.add_leave_type') ?></h3>
    </div>
    <div class="panel-body">
        <?= form_open('hr/save_leave_type', ['id' => 'leave_type_form', 'class' => 'form-horizontal']) ?>
        
        <div class="form-group">
            <?= form_label(lang('Hr.leave_type_name'), 'name', ['class' => 'control-label col-xs-3']) ?>
            <div class="col-xs-8">
                <?= form_input(['name' => 'name', 'class' => 'form-control', 'required' => true]) ?>
            </div>
        </div>
        
        <div class="form-group">
            <?= form_label(lang('Hr.code'), 'code', ['class' => 'control-label col-xs-3']) ?>
            <div class="col-xs-8">
                <?= form_input(['name' => 'code', 'class' => 'form-control', 'required' => true]) ?>
            </div>
        </div>
        
        <div class="form-group">
            <?= form_label(lang('Hr.paid_unpaid'), 'paid_unpaid', ['class' => 'control-label col-xs-3']) ?>
            <div class="col-xs-8">
                <?= form_dropdown('paid_unpaid', ['paid' => lang('Hr.paid'), 'unpaid' => lang('Hr.unpaid')], 'unpaid', ['class' => 'form-control']) ?>
            </div>
        </div>
        
        <div class="form-group">
            <?= form_label(lang('Hr.default_days'), 'default_days', ['class' => 'control-label col-xs-3']) ?>
            <div class="col-xs-8">
                <?= form_input(['name' => 'default_days', 'class' => 'form-control', 'type' => 'number', 'step' => '0.5', 'value' => 0]) ?>
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
    $('#leave_type_form').validate({
        submitHandler: function(form) {
            $(form).ajaxSubmit({
                success: function(response) {
                    if (response.success) location.reload();
                    alert(response.message);
                },
                dataType: 'json'
            });
        }
    });
});
</script>

<?= view('partial/footer') ?>
