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

<div class="payslip">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title text-center"><?= lang('Hr.payslip') ?></h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <h4><?= lang('Hr.employee_info') ?></h4>
                    <p><strong><?= lang('Hr.employee') ?>:</strong> <?= esc($employee->first_name . ' ' . $employee->last_name) ?></p>
                    <p><strong><?= lang('Hr.department') ?>:</strong> <?= esc($profile['department_name'] ?? '-') ?></p>
                    <p><strong><?= lang('Hr.position') ?>:</strong> <?= esc($profile['position_name'] ?? '-') ?></p>
                </div>
                <div class="col-md-6 text-right">
                    <h4><?= lang('Hr.pay_period') ?></h4>
                    <p><?= date('d/m/Y', strtotime($period_start)) ?> - <?= date('d/m/Y', strtotime($period_end)) ?></p>
                </div>
            </div>
            
            <hr>
            
            <div class="row">
                <div class="col-md-6">
                    <h4><?= lang('Hr.basic_salary') ?></h4>
                    <p class="lead"><?= to_currency($calculation['basic_salary']) ?></p>
                </div>
                <div class="col-md-6">
                    <h4><?= lang('Hr.attendance') ?></h4>
                    <p><?= lang('Hr.days_present') ?>: <?= $calculation['attendance_data']['days_present'] ?? 0 ?></p>
                    <p><?= lang('Hr.overtime') ?>: <?= ($calculation['attendance_data']['total_overtime_minutes'] ?? 0) / 60 ?> h</p>
                </div>
            </div>
            
            <hr>
            
            <h4><?= lang('Hr.earnings') ?></h4>
            <table class="table">
                <thead>
                    <tr>
                        <th><?= lang('Hr.component') ?></th>
                        <th class="text-right"><?= lang('Hr.amount') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($calculation['components'] as $component): ?>
                        <?php if ($component['group_type'] === 'earning'): ?>
                            <tr>
                                <td><?= esc($component['name']) ?></td>
                                <td class="text-right"><?= to_currency($component['value']) ?></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <tr class="success">
                        <td><strong><?= lang('Hr.total_earnings') ?></strong></td>
                        <td class="text-right"><strong><?= to_currency($calculation['total_earnings']) ?></strong></td>
                    </tr>
                </tbody>
            </table>
            
            <h4><?= lang('Hr.deductions') ?></h4>
            <table class="table">
                <thead>
                    <tr>
                        <th><?= lang('Hr.component') ?></th>
                        <th class="text-right"><?= lang('Hr.amount') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($calculation['components'] as $component): ?>
                        <?php if ($component['group_type'] === 'deduction'): ?>
                            <tr>
                                <td><?= esc($component['name']) ?></td>
                                <td class="text-right"><?= to_currency($component['value']) ?></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <tr class="danger">
                        <td><strong><?= lang('Hr.total_deductions') ?></strong></td>
                        <td class="text-right"><strong><?= to_currency($calculation['total_deductions']) ?></strong></td>
                    </tr>
                </tbody>
            </table>
            
            <hr>
            
            <div class="row">
                <div class="col-md-12 text-center">
                    <h3><?= lang('Hr.net_salary') ?>: <?= to_currency($calculation['net_salary']) ?></h3>
                </div>
            </div>
            
            <hr>
            
            <div class="row">
                <div class="col-md-6">
                    <p><strong><?= lang('Hr.bank') ?>:</strong> <?= esc($profile['bank_name'] ?? '-') ?></p>
                    <p><strong><?= lang('Hr.account') ?>:</strong> <?= esc($profile['bank_account'] ?? '-') ?></p>
                </div>
                <div class="col-md-6 text-right">
                    <p><strong><?= lang('Hr.generated_on') ?>:</strong> <?= date('d/m/Y H:i') ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="text-center">
        <button class="btn btn-primary" onclick="window.print()">
            <span class="glyphicon glyphicon-print"></span> <?= lang('Common.print') ?>
        </button>
        <a href="<?= site_url('hr/calculate') ?>" class="btn btn-default">
            <span class="glyphicon glyphicon-arrow-left"></span> <?= lang('Common.back') ?>
        </a>
    </div>
</div>

<style type="text/css">
@media print {
    .nav, .btn, #title_bar { display: none !important; }
    .payslip { padding: 20px; }
}
</style>

<?= view('partial/footer') ?>
