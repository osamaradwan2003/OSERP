<?php
/**
 * @var array $employees
 */
?>

<?= view('partial/header') ?>

<style>
.hr-page {
    padding: 20px 0;
}
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
.page-header-bar h1 .glyphicon {
    margin-right: 12px;
    color: var(--primary);
}
.salary-form-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    overflow: hidden;
}
.salary-form-card .panel-heading {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    padding: 20px 25px;
    font-size: 18px;
    font-weight: 600;
}
.salary-form-card .panel-body {
    padding: 30px;
}
.payslip-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    overflow: hidden;
    margin-top: 25px;
}
.payslip-card .panel-heading {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    color: #fff;
    padding: 20px 25px;
    font-size: 18px;
    font-weight: 600;
}
.payslip-header {
    background: #f8f9fa;
    padding: 25px;
    border-bottom: 2px solid #e9ecef;
}
.payslip-employee {
    display: flex;
    align-items: center;
    gap: 20px;
}
.payslip-employee .avatar {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    font-weight: 600;
}
.payslip-info {
    flex: 1;
}
.payslip-info h3 {
    margin: 0 0 5px 0;
    color: #2c3e50;
}
.payslip-info p {
    margin: 0;
    color: #6c757d;
}
.payslip-period {
    text-align: right;
}
.payslip-period .period-badge {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    padding: 10px 20px;
    border-radius: 25px;
    font-weight: 600;
}
.payslip-body {
    padding: 25px;
}
.payslip-section {
    margin-bottom: 25px;
}
.payslip-section h4 {
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #6c757d;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e9ecef;
}
.component-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
}
.component-row:last-child {
    border-bottom: none;
}
.component-row.earning {
    color: #155724;
}
.component-row.deduction {
    color: #721c24;
}
.component-name {
    display: flex;
    align-items: center;
    gap: 10px;
}
.component-name .icon {
    width: 24px;
    height: 24px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}
.component-row.earning .icon {
    background: #d4edda;
    color: #155724;
}
.component-row.deduction .icon {
    background: #f8d7da;
    color: #721c24;
}
.component-value {
    font-weight: 600;
}
.payslip-summary {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-top: 20px;
}
.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    font-size: 16px;
}
.summary-row.gross {
    border-bottom: 2px solid #dee2e6;
    padding-bottom: 15px;
    margin-bottom: 10px;
}
.summary-row.net {
    font-size: 22px;
    font-weight: 700;
    color: #28a745;
    margin-top: 15px;
    padding-top: 15px;
    border-top: 2px solid #dee2e6;
}
.breadcrumb-bar {
    margin-bottom: 20px;
}
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
            <li class="active"><?= lang('Hr.calculate_salary') ?></li>
        </ol>
    </div>

    <div class="page-header-bar">
        <h1>
            <span class="glyphicon glyphicon-calculator"></span>
            <?= lang('Hr.salary_calculation') ?>
        </h1>
    </div>

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="salary-form-card panel panel-default">
                <div class="panel-heading">
                    <span class="glyphicon glyphicon-user"></span>
                    <?= lang('Hr.salary_calculation') ?>
                </div>
                <div class="panel-body">
                    <?= form_open('hr/calculate', ['id' => 'calculate_form']) ?>
                    
                    <div class="form-group">
                        <label class="control-label required"><?= lang('Hr.employee') ?></label>
                        <select name="employee_id" id="employee_id" class="form-control input-lg" required>
                            <option value=""><?= lang('Common.select') ?>...</option>
                            <?php foreach ($employees as $emp): ?>
                                <option value="<?= $emp['employee_id'] ?>">
                                    <?= esc($emp['first_name'] . ' ' . $emp['last_name']) ?>
                                    <?= !empty($emp['department_name']) ? ' - ' . esc($emp['department_name']) : '' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label required"><?= lang('Hr.period_start') ?></label>
                                <?= form_input([
                                    'name' => 'period_start',
                                    'id' => 'period_start',
                                    'class' => 'form-control input-lg',
                                    'type' => 'date',
                                    'value' => date('Y-m-01'),
                                    'required' => true
                                ]) ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label required"><?= lang('Hr.period_end') ?></label>
                                <?= form_input([
                                    'name' => 'period_end',
                                    'id' => 'period_end',
                                    'class' => 'form-control input-lg',
                                    'type' => 'date',
                                    'value' => date('Y-m-t'),
                                    'required' => true
                                ]) ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group" style="margin-top: 30px;">
                        <button type="submit" class="btn btn-success btn-lg btn-block">
                            <span class="glyphicon glyphicon-calculator"></span>
                            <?= lang('Hr.calculate') ?>
                        </button>
                    </div>
                    
                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>

    <div id="result_container" style="display: none;">
        <div class="payslip-card">
            <div class="panel-heading">
                <span class="glyphicon glyphicon-file"></span>
                <?= lang('Hr.payslip') ?>
                <span class="pull-right"><?= lang('Hr.generated_on') ?>: <?= date('d M Y H:i') ?></span>
            </div>
            <div class="payslip-header">
                <div class="payslip-employee">
                    <div class="avatar" id="emp_avatar">?</div>
                    <div class="payslip-info">
                        <h3 id="emp_name"><?= lang('Hr.employee') ?></h3>
                        <p id="emp_dept"><?= lang('Hr.department') ?>: —</p>
                        <p id="emp_id"><?= lang('Hr.employee_id') ?>: —</p>
                    </div>
                    <div class="payslip-period">
                        <div class="period-badge" id="period_badge">—</div>
                    </div>
                </div>
            </div>
            <div class="payslip-body" id="result_content">
                <!-- Filled by AJAX -->
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('#calculate_form').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '<?= site_url('hr/calculate') ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            beforeSend: function() {
                $('#result_container').hide();
            },
            success: function(response) {
                if (response.success) {
                    displayResult(response);
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('<?= lang('Common.error') ?>');
            }
        });
    });
    
    function displayResult(result) {
        // Update header
        var empName = result.employee_name || '<?= lang('Hr.employee') ?>';
        var initials = empName.split(' ').map(function(n) { return n[0]; }).join('').substring(0, 2).toUpperCase();
        $('#emp_avatar').text(initials);
        $('#emp_name').text(empName);
        $('#emp_dept').text('<?= lang('Hr.department') ?>: ' + (result.department_name || '—'));
        $('#emp_id').text('<?= lang('Hr.employee_id') ?>: ' + result.employee_id);
        $('#period_badge').text(result.period_start + ' - ' + result.period_end);
        
        var html = '<div class="row">';
        
        // Attendance Summary
        html += '<div class="col-md-4">';
        html += '<div class="payslip-section">';
        html += '<h4><span class="glyphicon glyphicon-calendar"></span> <?= lang('Hr.attendance_summary') ?></h4>';
        var att = result.attendance_data || {};
        html += '<div class="component-row"><span><?= lang('Hr.days_present') ?></span><strong>' + (att.days_present || 0) + '</strong></div>';
        html += '<div class="component-row"><span><?= lang('Hr.days_absent') ?></span><strong>' + (att.days_absent || 0) + '</strong></div>';
        html += '<div class="component-row"><span><?= lang('Hr.overtime_minutes') ?></span><strong>' + (att.total_overtime_minutes || 0) + '</strong></div>';
        html += '<div class="component-row"><span><?= lang('Hr.late_minutes') ?></span><strong>' + (att.total_late_minutes || 0) + '</strong></div>';
        html += '</div></div>';
        
        // Basic Salary Info
        html += '<div class="col-md-4">';
        html += '<div class="payslip-section">';
        html += '<h4><span class="glyphicon glyphicon-usd"></span> <?= lang('Hr.basic_salary') ?></h4>';
        html += '<div class="component-row"><span><?= lang('Hr.basic_salary') ?></span><strong>' + formatCurrency(result.basic_salary) + '</strong></div>';
        html += '</div></div>';
        
        // Employee Info
        html += '<div class="col-md-4">';
        html += '<div class="payslip-section">';
        html += '<h4><span class="glyphicon glyphicon-info-sign"></span> <?= lang('Hr.employee_info') ?></h4>';
        html += '<div class="component-row"><span><?= lang('Hr.employee_id') ?></span><strong>' + result.employee_id + '</strong></div>';
        html += '<div class="component-row"><span><?= lang('Hr.period') ?></span><strong>' + result.period_start + ' - ' + result.period_end + '</strong></div>';
        html += '</div></div>';
        
        html += '</div>';
        
        // Salary Components
        var earnings = result.components.filter(function(c) { return c.group_type === 'earning'; });
        var deductions = result.components.filter(function(c) { return c.group_type === 'deduction'; });
        
        html += '<div class="row">';
        
        // Earnings
        html += '<div class="col-md-6">';
        html += '<div class="payslip-section">';
        html += '<h4 style="color: #155724;"><span class="glyphicon glyphicon-plus-sign"></span> <?= lang('Hr.earnings') ?></h4>';
        if (earnings.length > 0) {
            earnings.forEach(function(c) {
                html += '<div class="component-row earning">';
                html += '<div class="component-name"><span class="icon">+</span>' + c.name + '</div>';
                html += '<div class="component-value">' + formatCurrency(c.value) + '</div>';
                html += '</div>';
            });
        } else {
            html += '<div class="text-muted text-center"><?= lang('Common.no_data') ?></div>';
        }
        html += '</div></div>';
        
        // Deductions
        html += '<div class="col-md-6">';
        html += '<div class="payslip-section">';
        html += '<h4 style="color: #721c24;"><span class="glyphicon glyphicon-minus-sign"></span> <?= lang('Hr.deductions') ?></h4>';
        if (deductions.length > 0) {
            deductions.forEach(function(c) {
                html += '<div class="component-row deduction">';
                html += '<div class="component-name"><span class="icon">−</span>' + c.name + '</div>';
                html += '<div class="component-value">(' + formatCurrency(c.value) + ')</div>';
                html += '</div>';
            });
        } else {
            html += '<div class="text-muted text-center"><?= lang('Common.no_data') ?></div>';
        }
        html += '</div></div>';
        
        html += '</div>';
        
        // Summary
        html += '<div class="col-md-6 col-md-offset-3">';
        html += '<div class="payslip-summary">';
        html += '<div class="summary-row gross"><span><?= lang('Hr.total_earnings') ?></span><strong style="color: #155724;">' + formatCurrency(result.total_earnings) + '</strong></div>';
        html += '<div class="summary-row"><span><?= lang('Hr.total_deductions') ?></span><strong style="color: #721c24;">(' + formatCurrency(result.total_deductions) + ')</strong></div>';
        html += '<div class="summary-row net"><span><?= lang('Hr.net_salary') ?></span><span>' + formatCurrency(result.net_salary) + '</span></div>';
        html += '</div>';
        html += '</div>';
        
        $('#result_content').html(html);
        $('#result_container').show();
        $('html, body').animate({ scrollTop: $('#result_container').offset().top - 20 }, 500);
    }
    
    function formatCurrency(amount) {
        return '$' + parseFloat(amount || 0).toFixed(2);
    }
});
</script>

<?= view('partial/footer') ?>
