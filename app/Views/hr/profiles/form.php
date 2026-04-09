<?php
/**
 * @var object $employee
 * @var array|null $profile
 * @var array $department_options
 * @var array $position_options
 * @var array $shift_options
 */
?>

<div id="required_fields_message"><?= lang('Common.fields_required_message') ?></div>
<ul id="error_message_box" class="error_message_box"></ul>

<?= form_open('hr/save_profile', ['id' => 'profile_form', 'class' => 'form-horizontal']) ?>

<div class="form-group">
    <?= form_label(lang('Hr.employee_number'), 'employee_number', ['class' => 'control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_input([
            'name' => 'employee_number',
            'id' => 'employee_number',
            'class' => 'form-control',
            'value' => $profile['employee_number'] ?? '',
            'placeholder' => 'e.g., EMP-001'
        ]) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.department'), 'department_id', ['class' => 'control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_dropdown('department_id', ['' => lang('Common.none_selected_text')] + $department_options, $profile['department_id'] ?? '', ['class' => 'form-control']) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.position'), 'position_id', ['class' => 'control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_dropdown('position_id', ['' => lang('Common.none_selected_text')] + $position_options, $profile['position_id'] ?? '', ['class' => 'form-control']) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.shift'), 'shift_id', ['class' => 'control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_dropdown('shift_id', ['' => lang('Common.none_selected_text')] + $shift_options, $profile['shift_id'] ?? '', ['class' => 'form-control']) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.hire_date'), 'hire_date', ['class' => 'control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_input([
            'name' => 'hire_date',
            'id' => 'hire_date',
            'class' => 'form-control',
            'type' => 'date',
            'value' => $profile['hire_date'] ?? ''
        ]) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.termination_date'), 'termination_date', ['class' => 'control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_input([
            'name' => 'termination_date',
            'id' => 'termination_date',
            'class' => 'form-control',
            'type' => 'date',
            'value' => $profile['termination_date'] ?? ''
        ]) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.employment_type'), 'employment_type', ['class' => 'control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_dropdown('employment_type', [
            'full_time' => lang('Hr.full_time'),
            'part_time' => lang('Hr.part_time'),
            'contract' => lang('Hr.contract'),
            'intern' => lang('Hr.intern')
        ], $profile['employment_type'] ?? 'full_time', ['class' => 'form-control']) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.status'), 'employment_status', ['class' => 'control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_dropdown('employment_status', [
            'active' => lang('Hr.status_active'),
            'on_leave' => lang('Hr.status_on_leave'),
            'suspended' => lang('Hr.status_suspended'),
            'terminated' => lang('Hr.status_terminated')
        ], $profile['employment_status'] ?? 'active', ['class' => 'form-control']) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.basic_salary'), 'basic_salary', ['class' => 'control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_input([
            'name' => 'basic_salary',
            'id' => 'basic_salary',
            'class' => 'form-control',
            'type' => 'number',
            'step' => '0.01',
            'value' => $profile['basic_salary'] ?? 0
        ]) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.hourly_rate'), 'hourly_rate', ['class' => 'control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_input([
            'name' => 'hourly_rate',
            'id' => 'hourly_rate',
            'class' => 'form-control',
            'type' => 'number',
            'step' => '0.01',
            'value' => $profile['hourly_rate'] ?? 0
        ]) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.bank_name'), 'bank_name', ['class' => 'control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_input([
            'name' => 'bank_name',
            'id' => 'bank_name',
            'class' => 'form-control',
            'value' => $profile['bank_name'] ?? ''
        ]) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.bank_account'), 'bank_account', ['class' => 'control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_input([
            'name' => 'bank_account',
            'id' => 'bank_account',
            'class' => 'form-control',
            'value' => $profile['bank_account'] ?? ''
        ]) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.tax_id'), 'tax_id', ['class' => 'control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_input([
            'name' => 'tax_id',
            'id' => 'tax_id',
            'class' => 'form-control',
            'value' => $profile['tax_id'] ?? ''
        ]) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.social_security_number'), 'social_security_number', ['class' => 'control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_input([
            'name' => 'social_security_number',
            'id' => 'social_security_number',
            'class' => 'form-control',
            'value' => $profile['social_security_number'] ?? ''
        ]) ?>
    </div>
</div>

<?= form_hidden('employee_id', $employee->person_id) ?>

<script type="text/javascript">
$(document).ready(function() {
    $('#profile_form').validate({
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
        }
    });
});
</script>
