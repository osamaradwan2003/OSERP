<?php
/**
 * @var array $employee
 * @var array $profile
 * @var array $attachments
 * @var array $attendance_history
 * @var array $leave_history
 * @var array $recent_salary
 * @var array $department_options
 * @var array $position_options
 * @var array $shift_options
 */
?>

<?= view('partial/header') ?>

<style>
.hr-page { padding: 20px 0; }
.breadcrumb-bar { margin-bottom: 20px; }
.breadcrumb-bar .breadcrumb { margin: 0; padding: 10px 0; background: transparent; }
.info-value { padding: 8px 0; }
.info-row { border-bottom: 1px solid #eee; }
.info-row:last-child { border-bottom: none; }
.info-label { font-weight: 600; color: #555; padding: 8px 0; }
.info-display { padding: 8px 0; min-height: 30px; }
.edit-mode .info-display { display: none; }
.edit-mode .info-edit { display: block !important; }
.info-edit { display: none; }
.info-edit input, .info-edit select, .info-edit textarea { width: 100%; padding: 6px 8px; border: 1px solid #ccc; border-radius: 4px; }
.info-edit textarea { min-height: 60px; resize: vertical; }
.section-header { background: #f8f9fa; padding: 10px 15px; font-weight: 600; margin-bottom: 10px; border-radius: 4px; }
</style>

<div class="hr-page">
    <div class="breadcrumb-bar">
        <ol class="breadcrumb">
            <li><a href="<?= site_url() ?>"><?= lang('Module.home') ?></a></li>
            <li><a href="<?= site_url('hr') ?>"><?= lang('Hr.hr_dashboard') ?></a></li>
            <li><a href="<?= site_url('hr/employees') ?>"><?= lang('Hr.employees') ?></a></li>
            <li class="active"><?= esc($employee['first_name'] . ' ' . $employee['last_name']) ?></li>
        </ol>
    </div>

    <?= form_open('hr/employee/save/' . $employee['person_id'], ['id' => 'employee_form']) ?>
    
    <div class="row">
    <div class="col-md-12">
        <div class="btn-group" style="margin-bottom: 15px;">
            <a href="<?= site_url('hr/employees') ?>" class="btn btn-default">
                <span class="glyphicon glyphicon-arrow-left"></span> <?= lang('Common.back') ?>
            </a>
            <button type="button" id="edit_btn" class="btn btn-primary">
                <span class="glyphicon glyphicon-edit"></span> <?= lang('Common.edit') ?>
            </button>
            <button type="submit" id="submit_btn" class="btn btn-success" style="display: none;">
                <span class="glyphicon glyphicon-floppy-disk"></span> <?= lang('Common.submit') ?>
            </button>
            <button type="button" id="cancel_btn" class="btn btn-default" style="display: none;">
                <span class="glyphicon glyphicon-remove"></span> <?= lang('Common.cancel') ?>
            </button>
            <div class="btn-group">
                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                    <span class="glyphicon glyphicon-file"></span> <?= lang('Hr.export_pdf') ?> <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a href="<?= site_url('hr/employee/pdf_preview/' . $employee['person_id']) ?>" target="_blank">
                            <span class="glyphicon glyphicon-eye-open"></span> <?= lang('Hr.preview_pdf') ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?= site_url('hr/employee/pdf/' . $employee['person_id']) ?>">
                            <span class="glyphicon glyphicon-download"></span> <?= lang('Hr.download_pdf') ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="panel panel-default" id="info_panel">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <span class="glyphicon glyphicon-user"></span>
                    <?= esc($employee['first_name'] . ' ' . $employee['last_name']) ?>
                    <?php if (!empty($profile['employee_number'])): ?>
                        <small>(<?= lang('Hr.employee_number') ?>: <?= esc($profile['employee_number']) ?>)</small>
                    <?php endif; ?>
                </h3>
            </div>
            <div class="panel-body">
                <ul class="nav nav-tabs" id="employeeTabs">
                    <li class="active"><a href="#personal" data-toggle="tab"><?= lang('Hr.personal_info') ?></a></li>
                    <li><a href="#hr_profile" data-toggle="tab"><?= lang('Hr.hr_profile') ?></a></li>
                    <li><a href="#documents" data-toggle="tab"><?= lang('Hr.documents') ?> (<?= count($attachments) ?>)</a></li>
                    <li><a href="#attendance" data-toggle="tab"><?= lang('Hr.attendance') ?></a></li>
                    <li><a href="#leave" data-toggle="tab"><?= lang('Hr.leave_requests') ?></a></li>
                    <li><a href="#salary" data-toggle="tab"><?= lang('Hr.salary_info') ?></a></li>
                </ul>
                
                <div class="tab-content" style="margin-top: 15px;">
                    <!-- Personal Information Tab -->
                    <div class="tab-pane fade in active" id="personal">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-row">
                                    <div class="info-label"><?= lang('Hr.first_name') ?></div>
                                    <div class="info-display"><?= esc($employee['first_name']) ?: '-' ?></div>
                                    <div class="info-edit"><?= form_input('first_name', $employee['first_name'], ['class' => 'form-control']) ?></div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label"><?= lang('Hr.last_name') ?></div>
                                    <div class="info-display"><?= esc($employee['last_name']) ?: '-' ?></div>
                                    <div class="info-edit"><?= form_input('last_name', $employee['last_name'], ['class' => 'form-control']) ?></div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label"><?= lang('Hr.email') ?></div>
                                    <div class="info-display"><?= esc($employee['email']) ?: '-' ?></div>
                                    <div class="info-edit"><?= form_input('email', $employee['email'] ?? '', ['class' => 'form-control', 'type' => 'email']) ?></div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label"><?= lang('Hr.phone') ?></div>
                                    <div class="info-display"><?= esc($employee['phone_number']) ?: '-' ?></div>
                                    <div class="info-edit"><?= form_input('phone_number', $employee['phone_number'] ?? '', ['class' => 'form-control']) ?></div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label"><?= lang('Hr.address') ?></div>
                                    <div class="info-display"><?= nl2br(esc($employee['address_1'])) ?: '-' ?></div>
                                    <div class="info-edit"><?= form_textarea('address', $employee['address_1'] ?? '', ['class' => 'form-control', 'rows' => 2]) ?></div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label"><?= lang('Hr.city') ?></div>
                                    <div class="info-display"><?= esc($employee['city']) ?: '-' ?></div>
                                    <div class="info-edit"><?= form_input('city', $employee['city'] ?? '', ['class' => 'form-control']) ?></div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label"><?= lang('Hr.state') ?></div>
                                    <div class="info-display"><?= esc($employee['state']) ?: '-' ?></div>
                                    <div class="info-edit"><?= form_input('state', $employee['state'] ?? '', ['class' => 'form-control']) ?></div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label"><?= lang('Hr.zip') ?></div>
                                    <div class="info-display"><?= esc($employee['zip']) ?: '-' ?></div>
                                    <div class="info-edit"><?= form_input('zip', $employee['zip'] ?? '', ['class' => 'form-control']) ?></div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label"><?= lang('Hr.country') ?></div>
                                    <div class="info-display"><?= esc($employee['country']) ?: '-' ?></div>
                                    <div class="info-edit"><?= form_dropdown('country', ['' => lang('Hr.select_country')] + ($countries ?? []), $employee['country'] ?? '', ['class' => 'form-control']) ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="section-header"><?= lang('Hr.login_info') ?></div>
                                <div class="info-row">
                                    <div class="info-label"><?= lang('Hr.username') ?></div>
                                    <div class="info-display"><?= esc($employee['username']) ?: '-' ?></div>
                                    <div class="info-edit text-muted" style="padding: 8px 0;"><?= lang('Hr.login_info_not_editable') ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- HR Profile Tab -->
                    <div class="tab-pane fade" id="hr_profile">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="section-header"><?= lang('Hr.employment_info') ?></div>
                                <div class="info-row">
                                    <div class="info-label"><?= lang('Hr.employee_number') ?></div>
                                    <div class="info-display"><?= esc($profile['employee_number']) ?: '-' ?></div>
                                    <div class="info-edit"><?= form_input('employee_number', $profile['employee_number'] ?? '', ['class' => 'form-control']) ?></div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label"><?= lang('Hr.department') ?></div>
                                    <div class="info-display"><?= esc($profile['department_name'] ?? '-') ?></div>
                                    <div class="info-edit"><?= form_dropdown('department_id', ['' => lang('Common.none_selected_text')] + ($department_options ?? []), (string)($profile['department_id'] ?? ''), ['class' => 'form-control']) ?></div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label"><?= lang('Hr.position') ?></div>
                                    <div class="info-display"><?= esc($profile['position_name'] ?? '-') ?></div>
                                    <div class="info-edit"><?= form_dropdown('position_id', ['' => lang('Common.none_selected_text')] + ($position_options ?? []), (string)($profile['position_id'] ?? ''), ['class' => 'form-control']) ?></div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label"><?= lang('Hr.shift') ?></div>
                                    <div class="info-display"><?= esc($profile['shift_name'] ?? '-') ?></div>
                                    <div class="info-edit"><?= form_dropdown('shift_id', ['' => lang('Common.none_selected_text')] + ($shift_options ?? []), (string)($profile['shift_id'] ?? ''), ['class' => 'form-control']) ?></div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label"><?= lang('Hr.hire_date') ?></div>
                                    <div class="info-display"><?= $profile['hire_date'] ? date('M d, Y', strtotime($profile['hire_date'])) : '-' ?></div>
                                    <div class="info-edit"><?= form_input('hire_date', $profile['hire_date'] ?? '', ['class' => 'form-control', 'type' => 'date']) ?></div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label"><?= lang('Hr.employment_type') ?></div>
                                    <div class="info-display"><?= lang('Hr.' . ($profile['employment_type'] ?? 'full_time')) ?></div>
                                    <div class="info-edit"><?= form_dropdown('employment_type', [
                                        'full_time' => lang('Hr.full_time'),
                                        'part_time' => lang('Hr.part_time'),
                                        'contract' => lang('Hr.contract'),
                                        'intern' => lang('Hr.intern')
                                    ], $profile['employment_type'] ?? 'full_time', ['class' => 'form-control']) ?></div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label"><?= lang('Hr.basic_salary') ?></div>
                                    <div class="info-display"><?= $profile['basic_salary'] ? number_format($profile['basic_salary'], 2) : '-' ?></div>
                                    <div class="info-edit"><?= form_input('basic_salary', $profile['basic_salary'] ?? '', ['class' => 'form-control', 'type' => 'number', 'step' => '0.01']) ?></div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label"><?= lang('Hr.hourly_rate') ?></div>
                                    <div class="info-display"><?= $profile['hourly_rate'] ? number_format($profile['hourly_rate'], 2) : '-' ?></div>
                                    <div class="info-edit"><?= form_input('hourly_rate', $profile['hourly_rate'] ?? '', ['class' => 'form-control', 'type' => 'number', 'step' => '0.01']) ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="section-header"><?= lang('Hr.banking_info') ?></div>
                                <div class="info-row">
                                    <div class="info-label"><?= lang('Hr.bank_name') ?></div>
                                    <div class="info-display"><?= esc($profile['bank_name']) ?: '-' ?></div>
                                    <div class="info-edit"><?= form_input('bank_name', $profile['bank_name'] ?? '', ['class' => 'form-control']) ?></div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label"><?= lang('Hr.bank_account') ?></div>
                                    <div class="info-display"><?= esc($profile['bank_account']) ?: '-' ?></div>
                                    <div class="info-edit"><?= form_input('bank_account', $profile['bank_account'] ?? '', ['class' => 'form-control']) ?></div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label"><?= lang('Hr.tax_id') ?></div>
                                    <div class="info-display"><?= esc($profile['tax_id']) ?: '-' ?></div>
                                    <div class="info-edit"><?= form_input('tax_id', $profile['tax_id'] ?? '', ['class' => 'form-control']) ?></div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label"><?= lang('Hr.social_security_number') ?></div>
                                    <div class="info-display"><?= esc($profile['social_security_number']) ?: '-' ?></div>
                                    <div class="info-edit"><?= form_input('social_security_number', $profile['social_security_number'] ?? '', ['class' => 'form-control']) ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Documents Tab -->
                    <div class="tab-pane fade" id="documents">
                        <?php if (empty($attachments)): ?>
                            <div class="alert alert-info">
                                <?= lang('Hr.no_attachments') ?>
                            </div>
                        <?php else: ?>
                            <div class="row" style="margin-bottom: 15px;">
                                <div class="col-md-12 text-right">
                                    <a href="<?= site_url('hr/employee/attachments/zip/' . $employee['person_id']) ?>" class="btn btn-success">
                                        <span class="glyphicon glyphicon-download-alt"></span> <?= lang('Hr.download_all_attachments') ?>
                                    </a>
                                </div>
                            </div>
                            <div id="attachments_print_area">
                                <h4><?= lang('Hr.documents') ?></h4>
                                <p><strong><?= esc($employee['first_name'] . ' ' . $employee['last_name']) ?></strong> - <?= lang('Hr.employee_number') ?>: <?= esc($profile['employee_number'] ?? 'N/A') ?></p>
                                <table class="table table-striped table-bordered" style="margin-top: 15px;">
                                    <thead>
                                        <tr>
                                            <th><?= lang('Hr.document_type') ?></th>
                                            <th><?= lang('Hr.document_title') ?></th>
                                            <th><?= lang('Hr.file_name') ?></th>
                                            <th><?= lang('Hr.file_size') ?></th>
                                            <th><?= lang('Hr.expiry_date') ?></th>
                                            <th><?= lang('Hr.verified') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($attachments as $attachment): ?>
                                            <tr>
                                                <td><?= lang('Hr.doc_type_' . $attachment['doc_type']) ?></td>
                                                <td><?= esc($attachment['title']) ?></td>
                                                <td><?= esc($attachment['file_name']) ?></td>
                                                <td><?= number_format($attachment['file_size'] / 1024, 2) ?> KB</td>
                                                <td><?= $attachment['expiry_date'] ? date('M d, Y', strtotime($attachment['expiry_date'])) : '-' ?></td>
                                                <td>
                                                    <?php if ($attachment['is_verified']): ?>
                                                        <span class="label label-success"><?= lang('Hr.verified') ?></span>
                                                    <?php else: ?>
                                                        <span class="label label-default"><?= lang('Hr.not_verified') ?></span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-right" style="margin-top: 15px;">
                                <button type="button" class="btn btn-primary" onclick="printAttachments()">
                                    <span class="glyphicon glyphicon-print"></span> <?= lang('Common.print') ?>
                                </button>
                                <a href="<?= site_url('hr/attachment/download/' . $attachment['id']) ?>" 
                                   class="btn btn-info" title="<?= lang('Common.download') ?>">
                                    <span class="glyphicon glyphicon-download-alt"></span> <?= lang('Common.download') ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Attendance Tab -->
                    <div class="tab-pane fade" id="attendance">
                        <?php if (empty($attendance_history)): ?>
                            <div class="alert alert-info">
                                <?= lang('Hr.no_attendance_records') ?>
                            </div>
                        <?php else: ?>
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th><?= lang('Common.date') ?></th>
                                        <th><?= lang('Hr.clock_in') ?></th>
                                        <th><?= lang('Hr.clock_out') ?></th>
                                        <th><?= lang('Hr.worked_hours') ?></th>
                                        <th><?= lang('Hr.status') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($attendance_history as $record): ?>
                                        <tr>
                                            <td><?= date('M d, Y', strtotime($record['date'])) ?></td>
                                            <td><?= $record['clock_in'] ? date('H:i', strtotime($record['clock_in'])) : '-' ?></td>
                                            <td><?= $record['clock_out'] ? date('H:i', strtotime($record['clock_out'])) : '-' ?></td>
                                            <td><?= $record['worked_hours'] ? number_format($record['worked_hours'], 2) : '-' ?></td>
                                            <td>
                                                <?php
                                                $status_class = 'default';
                                                if ($record['status'] === 'present') $status_class = 'success';
                                                elseif ($record['status'] === 'absent') $status_class = 'danger';
                                                elseif ($record['status'] === 'late') $status_class = 'warning';
                                                ?>
                                                <span class="label label-<?= $status_class ?>">
                                                    <?= lang('Hr.status_' . $record['status']) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Leave Tab -->
                    <div class="tab-pane fade" id="leave">
                        <?php if (!empty($leave_balances)): ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <h4><?= lang('Hr.leave_balance') ?></h4>
                                    <div class="row">
                                        <?php foreach ($leave_balances as $balance): ?>
                                            <div class="col-md-3">
                                                <div class="panel panel-<?= $balance['remaining'] > 0 ? 'success' : 'default' ?>">
                                                    <div class="panel-heading">
                                                        <strong><?= esc($balance['type_name']) ?></strong>
                                                    </div>
                                                    <div class="panel-body text-center">
                                                        <h2 style="margin: 0;">
                                                            <?= $balance['remaining'] ?>
                                                        </h2>
                                                        <small class="text-muted">
                                                            <?= lang('Hr.days') ?> <?= lang('Hr.remaining') ?>
                                                            <br>
                                                            <span class="text-muted">(<?= $balance['used_days'] ?> / <?= $balance['total_days'] ?> <?= lang('Hr.days') ?>)</span>
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        <?php endif; ?>
                        
                        <h4><?= lang('Hr.leave_history') ?></h4>
                        <?php if (empty($leave_history)): ?>
                            <div class="alert alert-info">
                                <?= lang('Hr.no_leave_requests') ?>
                            </div>
                        <?php else: ?>
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th><?= lang('Hr.leave_type') ?></th>
                                        <th><?= lang('Hr.start_date') ?></th>
                                        <th><?= lang('Hr.end_date') ?></th>
                                        <th><?= lang('Hr.total_days') ?></th>
                                        <th><?= lang('Hr.status') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($leave_history as $leave): ?>
                                        <tr>
                                            <td><?= esc($leave['leave_type_name'] ?? '-') ?></td>
                                            <td><?= date('M d, Y', strtotime($leave['start_date'])) ?></td>
                                            <td><?= date('M d, Y', strtotime($leave['end_date'])) ?></td>
                                            <td><?= $leave['total_days'] ?> <?= lang('Hr.days') ?></td>
                                            <td>
                                                <?php
                                                $status_class = 'default';
                                                if ($leave['status'] === 'approved') $status_class = 'success';
                                                elseif ($leave['status'] === 'rejected') $status_class = 'danger';
                                                elseif ($leave['status'] === 'pending') $status_class = 'warning';
                                                ?>
                                                <span class="label label-<?= $status_class ?>">
                                                    <?= ucfirst($leave['status']) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Salary Tab -->
                    <div class="tab-pane fade" id="salary">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-striped">
                                    <tr>
                                        <th colspan="2"><?= lang('Hr.salary_info') ?></th>
                                    </tr>
                                    <tr>
                                        <th style="width: 50%;"><?= lang('Hr.basic_salary') ?></th>
                                        <td><?= $profile['basic_salary'] ? number_format($profile['basic_salary'], 2) : '-' ?></td>
                                    </tr>
                                    <tr>
                                        <th><?= lang('Hr.hourly_rate') ?></th>
                                        <td><?= $profile['hourly_rate'] ? number_format($profile['hourly_rate'], 2) : '-' ?></td>
                                    </tr>
                                    <tr>
                                        <th><?= lang('Hr.shift') ?></th>
                                        <td><?= esc($profile['shift_name'] ?? '-') ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <?php if (!empty($recent_salary)): ?>
                                <table class="table table-striped">
                                    <tr>
                                        <th colspan="2"><?= lang('Hr.payslip') ?> (<?= date('M Y', strtotime($recent_salary['period_start'])) ?>)</th>
                                    </tr>
                                    <tr>
                                        <th style="width: 50%;"><?= lang('Hr.total_earnings') ?></th>
                                        <td class="text-success"><?= number_format($recent_salary['total_earnings'], 2) ?></td>
                                    </tr>
                                    <tr>
                                        <th><?= lang('Hr.total_deductions') ?></th>
                                        <td class="text-danger"><?= number_format($recent_salary['total_deductions'], 2) ?></td>
                                    </tr>
                                    <tr>
                                        <th><strong><?= lang('Hr.net_salary') ?></strong></th>
                                        <td class="text-primary"><strong><?= number_format($recent_salary['net_salary'], 2) ?></strong></td>
                                    </tr>
                                </table>
                                <?php else: ?>
                                <div class="alert alert-info">
                                    <?= lang('Hr.no_salary_records') ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= form_hidden('person_id', $employee['person_id']) ?>
<?= form_close() ?>

<script>
$(document).ready(function() {
    // Hash-based tab selection
    var hash = window.location.hash;
    if (hash) {
        $('#employeeTabs a[href="' + hash + '"]').tab('show');
    }
    
    $('#employeeTabs a').click(function(e) {
        e.preventDefault();
        $(this).tab('show');
        var hash = $(this).attr('href');
        history.pushState(null, null, hash);
    });
    
    // Edit mode toggle
    $('#edit_btn').click(function() {
        $('#info_panel').addClass('edit-mode');
        $('#edit_btn').hide();
        $('#submit_btn').show();
        $('#cancel_btn').show();
    });
    
    $('#cancel_btn').click(function() {
        $('#info_panel').removeClass('edit-mode');
        $('#cancel_btn').hide();
        $('#submit_btn').hide();
        $('#edit_btn').show();
        // Reset form values
        $('#employee_form')[0].reset();
    });
    
    // Print attachments function
    window.printAttachments = function() {
        var printContent = $('#attachments_print_area').html();
        var printWindow = window.open('', '_blank');
        printWindow.document.write('<!DOCTYPE html>');
        printWindow.document.write('<html><head>');
        printWindow.document.write('<title><?= lang('Hr.documents') ?> - <?= esc($employee['first_name'] . ' ' . $employee['last_name']) ?></title>');
        printWindow.document.write('<link rel="stylesheet" href="<?= base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') ?>">');
        printWindow.document.write('<style>');
        printWindow.document.write('body { padding: 20px; font-family: Arial, sans-serif; }');
        printWindow.document.write('h4 { margin-top: 0; }');
        printWindow.document.write('table { width: 100%; border-collapse: collapse; margin-top: 15px; }');
        printWindow.document.write('th, td { padding: 8px; text-align: left; border: 1px solid #ddd; }');
        printWindow.document.write('th { background: #f5f5f5; }');
        printWindow.document.write('@media print { .no-print { display: none; } }');
        printWindow.document.write('</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write(printContent);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.focus();
        setTimeout(function() {
            printWindow.print();
        }, 250);
    };
    
    // Form submission with AJAX
    $('#employee_form').submit(function(e) {
        e.preventDefault();
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    BootstrapDialog.show({
                        title: '<span class="glyphicon glyphicon-ok-circle"></span> <?= lang('Common.success') ?>',
                        message: response.message || '<?= lang('Common.successful_update') ?>',
                        type: BootstrapDialog.TYPE_SUCCESS,
                        buttons: [{
                            label: 'OK',
                            action: function(dialog) {
                                dialog.close();
                                location.reload();
                            }
                        }]
                    });
                } else {
                    BootstrapDialog.show({
                        title: '<span class="glyphicon glyphicon-remove-circle"></span> <?= lang('Common.error') ?>',
                        message: response.message || '<?= lang('Common.error') ?>',
                        type: BootstrapDialog.TYPE_DANGER
                    });
                }
            },
            error: function(xhr) {
                BootstrapDialog.show({
                    title: '<span class="glyphicon glyphicon-remove-circle"></span> <?= lang('Common.error') ?>',
                    message: '<?= lang('Common.error') ?>: ' + (xhr.responseText || 'Unknown error'),
                    type: BootstrapDialog.TYPE_DANGER
                });
            }
        });
    });
});
</script>

<?= view('partial/footer') ?>
