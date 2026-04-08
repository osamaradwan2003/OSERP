<?php

namespace App\Controllers;

use App\Models\Cashflow_account;
use App\Models\Cashflow_category_type;

class Cashflow_reports extends Secure_Controller
{
    private ?array $typeMap = null;

    public function __construct()
    {
        parent::__construct('reports');

        helper('report');
    }

    public function ledger_input(): string
    {
        if (!$this->employee->has_grant('reports_cashflow', $this->employee->get_logged_in_employee_info()->person_id)) {
            return view('no_access', ['module_name' => lang('Module.reports'), 'permission_id' => 'reports_cashflow']);
        }

        $accountModel = model(Cashflow_account::class);

        $typeOptions = ['all' => lang('Reports.all')] + model(Cashflow_category_type::class)->getActiveOptions();

        return view('reports/cashflow_input', [
            'report_title' => lang('Reports.cashflow_ledger_report'),
            'report_url' => 'reports/cashflow_ledger',
            'accounts' => ['all' => lang('Reports.all')] + $accountModel->getActiveOptions(),
            'show_type' => true,
            'type_options' => $typeOptions
        ]);
    }

    public function ledger(string $start_date, string $end_date, string $account_id = 'all', string $entry_type = 'all'): string
    {
        if (!$this->employee->has_grant('reports_cashflow', $this->employee->get_logged_in_employee_info()->person_id)) {
            return view('no_access', ['module_name' => lang('Module.reports'), 'permission_id' => 'reports_cashflow']);
        }

        $this->disableCache();

        $db = db_connect();
        $entriesAlias = $db->getPrefix() . 'cashflow_entries';
        $builder = $db->table('cashflow_entries AS ' . $entriesAlias);
        $builder->select("$entriesAlias.*", false);
        $builder->select('account.name AS account_name, from_acc.name AS from_account_name, to_acc.name AS to_account_name', false);
        $builder->select('CONCAT(cpeople.first_name, " ", cpeople.last_name) AS customer_name, supplier_main.company_name AS supplier_name', false);
        $builder->join('cashflow_accounts AS account', "account.account_id = $entriesAlias.account_id", 'left');
        $builder->join('cashflow_accounts AS from_acc', "from_acc.account_id = $entriesAlias.from_account_id", 'left');
        $builder->join('cashflow_accounts AS to_acc', "to_acc.account_id = $entriesAlias.to_account_id", 'left');
        $builder->join('customers', "customers.person_id = $entriesAlias.customer_id", 'left');
        $builder->join('people AS cpeople', 'cpeople.person_id = customers.person_id', 'left');
        $builder->join('suppliers AS supplier_main', "supplier_main.person_id = $entriesAlias.supplier_id", 'left');
        $builder->where("$entriesAlias.deleted", 0);
        $builder->where("$entriesAlias.status", 'posted');
        $builder->where("DATE($entriesAlias.entry_date) >=", $start_date);
        $builder->where("DATE($entriesAlias.entry_date) <=", $end_date);

        if ($account_id !== 'all') {
            $aid = (int) $account_id;
            $builder->groupStart();
            $builder->where("$entriesAlias.account_id", $aid);
            $builder->orWhere("$entriesAlias.from_account_id", $aid);
            $builder->orWhere("$entriesAlias.to_account_id", $aid);
            $builder->groupEnd();
        }

        if ($entry_type !== 'all') {
            $builder->where("$entriesAlias.entry_type", $entry_type);
        }

        $rows = $builder->orderBy("$entriesAlias.entry_date", 'DESC')->get()->getResultArray();

        $dataRows = [];
        $incomeTotal = 0.0;
        $outcomeTotal = 0.0;

        foreach ($rows as $row) {
            $amount = (float) $row['amount'];
            $calcMethod = $this->getCalcMethod((string) ($row['entry_type'] ?? ''));
            if ($calcMethod === 'add') {
                $incomeTotal += $amount;
            } elseif ($calcMethod === 'subtract') {
                $outcomeTotal += $amount;
            }

            $accountDisplay = $calcMethod === 'transfer'
                ? ($row['from_account_name'] ?? '-') . ' -> ' . ($row['to_account_name'] ?? '-')
                : ($row['account_name'] ?? '-');

            $partyDisplay = $row['customer_name'] ?: ($row['supplier_name'] ?: '-');
            $typeLabel = $this->getTypeLabel((string) ($row['entry_type'] ?? ''));

            $dataRows[] = [
                'entry_id' => $row['entry_id'],
                'date' => to_datetime(strtotime($row['entry_date'])),
                'type' => $typeLabel,
                'amount' => to_currency($amount),
                'account' => $accountDisplay,
                'party' => $partyDisplay,
                'status' => lang('Cashflow.' . $row['status']),
                'description' => $row['description'] ?? ''
            ];
        }

        $headers = [
            ['entry_id' => lang('Common.id')],
            ['date' => lang('Reports.date')],
            ['type' => lang('Reports.type')],
            ['amount' => lang('Cashflow.amount')],
            ['account' => lang('Cashflow.account')],
            ['party' => lang('Cashflow.party')],
            ['status' => lang('Cashflow.status')],
            ['description' => lang('Common.description')]
        ];

        return view('reports/tabular', [
            'title' => lang('Reports.cashflow_ledger_report'),
            'subtitle' => $this->subtitle($start_date, $end_date),
            'headers' => $headers,
            'data' => $dataRows,
            'summary_data' => [
                'subtotal' => $incomeTotal,
                'cost' => $outcomeTotal,
                'profit' => $incomeTotal - $outcomeTotal
            ]
        ]);
    }

    public function summary_input(): string
    {
        if (!$this->employee->has_grant('reports_cashflow', $this->employee->get_logged_in_employee_info()->person_id)) {
            return view('no_access', ['module_name' => lang('Module.reports'), 'permission_id' => 'reports_cashflow']);
        }

        $accountModel = model(Cashflow_account::class);

        return view('reports/cashflow_input', [
            'report_title' => lang('Reports.cashflow_summary_report'),
            'report_url' => 'reports/cashflow_summary',
            'accounts' => ['all' => lang('Reports.all')] + $accountModel->getActiveOptions(),
            'show_type' => false
        ]);
    }

    public function summary(string $start_date, string $end_date, string $account_id = 'all'): string
    {
        if (!$this->employee->has_grant('reports_cashflow', $this->employee->get_logged_in_employee_info()->person_id)) {
            return view('no_access', ['module_name' => lang('Module.reports'), 'permission_id' => 'reports_cashflow']);
        }

        $this->disableCache();

        $builder = db_connect()->table('cashflow_entries');
        $builder->select('entry_type, SUM(amount) AS total_amount');
        $builder->where('deleted', 0);
        $builder->where('status', 'posted');
        $builder->where('DATE(entry_date) >=', $start_date);
        $builder->where('DATE(entry_date) <=', $end_date);
        if ($account_id !== 'all') {
            $aid = (int) $account_id;
            $builder->groupStart();
            $builder->where('account_id', $aid);
            $builder->orWhere('from_account_id', $aid);
            $builder->orWhere('to_account_id', $aid);
            $builder->groupEnd();
        }
        $builder->groupBy('entry_type');

        $rows = $builder->get()->getResultArray();

        $summaryMap = [];
        foreach ($rows as $row) {
            $summaryMap[$row['entry_type']] = (float) $row['total_amount'];
        }

        $netAdd = 0.0;
        $netSubtract = 0.0;
        foreach ($summaryMap as $type => $total) {
            $calcMethod = $this->getCalcMethod((string) $type);
            if ($calcMethod === 'add') {
                $netAdd += $total;
            } elseif ($calcMethod === 'subtract') {
                $netSubtract += $total;
            }
        }

        $typeMap = $this->getTypeMap();
        $dataRows = [];
        foreach ($typeMap as $typeCode => $typeInfo) {
            $dataRows[] = [
                'type' => $this->getTypeLabel($typeCode),
                'amount' => to_currency($summaryMap[$typeCode] ?? 0)
            ];
        }

        $headers = [
            ['type' => lang('Reports.type')],
            ['amount' => lang('Cashflow.amount')]
        ];

        return view('reports/tabular', [
            'title' => lang('Reports.cashflow_summary_report'),
            'subtitle' => $this->subtitle($start_date, $end_date),
            'headers' => $headers,
            'data' => $dataRows,
            'summary_data' => [
                'subtotal' => $netAdd,
                'cost' => $netSubtract,
                'profit' => $netAdd - $netSubtract
            ]
        ]);
    }

    public function account_balance_input(): string
    {
        if (!$this->employee->has_grant('reports_cashflow', $this->employee->get_logged_in_employee_info()->person_id)) {
            return view('no_access', ['module_name' => lang('Module.reports'), 'permission_id' => 'reports_cashflow']);
        }

        $accountModel = model(Cashflow_account::class);

        return view('reports/cashflow_input', [
            'report_title' => lang('Reports.cashflow_account_balance_report'),
            'report_url' => 'reports/cashflow_account_balance',
            'accounts' => ['all' => lang('Reports.all')] + $accountModel->getActiveOptions(),
            'show_type' => false
        ]);
    }

    public function account_balance(string $start_date, string $end_date, string $account_id = 'all'): string
    {
        if (!$this->employee->has_grant('reports_cashflow', $this->employee->get_logged_in_employee_info()->person_id)) {
            return view('no_access', ['module_name' => lang('Module.reports'), 'permission_id' => 'reports_cashflow']);
        }

        $this->disableCache();

        $accountsBuilder = db_connect()->table('cashflow_accounts');
        if ($account_id !== 'all') {
            $accountsBuilder->where('account_id', (int) $account_id);
        }
        $accounts = $accountsBuilder->get()->getResultArray();

        $rows = [];
        $closingTotal = 0.0;

        foreach ($accounts as $account) {
            $aid = (int) $account['account_id'];
            $opening = (float) $account['opening_balance'];
            $periodNet = $this->calculateAccountNet($aid, $start_date, $end_date);
            $historicalNet = $this->calculateAccountNet($aid, '1900-01-01', $end_date);
            $closing = $opening + $historicalNet;
            $closingTotal += $closing;

            $rows[] = [
                'account' => $account['name'],
                'opening_balance' => to_currency($opening),
                'period_net' => to_currency($periodNet),
                'closing_balance' => to_currency($closing)
            ];
        }

        $headers = [
            ['account' => lang('Cashflow.account')],
            ['opening_balance' => lang('Reports.opening_balance')],
            ['period_net' => lang('Reports.period_net')],
            ['closing_balance' => lang('Reports.closing_balance')]
        ];

        return view('reports/tabular', [
            'title' => lang('Reports.cashflow_account_balance_report'),
            'subtitle' => $this->subtitle($start_date, $end_date),
            'headers' => $headers,
            'data' => $rows,
            'summary_data' => [
                'total' => $closingTotal
            ]
        ]);
    }

    public function financial_overview_input(): string
    {
        if (!$this->employee->has_grant('reports_financial_overview', $this->employee->get_logged_in_employee_info()->person_id)) {
            return view('no_access', ['module_name' => lang('Module.reports'), 'permission_id' => 'reports_financial_overview']);
        }

        return view('reports/financial_overview_input', [
            'report_title' => lang('Reports.financial_overview_report'),
            'report_url' => 'reports/financial_overview'
        ]);
    }

    public function financial_overview(string $start_date, string $end_date): string
    {
        if (!$this->employee->has_grant('reports_financial_overview', $this->employee->get_logged_in_employee_info()->person_id)) {
            return view('no_access', ['module_name' => lang('Module.reports'), 'permission_id' => 'reports_financial_overview']);
        }

        $this->disableCache();
        $db = db_connect();

        $salesNet = (float) ($db->table('sales_payments AS sales_payments')
            ->select('SUM(sales_payments.payment_amount - sales_payments.cash_refund) AS total', false)
            ->join('sales AS sales', 'sales.sale_id = sales_payments.sale_id')
            ->where('DATE(sales.sale_time) >=', $start_date)
            ->where('DATE(sales.sale_time) <=', $end_date)
            ->get()->getRow()->total ?? 0);

        $receivingsNet = (float) ($db->table('receivings_items AS receivings_items')
            ->select('SUM(receivings_items.quantity_purchased * receivings_items.item_cost_price) AS total', false)
            ->join('receivings AS receivings', 'receivings.receiving_id = receivings_items.receiving_id')
            ->where('DATE(receivings.receiving_time) >=', $start_date)
            ->where('DATE(receivings.receiving_time) <=', $end_date)
            ->get()->getRow()->total ?? 0);

        $cashflowRows = $db->table('cashflow_entries')
            ->select('entry_type, SUM(amount) AS total_amount')
            ->where('deleted', 0)
            ->where('status', 'posted')
            ->where('DATE(entry_date) >=', $start_date)
            ->where('DATE(entry_date) <=', $end_date)
            ->groupBy('entry_type')
            ->get()
            ->getResultArray();

        $cashflowNet = 0.0;
        foreach ($cashflowRows as $row) {
            $calcMethod = $this->getCalcMethod((string) ($row['entry_type'] ?? ''));
            $total = (float) ($row['total_amount'] ?? 0);
            if ($calcMethod === 'add') {
                $cashflowNet += $total;
            } elseif ($calcMethod === 'subtract') {
                $cashflowNet -= $total;
            }
        }
        $netMovement = $salesNet - $receivingsNet + $cashflowNet;

        $dataRows = [
            ['metric' => lang('Reports.sales_net'), 'amount' => to_currency($salesNet)],
            ['metric' => lang('Reports.receivings_net'), 'amount' => to_currency(-1 * $receivingsNet)],
            ['metric' => lang('Reports.cashflow_net'), 'amount' => to_currency($cashflowNet)],
            ['metric' => lang('Reports.net_cash_movement'), 'amount' => to_currency($netMovement)]
        ];

        $headers = [
            ['metric' => lang('Reports.report')],
            ['amount' => lang('Cashflow.amount')]
        ];

        return view('reports/tabular', [
            'title' => lang('Reports.financial_overview_report'),
            'subtitle' => $this->subtitle($start_date, $end_date),
            'headers' => $headers,
            'data' => $dataRows,
            'summary_data' => [
                'net_cash_movement' => $netMovement
            ]
        ]);
    }

    private function calculateAccountNet(int $accountId, string $startDate, string $endDate): float
    {
        $db = db_connect();
        $rows = $db->table('cashflow_entries')
            ->select('entry_type, amount, account_id, from_account_id, to_account_id')
            ->where('deleted', 0)
            ->where('status', 'posted')
            ->where('DATE(entry_date) >=', $startDate)
            ->where('DATE(entry_date) <=', $endDate)
            ->groupStart()
            ->where('account_id', $accountId)
            ->orWhere('from_account_id', $accountId)
            ->orWhere('to_account_id', $accountId)
            ->groupEnd()
            ->get()
            ->getResultArray();

        $net = 0.0;
        foreach ($rows as $row) {
            $calcMethod = $this->getCalcMethod((string) ($row['entry_type'] ?? ''));
            $amount = (float) ($row['amount'] ?? 0);

            if ($calcMethod === 'transfer') {
                if ((int) ($row['from_account_id'] ?? 0) === $accountId) {
                    $net -= $amount;
                }
                if ((int) ($row['to_account_id'] ?? 0) === $accountId) {
                    $net += $amount;
                }
                continue;
            }

            if ((int) ($row['account_id'] ?? 0) !== $accountId) {
                continue;
            }

            if ($calcMethod === 'add') {
                $net += $amount;
            } elseif ($calcMethod === 'subtract') {
                $net -= $amount;
            }
        }

        return $net;
    }

    private function subtitle(string $startDate, string $endDate): string
    {
        $dateFormat = config(\Config\OSPOS::class)->settings['dateformat'];

        return date($dateFormat, strtotime($startDate)) . ' - ' . date($dateFormat, strtotime($endDate));
    }

    private function disableCache(): void
    {
        $this->response->setHeader('Pragma', 'no-cache')
            ->appendHeader('Last-Modified', gmdate('D, d M Y H:i:s') . ' GMT')
            ->appendHeader('Cache-Control', 'no-store, no-cache, must-revalidate')
            ->appendHeader('Cache-Control', 'post-check=0, pre-check=0');
    }

    private function getTypeMap(): array
    {
        if ($this->typeMap === null) {
            $this->typeMap = model(Cashflow_category_type::class)->getTypeMap();
        }

        return $this->typeMap;
    }

    private function getTypeLabel(string $typeCode): string
    {
        $map = $this->getTypeMap();
        $label = $map[$typeCode]['type_label'] ?? '';

        return $label !== '' ? $label : lang('Cashflow.' . $typeCode);
    }

    private function getCalcMethod(string $typeCode): string
    {
        $map = $this->getTypeMap();
        return (string) ($map[$typeCode]['calc_method'] ?? '');
    }
}


