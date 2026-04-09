<?php
/**
 * @var object $employee
 * @var array $profile
 * @var string $period_start
 * @var string $period_end
 * @var array $calculation
 */
?>

<?= view('partial/header') ?>

<style>
.payslip-page { padding: 20px 0; }
.payslip-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    overflow: hidden;
}
.payslip-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    padding: 30px;
    text-align: center;
}
.payslip-header h2 {
    margin: 0 0 10px 0;
    font-size: 28px;
}
.payslip-header p {
    margin: 0;
    opacity: 0.9;
}
.payslip-employee {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 25px;
    background: #f8f9fa;
    border-bottom: 2px solid #e9ecef;
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
.payslip-employee .info h3 {
    margin: 0 0 5px 0;
    color: #2c3e50;
}
.payslip-employee .info p {
    margin: 0;
    color: #6c757d;
}
.payslip-period {
    text-align: right;
    flex: 1;
}
.payslip-period .badge {
    background: rgba(255,255,255,0.2);
    color: #fff;
    padding: 10px 20px;
    border-radius: 25px;
    font-size: 14px;
}
.payslip-body { padding: 25px; }
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
.payslip-section h4 .glyphicon { margin-right: 8px; }
.component-row {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    border-bottom: 1px solid #f0f0f0;
}
.component-row:last-child { border-bottom: none; }
.component-row.earning { color: #155724; }
.component-row.deduction { color: #721c24; }
.component-name { display: flex; align-items: center; gap: 10px; }
.component-name .icon {
    width: 24px;
    height: 24px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}
.component-row.earning .icon { background: #d4edda; color: #155724; }
.component-row.deduction .icon { background: #f8d7da; color: #721c24; }
.component-value { font-weight: 600; font-size: 16px; }
.payslip-summary {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 12px;
    margin-top: 20px;
}
.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    font-size: 18px;
}
.summary-row.gross {
    border-bottom: 2px solid #dee2e6;
    padding-bottom: 15px;
    margin-bottom: 10px;
}
.summary-row.net {
    font-size: 24px;
    font-weight: 700;
    color: #28a745;
    margin-top: 15px;
    padding-top: 15px;
    border-top: 2px solid #dee2e6;
}
.payslip-footer {
    background: #f8f9fa;
    padding: 20px 25px;
    border-top: 2px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.payslip-footer .bank-info p { margin: 0; color: #6c757d; font-size: 14px; }
.payslip-footer .generated { color: #6c757d; font-size: 12px; }
.print-actions {
    text-align: center;
    padding: 20px;
}
.print-actions .btn { margin: 0 5px; }
@media print {
    .nav, .btn, #title_bar, .print-actions { display: none !important; }
    .payslip-card { box-shadow: none; }
}
</style>

<div class="payslip-page">
    <div class="payslip-card">
        <div class="payslip-header">
            <h2><span class="glyphicon glyphicon-file"></span> <?= lang('Hr.payslip') ?></h2>
            <p><?= lang('Hr.generated_on') ?>: <?= date('d M Y H:i') ?></p>
        </div>

        <div class="payslip-employee">
            <div class="avatar">
                <?= strtoupper(substr($employee->first_name, 0, 1)) ?>
            </div>
            <div class="info">
                <h3><?= esc($employee->first_name . ' ' . $employee->last_name) ?></h3>
                <p>
                    <?= lang('Hr.department') ?>: <?= esc($profile['department_name'] ?? '—') ?> | 
                    <?= lang('Hr.position') ?>: <?= esc($profile['position_name'] ?? '—') ?>
                </p>
            </div>
            <div class="payslip-period">
                <span class="badge">
                    <?= date('d M', strtotime($period_start)) ?> - <?= date('d M Y', strtotime($period_end)) ?>
                </span>
            </div>
        </div>

        <div class="payslip-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="payslip-section">
                        <h4><span class="glyphicon glyphicon-usd"></span> <?= lang('Hr.basic_salary') ?></h4>
                        <div class="component-row earning">
                            <span class="component-name"><span class="icon">$</span><?= lang('Hr.basic_salary') ?></span>
                            <span class="component-value"><?= to_currency($calculation['basic_salary']) ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="payslip-section">
                        <h4><span class="glyphicon glyphicon-calendar"></span> <?= lang('Hr.attendance') ?></h4>
                        <div class="component-row">
                            <span class="component-name"><?= lang('Hr.days_present') ?></span>
                            <span class="component-value"><?= $calculation['attendance_data']['days_present'] ?? 0 ?></span>
                        </div>
                        <div class="component-row">
                            <span class="component-name"><?= lang('Hr.overtime') ?></span>
                            <span class="component-value"><?= number_format(($calculation['attendance_data']['total_overtime_minutes'] ?? 0) / 60, 1) ?>h</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="payslip-section">
                        <h4><span class="glyphicon glyphicon-plus-sign"></span> <?= lang('Hr.earnings') ?></h4>
                        <?php $hasEarnings = false; ?>
                        <?php foreach ($calculation['components'] as $component): ?>
                            <?php if ($component['group_type'] === 'earning'): ?>
                                <?php $hasEarnings = true; ?>
                                <div class="component-row earning">
                                    <span class="component-name"><span class="icon">+</span><?= esc($component['name']) ?></span>
                                    <span class="component-value"><?= to_currency($component['value']) ?></span>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?php if (!$hasEarnings): ?>
                            <div class="text-muted text-center">—</div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="payslip-section">
                        <h4><span class="glyphicon glyphicon-minus-sign"></span> <?= lang('Hr.deductions') ?></h4>
                        <?php $hasDeductions = false; ?>
                        <?php foreach ($calculation['components'] as $component): ?>
                            <?php if ($component['group_type'] === 'deduction'): ?>
                                <?php $hasDeductions = true; ?>
                                <div class="component-row deduction">
                                    <span class="component-name"><span class="icon">−</span><?= esc($component['name']) ?></span>
                                    <span class="component-value">(<?= to_currency($component['value']) ?>)</span>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?php if (!$hasDeductions): ?>
                            <div class="text-muted text-center">—</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="payslip-summary">
                <div class="summary-row gross">
                    <span><?= lang('Hr.total_earnings') ?></span>
                    <strong style="color: #155724;"><?= to_currency($calculation['total_earnings']) ?></strong>
                </div>
                <div class="summary-row">
                    <span><?= lang('Hr.total_deductions') ?></span>
                    <strong style="color: #721c24;">(<?= to_currency($calculation['total_deductions']) ?>)</strong>
                </div>
                <div class="summary-row net">
                    <span><?= lang('Hr.net_salary') ?></span>
                    <span><?= to_currency($calculation['net_salary']) ?></span>
                </div>
            </div>
        </div>

        <div class="payslip-footer">
            <div class="bank-info">
                <p><strong><?= lang('Hr.bank') ?>:</strong> <?= esc($profile['bank_name'] ?? '—') ?></p>
                <p><strong><?= lang('Hr.account') ?>:</strong> <?= esc($profile['bank_account'] ?? '—') ?></p>
            </div>
            <div class="generated">
                <?= lang('Hr.generated_on') ?>: <?= date('d M Y H:i') ?>
            </div>
        </div>
    </div>

    <div class="print-actions">
        <button class="btn btn-primary btn-lg" onclick="window.print()">
            <span class="glyphicon glyphicon-print"></span> <?= lang('Common.print') ?>
        </button>
        <a href="<?= site_url('hr/calculate') ?>" class="btn btn-default btn-lg">
            <span class="glyphicon glyphicon-arrow-left"></span> <?= lang('Common.back') ?>
        </a>
    </div>
</div>

<?= view('partial/footer') ?>
