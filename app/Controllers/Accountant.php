<?php

namespace App\Controllers;

use App\Models\Employee;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

/**
 * Accountant module controller
 *
 * Provides access to all accountant-related features including:
 * - Cash flow management
 * - Financial reports
 * - Account management
 */
class Accountant extends Secure_Controller
{
    protected Employee $employee;

    public function __construct()
    {
        parent::__construct('accountant', null, 'office');
    }

    /**
     * Main accountant dashboard
     * @return string
     */
    public function getIndex(): string
    {
        $data['controller_name'] = 'accountant';

        return view('accountant/dashboard', $data);
    }

    /**
     * Redirect to cashflow entries
     * @return RedirectResponse
     */
    public function getCashflow(): RedirectResponse
    {
        return redirect()->to('cashflow');
    }

    /**
     * Redirect to cashflow accounts management
     * @return RedirectResponse
     */
    public function getAccounts(): RedirectResponse
    {
        return redirect()->to('cashflow_accounts');
    }

    /**
     * Redirect to cashflow categories management
     * @return RedirectResponse
     */
    public function getCategories(): RedirectResponse
    {
        return redirect()->to('cashflow_categories');
    }

    /**
     * Redirect to cashflow category types management
     * @return RedirectResponse
     */
    public function getCategoryTypes(): RedirectResponse
    {
        return redirect()->to('cashflow_category_types');
    }

    /**
     * Redirect to cashflow drafts
     * @return RedirectResponse
     */
    public function getDrafts(): RedirectResponse
    {
        return redirect()->to('cashflow/drafts');
    }

    /**
     * Redirect to financial reports
     * @return RedirectResponse
     */
    public function getReports(): RedirectResponse
    {
        return redirect()->to('reports/cashflow_ledger');
    }

    /**
     * Redirect to financial overview report
     * @return RedirectResponse
     */
    public function getFinancialOverview(): RedirectResponse
    {
        return redirect()->to('reports/financial_overview');
    }

    /**
     * Redirect to account balance report
     * @return RedirectResponse
     */
    public function getAccountBalance(): RedirectResponse
    {
        return redirect()->to('reports/cashflow_account_balance');
    }

    /**
     * Check if user has accountant access
     * @return ResponseInterface
     */
    public function getCheckAccess(): ResponseInterface
    {
        $hasAccess = $this->employee->has_grant('cashflow', $this->employee->get_logged_in_employee_info()->person_id);

        return $this->response->setJSON([
            'has_access' => $hasAccess
        ]);
    }
}
