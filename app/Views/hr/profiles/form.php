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
.page-header-bar h1 .glyphicon { margin-right: 12px; color: var(--primary); }
.employee-header {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 25px;
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
.employee-header .avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    font-weight: 600;
}
.employee-header h2 {
    margin: 0 0 5px 0;
    color: #2c3e50;
}
.employee-header p { margin: 0; color: #6c757d; }
.form-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    overflow: hidden;
}
.form-card .panel-heading {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    padding: 15px 25px;
    font-size: 16px;
    font-weight: 600;
}
.form-card .panel-body { padding: 25px; }
.nav-tabs-custom {
    margin-bottom: 25px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    overflow: hidden;
}
.nav-tabs-custom > .nav-tabs {
    margin: 0;
    border: none;
}
.nav-tabs-custom > .nav-tabs > li {
    margin: 0;
}
.nav-tabs-custom > .nav-tabs > li > a {
    border: none;
    border-radius: 0;
    color: #6c757d;
    padding: 15px 25px;
    margin: 0;
}
.nav-tabs-custom > .nav-tabs > li.active > a {
    border: none;
    border-bottom: 3px solid #667eea;
    color: #667eea;
    background: transparent;
}
.nav-tabs-custom > .nav-tabs > li > a:hover {
    background: #f8f9fa;
    color: #667eea;
}
.breadcrumb-bar { margin-bottom: 20px; }
.breadcrumb-bar .breadcrumb {
    margin: 0;
    padding: 10px 0;
    background: transparent;
}
.section-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
}
.section-icon.info { background: #e3f2fd; color: #1565c0; }
.section-icon.emp { background: #e8f5e9; color: #2e7d32; }
.section-icon.salary { background: #fff3cd; color: #856404; }
.section-icon.bank { background: #f3e5f5; color: #6a1b9a; }
</style>

<div class="hr-page">
    <div class="breadcrumb-bar">
        <ol class="breadcrumb">
            <li><a href="<?= site_url() ?>"><?= lang('Module.home') ?></a></li>
            <li><a href="<?= site_url('hr') ?>"><?= lang('Hr.hr_dashboard') ?></a></li>
            <li><a href="<?= site_url('hr/profiles') ?>"><?= lang('Hr.employee_profiles') ?></a></li>
            <li class="active"><?= esc($employee->first_name . ' ' . $employee->last_name) ?></li>
        </ol>
    </div>

    <div class="employee-header">
        <div class="avatar">
            <?= strtoupper(substr($employee->first_name, 0, 1)) ?>
        </div>
        <div>
            <h2><?= esc($employee->first_name . ' ' . $employee->last_name) ?></h2>
            <p><?= esc($employee->email ?? '') ?></p>
        </div>
    </div>

    <?= form_open('hr/save_profile', ['id' => 'profile_form']) ?>

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#basic_info" data-toggle="tab"><span class="glyphicon glyphicon-info-sign"></span> <?= lang('Hr.basic_info') ?></a></li>
            <li><a href="#employment" data-toggle="tab"><span class="glyphicon glyphicon-briefcase"></span> <?= lang('Hr.employment_info') ?></a></li>
            <li><a href="#salary" data-toggle="tab"><span class="glyphicon glyphicon-usd"></span> <?= lang('Hr.salary_info') ?></a></li>
            <li><a href="#banking" data-toggle="tab"><span class="glyphicon glyphicon-credit-card"></span> <?= lang('Hr.banking_info') ?></a></li>
        </ul>
    </div>

    <div class="tab-content">
        <div class="tab-pane active" id="basic_info">
            <div class="form-card">
                <div class="panel-heading">
                    <span class="glyphicon glyphicon-info-sign"></span> <?= lang('Hr.basic_info') ?>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= form_label(lang('Hr.employee_number'), 'employee_number', ['class' => 'control-label']) ?>
                                <?= form_input([
                                    'name' => 'employee_number',
                                    'id' => 'employee_number',
                                    'class' => 'form-control input-lg',
                                    'value' => $profile['employee_number'] ?? '',
                                    'placeholder' => 'e.g., EMP-001'
                                ]) ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= form_label(lang('Hr.department'), 'department_id', ['class' => 'control-label']) ?>
                                <?= form_dropdown('department_id', $department_options, $profile['department_id'] ?? '', ['class' => 'form-control input-lg']) ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= form_label(lang('Hr.position'), 'position_id', ['class' => 'control-label']) ?>
                                <?= form_dropdown('position_id', $position_options, $profile['position_id'] ?? '', ['class' => 'form-control input-lg']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= form_label(lang('Hr.shift'), 'shift_id', ['class' => 'control-label']) ?>
                                <?= form_dropdown('shift_id', $shift_options, $profile['shift_id'] ?? '', ['class' => 'form-control input-lg']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane" id="employment">
            <div class="form-card">
                <div class="panel-heading">
                    <span class="glyphicon glyphicon-briefcase"></span> <?= lang('Hr.employment_info') ?>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= form_label(lang('Hr.hire_date'), 'hire_date', ['class' => 'control-label']) ?>
                                <?= form_input([
                                    'name' => 'hire_date',
                                    'id' => 'hire_date',
                                    'class' => 'form-control input-lg',
                                    'type' => 'date',
                                    'value' => $profile['hire_date'] ?? ''
                                ]) ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= form_label(lang('Hr.termination_date'), 'termination_date', ['class' => 'control-label']) ?>
                                <?= form_input([
                                    'name' => 'termination_date',
                                    'id' => 'termination_date',
                                    'class' => 'form-control input-lg',
                                    'type' => 'date',
                                    'value' => $profile['termination_date'] ?? ''
                                ]) ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= form_label(lang('Hr.employment_type'), 'employment_type', ['class' => 'control-label']) ?>
                                <?= form_dropdown('employment_type', [
                                    'full_time' => lang('Hr.full_time'),
                                    'part_time' => lang('Hr.part_time'),
                                    'contract' => lang('Hr.contract'),
                                    'intern' => lang('Hr.intern')
                                ], $profile['employment_type'] ?? 'full_time', ['class' => 'form-control input-lg']) ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= form_label(lang('Hr.status'), 'employment_status', ['class' => 'control-label']) ?>
                                <?= form_dropdown('employment_status', [
                                    'active' => lang('Hr.status_active'),
                                    'on_leave' => lang('Hr.status_on_leave'),
                                    'suspended' => lang('Hr.status_suspended'),
                                    'terminated' => lang('Hr.status_terminated')
                                ], $profile['employment_status'] ?? 'active', ['class' => 'form-control input-lg']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane" id="salary">
            <div class="form-card">
                <div class="panel-heading">
                    <span class="glyphicon glyphicon-usd"></span> <?= lang('Hr.salary_info') ?>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= form_label(lang('Hr.basic_salary'), 'basic_salary', ['class' => 'control-label']) ?>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <?= form_input([
                                        'name' => 'basic_salary',
                                        'id' => 'basic_salary',
                                        'class' => 'form-control input-lg',
                                        'type' => 'number',
                                        'step' => '0.01',
                                        'value' => $profile['basic_salary'] ?? 0
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= form_label(lang('Hr.hourly_rate'), 'hourly_rate', ['class' => 'control-label']) ?>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <?= form_input([
                                        'name' => 'hourly_rate',
                                        'id' => 'hourly_rate',
                                        'class' => 'form-control input-lg',
                                        'type' => 'number',
                                        'step' => '0.01',
                                        'value' => $profile['hourly_rate'] ?? 0
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane" id="banking">
            <div class="form-card">
                <div class="panel-heading">
                    <span class="glyphicon glyphicon-credit-card"></span> <?= lang('Hr.banking_info') ?>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= form_label(lang('Hr.bank_name'), 'bank_name', ['class' => 'control-label']) ?>
                                <?= form_input([
                                    'name' => 'bank_name',
                                    'id' => 'bank_name',
                                    'class' => 'form-control input-lg',
                                    'value' => $profile['bank_name'] ?? ''
                                ]) ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= form_label(lang('Hr.bank_account'), 'bank_account', ['class' => 'control-label']) ?>
                                <?= form_input([
                                    'name' => 'bank_account',
                                    'id' => 'bank_account',
                                    'class' => 'form-control input-lg',
                                    'value' => $profile['bank_account'] ?? ''
                                ]) ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= form_label(lang('Hr.tax_id'), 'tax_id', ['class' => 'control-label']) ?>
                                <?= form_input([
                                    'name' => 'tax_id',
                                    'id' => 'tax_id',
                                    'class' => 'form-control input-lg',
                                    'value' => $profile['tax_id'] ?? ''
                                ]) ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= form_label(lang('Hr.social_security_number'), 'social_security_number', ['class' => 'control-label']) ?>
                                <?= form_input([
                                    'name' => 'social_security_number',
                                    'id' => 'social_security_number',
                                    'class' => 'form-control input-lg',
                                    'value' => $profile['social_security_number'] ?? ''
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group" style="margin-top: 25px;">
        <button type="submit" class="btn btn-success btn-lg">
            <span class="glyphicon glyphicon-save"></span> <?= lang('Common.submit') ?>
        </button>
        <a href="<?= site_url('hr/profiles') ?>" class="btn btn-default btn-lg">
            <?= lang('Common.cancel') ?>
        </a>
    </div>

    <?= form_hidden('employee_id', $employee->person_id) ?>

    <?= form_close() ?>
</div>

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
