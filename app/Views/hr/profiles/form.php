<?php
/**
 * @var object $employee
 * @var array|null $profile
 * @var array $department_options
 * @var array $position_options
 * @var array $shift_options
 */
?>

<?= view('partial/header') ?>

<div id="title_bar" class="btn-toolbar">
    <h2 class="pull-left"><?= lang('Hr.employee_profile') ?>: <?= esc($employee->first_name . ' ' . $employee->last_name) ?></h2>
</div>

<?= form_open('hr/save_profile', ['id' => 'profile_form', 'class' => 'form-horizontal']) ?>

<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active">
        <a href="#basic_info" aria-controls="basic_info" role="tab" data-toggle="tab"><?= lang('Hr.basic_info') ?></a>
    </li>
    <li role="presentation">
        <a href="#employment" aria-controls="employment" role="tab" data-toggle="tab"><?= lang('Hr.employment_info') ?></a>
    </li>
    <li role="presentation">
        <a href="#salary" aria-controls="salary" role="tab" data-toggle="tab"><?= lang('Hr.salary_info') ?></a>
    </li>
    <li role="presentation">
        <a href="#banking" aria-controls="banking" role="tab" data-toggle="tab"><?= lang('Hr.banking_info') ?></a>
    </li>
</ul>

<div class="tab-content" style="margin-top: 20px;">
    <div role="tabpanel" class="tab-pane active" id="basic_info">
        <fieldset>
            <div class="form-group">
                <?= form_label(lang('Hr.employee_number'), 'employee_number', ['class' => 'control-label col-xs-3']) ?>
                <div class="col-xs-8">
                    <?= form_input([
                        'name' => 'employee_number',
                        'id' => 'employee_number',
                        'class' => 'form-control',
                        'value' => $profile['employee_number'] ?? ''
                    ]) ?>
                </div>
            </div>
            
            <div class="form-group">
                <?= form_label(lang('Hr.department'), 'department_id', ['class' => 'control-label col-xs-3']) ?>
                <div class="col-xs-8">
                    <?= form_dropdown('department_id', $department_options, $profile['department_id'] ?? '', ['class' => 'form-control']) ?>
                </div>
            </div>
            
            <div class="form-group">
                <?= form_label(lang('Hr.position'), 'position_id', ['class' => 'control-label col-xs-3']) ?>
                <div class="col-xs-8">
                    <?= form_dropdown('position_id', $position_options, $profile['position_id'] ?? '', ['class' => 'form-control']) ?>
                </div>
            </div>
            
            <div class="form-group">
                <?= form_label(lang('Hr.shift'), 'shift_id', ['class' => 'control-label col-xs-3']) ?>
                <div class="col-xs-8">
                    <?= form_dropdown('shift_id', $shift_options, $profile['shift_id'] ?? '', ['class' => 'form-control']) ?>
                </div>
            </div>
        </fieldset>
    </div>

    <div role="tabpanel" class="tab-pane" id="employment">
        <fieldset>
            <div class="form-group">
                <?= form_label(lang('Hr.hire_date'), 'hire_date', ['class' => 'control-label col-xs-3']) ?>
                <div class="col-xs-8">
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
                <div class="col-xs-8">
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
                <div class="col-xs-8">
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
                <div class="col-xs-8">
                    <?= form_dropdown('employment_status', [
                        'active' => lang('Hr.status_active'),
                        'on_leave' => lang('Hr.status_on_leave'),
                        'suspended' => lang('Hr.status_suspended'),
                        'terminated' => lang('Hr.status_terminated')
                    ], $profile['employment_status'] ?? 'active', ['class' => 'form-control']) ?>
                </div>
            </div>
        </fieldset>
    </div>

    <div role="tabpanel" class="tab-pane" id="salary">
        <fieldset>
            <div class="form-group">
                <?= form_label(lang('Hr.basic_salary'), 'basic_salary', ['class' => 'control-label col-xs-3']) ?>
                <div class="col-xs-8">
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
                <div class="col-xs-8">
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
        </fieldset>
    </div>

    <div role="tabpanel" class="tab-pane" id="banking">
        <fieldset>
            <div class="form-group">
                <?= form_label(lang('Hr.bank_name'), 'bank_name', ['class' => 'control-label col-xs-3']) ?>
                <div class="col-xs-8">
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
                <div class="col-xs-8">
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
                <div class="col-xs-8">
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
                <div class="col-xs-8">
                    <?= form_input([
                        'name' => 'social_security_number',
                        'id' => 'social_security_number',
                        'class' => 'form-control',
                        'value' => $profile['social_security_number'] ?? ''
                    ]) ?>
                </div>
            </div>
        </fieldset>
    </div>
</div>

<div class="form-group" style="margin-top: 20px;">
    <div class="col-xs-offset-3 col-xs-8">
        <button type="submit" class="btn btn-primary"><?= lang('Common.submit') ?></button>
        <a href="<?= site_url('hr/profiles') ?>" class="btn btn-default"><?= lang('Common.cancel') ?></a>
    </div>
</div>

<?= form_hidden('employee_id', $employee->person_id) ?>

<?= form_close() ?>

<script type="text/javascript">
$(document).ready(function() {
    $('#profile_form').validate({
        submitHandler: function(form) {
            $(form).ajaxSubmit({
                success: function(response) {
                    alert(response.message);
                    if (response.success) {
                        window.location.href = '<?= site_url('hr/profiles') ?>';
                    }
                },
                dataType: 'json'
            });
        }
    });
});
</script>

<?= view('partial/footer') ?>
