<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= lang('Hr.employee_details') ?> - <?= esc($employee['first_name'] . ' ' . $employee['last_name']) ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        .header { background: #2c3e50; color: white; padding: 20px; text-align: center; margin-bottom: 20px; }
        .header h1 { font-size: 24px; margin-bottom: 5px; }
        .header p { font-size: 12px; opacity: 0.8; }
        .content { padding: 0 20px 20px; }
        .section { margin-bottom: 20px; }
        .section-title { background: #34495e; color: white; padding: 8px 12px; font-size: 14px; font-weight: bold; margin-bottom: 10px; }
        .info-grid { display: table; width: 100%; }
        .info-row { display: table-row; border-bottom: 1px solid #eee; }
        .info-label { display: table-cell; width: 40%; padding: 8px; font-weight: bold; background: #f9f9f9; }
        .info-value { display: table-cell; width: 60%; padding: 8px; }
        .two-col { display: table; width: 100%; }
        .col { display: table-cell; width: 50%; vertical-align: top; padding: 0 10px; }
        .col:first-child { padding-left: 0; }
        .col:last-child { padding-right: 0; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; padding: 10px 20px; background: #f5f5f5; border-top: 1px solid #ddd; font-size: 10px; color: #666; text-align: center; }
        .status-badge { display: inline-block; padding: 3px 8px; border-radius: 3px; font-size: 11px; }
        .status-active { background: #27ae60; color: white; }
        .status-terminated { background: #e74c3c; color: white; }
        .status-other { background: #f39c12; color: white; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f5f5f5; font-weight: bold; }
        .text-success { color: #27ae60; }
        .text-danger { color: #e74c3c; }
        .text-muted { color: #999; }
        .attachments-table { margin-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1><?= lang('Hr.employee_details') ?></h1>
        <p><?= esc($employee['first_name'] . ' ' . $employee['last_name']) ?>
           <?php if (!empty($profile['employee_number'])): ?>
               | <?= lang('Hr.employee_number') ?>: <?= esc($profile['employee_number']) ?>
           <?php endif; ?>
        </p>
    </div>
    
    <div class="content">
        <!-- Personal Information -->
        <div class="section">
            <div class="section-title"><?= lang('Hr.personal_info') ?></div>
            <div class="two-col">
                <div class="col">
                    <div class="info-row">
                        <div class="info-label"><?= lang('Hr.first_name') ?></div>
                        <div class="info-value"><?= esc($employee['first_name']) ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><?= lang('Hr.last_name') ?></div>
                        <div class="info-value"><?= esc($employee['last_name']) ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><?= lang('Hr.email') ?></div>
                        <div class="info-value"><?= esc($employee['email']) ?: '-' ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><?= lang('Hr.phone') ?></div>
                        <div class="info-value"><?= esc($employee['phone_number']) ?: '-' ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><?= lang('Hr.address') ?></div>
                        <div class="info-value"><?= nl2br(esc($employee['address_1'])) ?: '-' ?></div>
                    </div>
                </div>
                <div class="col">
                    <div class="info-row">
                        <div class="info-label"><?= lang('Hr.city') ?></div>
                        <div class="info-value"><?= esc($employee['city']) ?: '-' ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><?= lang('Hr.state') ?></div>
                        <div class="info-value"><?= esc($employee['state']) ?: '-' ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><?= lang('Hr.zip') ?></div>
                        <div class="info-value"><?= esc($employee['zip']) ?: '-' ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><?= lang('Hr.country') ?></div>
                        <div class="info-value"><?= esc($employee['country']) ?: '-' ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><?= lang('Hr.username') ?></div>
                        <div class="info-value"><?= esc($employee['username']) ?: '-' ?></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- HR Profile -->
        <div class="section">
            <div class="section-title"><?= lang('Hr.hr_profile') ?></div>
            <div class="two-col">
                <div class="col">
                    <div class="info-row">
                        <div class="info-label"><?= lang('Hr.department') ?></div>
                        <div class="info-value"><?= esc($profile['department_name'] ?? '-') ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><?= lang('Hr.position') ?></div>
                        <div class="info-value"><?= esc($profile['position_name'] ?? '-') ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><?= lang('Hr.shift') ?></div>
                        <div class="info-value"><?= esc($profile['shift_name'] ?? '-') ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><?= lang('Hr.hire_date') ?></div>
                        <div class="info-value"><?= $profile['hire_date'] ? date('M d, Y', strtotime($profile['hire_date'])) : '-' ?></div>
                    </div>
                </div>
                <div class="col">
                    <div class="info-row">
                        <div class="info-label"><?= lang('Hr.employment_type') ?></div>
                        <div class="info-value"><?= lang('Hr.' . ($profile['employment_type'] ?? 'full_time')) ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><?= lang('Hr.basic_salary') ?></div>
                        <div class="info-value"><?= $profile['basic_salary'] ? number_format($profile['basic_salary'], 2) : '-' ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><?= lang('Hr.hourly_rate') ?></div>
                        <div class="info-value"><?= $profile['hourly_rate'] ? number_format($profile['hourly_rate'], 2) : '-' ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><?= lang('Hr.employment_status') ?></div>
                        <div class="info-value">
                            <?php
                            $status = $profile['employment_status'] ?? 'active';
                            $status_class = $status === 'active' ? 'status-active' : ($status === 'terminated' ? 'status-terminated' : 'status-other');
                            ?>
                            <span class="status-badge <?= $status_class ?>"><?= lang('Hr.status_' . $status) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Banking Information -->
        <div class="section">
            <div class="section-title"><?= lang('Hr.banking_info') ?></div>
            <div class="two-col">
                <div class="col">
                    <div class="info-row">
                        <div class="info-label"><?= lang('Hr.bank_name') ?></div>
                        <div class="info-value"><?= esc($profile['bank_name']) ?: '-' ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><?= lang('Hr.bank_account') ?></div>
                        <div class="info-value"><?= esc($profile['bank_account']) ?: '-' ?></div>
                    </div>
                </div>
                <div class="col">
                    <div class="info-row">
                        <div class="info-label"><?= lang('Hr.tax_id') ?></div>
                        <div class="info-value"><?= esc($profile['tax_id']) ?: '-' ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><?= lang('Hr.social_security_number') ?></div>
                        <div class="info-value"><?= esc($profile['social_security_number']) ?: '-' ?></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Documents -->
        <?php if (!empty($attachments)): ?>
        <div class="section">
            <div class="section-title"><?= lang('Hr.documents') ?> (<?= count($attachments) ?>)</div>
            <table class="attachments-table">
                <thead>
                    <tr>
                        <th><?= lang('Hr.document_type') ?></th>
                        <th><?= lang('Hr.document_title') ?></th>
                        <th><?= lang('Hr.file_name') ?></th>
                        <th><?= lang('Hr.expiry_date') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($attachments as $attachment): ?>
                    <tr>
                        <td><?= lang('Hr.doc_type_' . $attachment['doc_type']) ?></td>
                        <td><?= esc($attachment['title']) ?></td>
                        <td><?= esc($attachment['file_name']) ?></td>
                        <td><?= $attachment['expiry_date'] ? date('M d, Y', strtotime($attachment['expiry_date'])) : '-' ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
        
        <!-- Salary Summary -->
        <div class="section">
            <div class="section-title"><?= lang('Hr.salary_info') ?></div>
            <div class="two-col">
                <div class="col">
                    <div class="info-row">
                        <div class="info-label"><?= lang('Hr.basic_salary') ?></div>
                        <div class="info-value"><?= $profile['basic_salary'] ? number_format($profile['basic_salary'], 2) : '-' ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><?= lang('Hr.hourly_rate') ?></div>
                        <div class="info-value"><?= $profile['hourly_rate'] ? number_format($profile['hourly_rate'], 2) : '-' ?></div>
                    </div>
                </div>
                <div class="col">
                    <?php if (!empty($recent_salary)): ?>
                    <div class="info-row">
                        <div class="info-label"><?= lang('Hr.total_earnings') ?></div>
                        <div class="info-value text-success"><?= number_format($recent_salary['total_earnings'], 2) ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><?= lang('Hr.total_deductions') ?></div>
                        <div class="info-value text-danger"><?= number_format($recent_salary['total_deductions'], 2) ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><strong><?= lang('Hr.net_salary') ?></strong></div>
                        <div class="info-value"><strong><?= number_format($recent_salary['net_salary'], 2) ?></strong></div>
                    </div>
                    <?php else: ?>
                    <div class="info-row">
                        <div class="info-value text-muted"><?= lang('Hr.no_salary_records') ?></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="footer">
        <?= lang('Hr.generated_on') ?>: <?= date('M d, Y H:i:s') ?> | <?= lang('App.common.company') ?: 'Open Source Point of Sale' ?>
    </div>
</body>
</html>
