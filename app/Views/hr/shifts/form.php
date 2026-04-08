<?php
/**
 * @var array|null $shift
 */
?>

<?= form_open('hr/save_shift', ['id' => 'shift_form', 'class' => 'form-horizontal']) ?>

<div class="form-group">
    <?= form_label(lang('Hr.shift_name'), 'name', ['class' => 'control-label col-xs-3 required']) ?>
    <div class="col-xs-8">
        <?= form_input([
            'name' => 'name',
            'id' => 'name',
            'class' => 'form-control',
            'value' => $shift['name'] ?? ''
        ]) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.code'), 'code', ['class' => 'control-label col-xs-3 required']) ?>
    <div class="col-xs-8">
        <?= form_input([
            'name' => 'code',
            'id' => 'code',
            'class' => 'form-control',
            'value' => $shift['code'] ?? ''
        ]) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.start_time'), 'start_time', ['class' => 'control-label col-xs-3 required']) ?>
    <div class="col-xs-8">
        <?= form_input([
            'name' => 'start_time',
            'id' => 'start_time',
            'class' => 'form-control',
            'type' => 'time',
            'value' => $shift['start_time'] ?? '09:00:00'
        ]) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.end_time'), 'end_time', ['class' => 'control-label col-xs-3 required']) ?>
    <div class="col-xs-8">
        <?= form_input([
            'name' => 'end_time',
            'id' => 'end_time',
            'class' => 'form-control',
            'type' => 'time',
            'value' => $shift['end_time'] ?? '17:00:00'
        ]) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.working_hours'), 'working_hours', ['class' => 'control-label col-xs-3']) ?>
    <div class="col-xs-8">
        <?= form_input([
            'name' => 'working_hours',
            'id' => 'working_hours',
            'class' => 'form-control',
            'type' => 'number',
            'step' => '0.01',
            'value' => $shift['working_hours'] ?? 8.00
        ]) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.grace_period'), 'grace_period_minutes', ['class' => 'control-label col-xs-3']) ?>
    <div class="col-xs-8">
        <?= form_input([
            'name' => 'grace_period_minutes',
            'id' => 'grace_period_minutes',
            'class' => 'form-control',
            'type' => 'number',
            'value' => $shift['grace_period_minutes'] ?? 0
        ]) ?>
    </div>
</div>

<div class="form-group">
    <div class="col-xs-offset-3 col-xs-8">
        <div class="checkbox">
            <label>
                <?= form_checkbox('is_night_shift', 1, ($shift['is_night_shift'] ?? false) ? true : false) ?>
                <?= lang('Hr.night_shift') ?>
            </label>
        </div>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Common.active'), 'is_active', ['class' => 'control-label col-xs-3']) ?>
    <div class="col-xs-8">
        <div class="checkbox">
            <label>
                <?= form_checkbox('is_active', 1, ($shift['is_active'] ?? true) ? true : false) ?>
            </label>
        </div>
    </div>
</div>

<?= form_hidden('id', $shift['id'] ?? '') ?>

<?= form_close() ?>

<script type="text/javascript">
$(document).ready(function() {
    $('#shift_form').validate({
        submitHandler: function(form) {
            $(form).ajaxSubmit({
                success: function(response) {
                    dialog_support.hide();
                    table_support.handle_submit("hr/shifts", response);
                },
                dataType: 'json'
            });
        },
        rules: {
            name: 'required',
            code: 'required',
            start_time: 'required',
            end_time: 'required'
        },
        messages: {
            name: "<?= lang('Common.required') ?>",
            code: "<?= lang('Common.required') ?>",
            start_time: "<?= lang('Common.required') ?>",
            end_time: "<?= lang('Common.required') ?>"
        }
    });
});
</script>
