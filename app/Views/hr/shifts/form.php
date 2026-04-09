<?php
/**
 * @var array|null $shift
 */
?>

<div id="required_fields_message"><?= lang('Common.fields_required_message') ?></div>
<ul id="error_message_box" class="error_message_box"></ul>

<?= form_open('hr/shift/save', ['id' => 'shift_form', 'class' => 'form-horizontal']) ?>

<div class="form-group">
    <?= form_label(lang('Hr.shift_name'), 'name', ['class' => 'required control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_input([
            'name' => 'name',
            'id' => 'name',
            'class' => 'form-control',
            'value' => $shift['name'] ?? '',
        ]) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.shift_code'), 'code', ['class' => 'required control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_input([
            'name' => 'code',
            'id' => 'code',
            'class' => 'form-control',
            'value' => $shift['code'] ?? '',
        ]) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.start_time'), 'start_time', ['class' => 'required control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_input([
            'name' => 'start_time',
            'id' => 'start_time',
            'class' => 'form-control',
            'value' => $shift['start_time'] ?? '09:00:00',
            'type' => 'time',
        ]) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.end_time'), 'end_time', ['class' => 'required control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_input([
            'name' => 'end_time',
            'id' => 'end_time',
            'class' => 'form-control',
            'value' => $shift['end_time'] ?? '17:00:00',
            'type' => 'time',
        ]) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.working_hours'), 'working_hours', ['class' => 'control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_input([
            'name' => 'working_hours',
            'id' => 'working_hours',
            'class' => 'form-control',
            'value' => $shift['working_hours'] ?? 8.00,
            'type' => 'number',
            'step' => '0.01',
        ]) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.grace_period'), 'grace_period_minutes', ['class' => 'control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_input([
            'name' => 'grace_period_minutes',
            'id' => 'grace_period_minutes',
            'class' => 'form-control',
            'value' => $shift['grace_period_minutes'] ?? 0,
            'type' => 'number',
        ]) ?>
    </div>
</div>

<div class="form-group">
    <div class="col-xs-9 col-xs-offset-3">
        <label class="checkbox-inline">
            <?= form_checkbox('is_night_shift', 1, ($shift['is_night_shift'] ?? false) ? true : false) ?>
            <?= lang('Hr.night_shift') ?>
        </label>
        <label class="checkbox-inline">
            <?= form_checkbox('is_active', 1, ($shift['is_active'] ?? true) ? true : false) ?>
            <?= lang('Common.active') ?>
        </label>
    </div>
</div>

<?= form_hidden('id', $shift['id'] ?? '') ?>

<script type="text/javascript">
$(document).ready(function() {
    $('#shift_form').validate({
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
            code: 'required',
            start_time: 'required',
            end_time: 'required'
        }
    });
});
</script>
