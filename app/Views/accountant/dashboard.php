<?php
/**
 * @var array $allowed_modules
 * @var object $user_info
 * @var array $config
 * @var string $controller_name
 */
?>
<?= view('partial/header') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h2><?= lang('Accountant.module_name') ?></h2>
            <p class="lead"><?= lang('Accountant.module_description') ?></p>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-money-bill-wave fa-3x mb-3 text-primary"></i>
                    <h5 class="card-title"><?= lang('Accountant.cashflow_entries') ?></h5>
                    <p class="card-text"><?= lang('Accountant.cashflow_entries_desc') ?></p>
                    <a href="<?= site_url('cashflow') ?>" class="btn btn-primary">
                        <?= lang('Accountant.manage_entries') ?>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-file-invoice-dollar fa-3x mb-3 text-success"></i>
                    <h5 class="card-title"><?= lang('Accountant.draft_entries') ?></h5>
                    <p class="card-text"><?= lang('Accountant.draft_entries_desc') ?></p>
                    <a href="<?= site_url('cashflow/drafts') ?>" class="btn btn-success">
                        <?= lang('Accountant.manage_drafts') ?>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-university fa-3x mb-3 text-info"></i>
                    <h5 class="card-title"><?= lang('Accountant.accounts') ?></h5>
                    <p class="card-text"><?= lang('Accountant.accounts_desc') ?></p>
                    <a href="<?= site_url('cashflow_accounts') ?>" class="btn btn-info">
                        <?= lang('Accountant.manage_accounts') ?>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-tags fa-3x mb-3 text-warning"></i>
                    <h5 class="card-title"><?= lang('Accountant.categories') ?></h5>
                    <p class="card-text"><?= lang('Accountant.categories_desc') ?></p>
                    <a href="<?= site_url('cashflow_categories') ?>" class="btn btn-warning">
                        <?= lang('Accountant.manage_categories') ?>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <h3><?= lang('Accountant.reports_section') ?></h3>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-book fa-3x mb-3 text-secondary"></i>
                    <h5 class="card-title"><?= lang('Accountant.ledger_report') ?></h5>
                    <p class="card-text"><?= lang('Accountant.ledger_report_desc') ?></p>
                    <a href="<?= site_url('reports/cashflow_ledger') ?>" class="btn btn-secondary">
                        <?= lang('Accountant.view_ledger') ?>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-chart-pie fa-3x mb-3 text-danger"></i>
                    <h5 class="card-title"><?= lang('Accountant.summary_report') ?></h5>
                    <p class="card-text"><?= lang('Accountant.summary_report_desc') ?></p>
                    <a href="<?= site_url('reports/cashflow_summary') ?>" class="btn btn-danger">
                        <?= lang('Accountant.view_summary') ?>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-balance-scale fa-3x mb-3 text-dark"></i>
                    <h5 class="card-title"><?= lang('Accountant.balance_report') ?></h5>
                    <p class="card-text"><?= lang('Accountant.balance_report_desc') ?></p>
                    <a href="<?= site_url('reports/cashflow_account_balance') ?>" class="btn btn-dark">
                        <?= lang('Accountant.view_balance') ?>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-chart-line fa-3x mb-3 text-primary"></i>
                    <h5 class="card-title"><?= lang('Accountant.financial_overview') ?></h5>
                    <p class="card-text"><?= lang('Accountant.financial_overview_desc') ?></p>
                    <a href="<?= site_url('reports/financial_overview') ?>" class="btn btn-primary">
                        <?= lang('Accountant.view_overview') ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= view('partial/footer') ?>
