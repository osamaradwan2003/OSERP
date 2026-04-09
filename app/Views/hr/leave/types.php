<?php
/**
 * @var array|null $leave_type
 */
?>

<div id="required_fields_message"><?= lang('Common.fields_required_message') ?></div>
<ul id="error_message_box" class="error_message_box"></ul>

<?= form_open('hr/leave_type/save', ['id' => 'leave_type_form', 'class' => 'form-horizontal']) ?>

<div class="form-group">
    <?= form_label(lang('Hr.leave_name'), 'name', ['class' => 'required control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_input([
            'name' => 'name',
            'id' => 'name',
            'class' => 'form-control',
            'value' => $leave_type['name'] ?? '',
        ]) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.leave_code'), 'code', ['class' => 'required control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_input([
            'name' => 'code',
            'id' => 'code',
            'class' => 'form-control',
            'value' => $leave_type['code'] ?? '',
        ]) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.paid_unpaid'), 'paid_unpaid', ['class' => 'control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_dropdown('paid_unpaid', [
            'paid' => lang('Hr.paid'),
            'unpaid' => lang('Hr.unpaid'),
        ], $leave_type['paid_unpaid'] ?? 'unpaid', ['class' => 'form-control']) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.default_days'), 'default_days', ['class' => 'control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_input([
            'name' => 'default_days',
            'id' => 'default_days',
            'class' => 'form-control',
            'value' => (string)($leave_type['default_days'] ?? '0'),
            'type' => 'number',
            'step' => '0.5',
        ]) ?>
    </div>
</div>

<div class="form-group">
    <div class="col-xs-9 col-xs-offset-3">
        <label class="checkbox">
            <?= form_checkbox('is_active', 1, ($leave_type['is_active'] ?? true) ? true : false) ?>
            <?= lang('Common.active') ?>
        </label>
    </div>
</div>

<?= form_hidden('id', (string)($leave_type['id'] ?? 0)) ?>

<script type="text/javascript">
$(document).ready(function() {
    $('#leave_type_form').validate({
        submitHandler: function(form) {
            $(form).ajaxSubmit({
                success: function(response) {
                    dialog_support.hide();
                    if (response.success) {
                        window.location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                dataType: 'json'
            });
        },
        rules: {
            name: 'required',
            code: 'required'
        }
    });
});
</script>
