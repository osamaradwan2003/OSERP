<?php

namespace App\Traits;

use CodeIgniter\HTTP\ResponseInterface;

/**
 * Trait for standardized report functionality in controllers.
 *
 * Provides common patterns for report generation used across multiple controllers including:
 * - Date range handling
 * - Report subtitle formatting
 * - Cache control headers
 * - Permission checking
 *
 * @see Reports
 * @see Cashflow_reports
 * @see Secure_Controller
 */
trait ReportDataTrait
{
    /**
     * Disable browser caching for report responses.
     *
     * Sets headers to prevent caching of sensitive report data.
     *
     * @return void
     */
    protected function disableReportCache(): void
    {
        $this->response->setHeader('Pragma', 'no-cache')
            ->appendHeader('Last-Modified', gmdate('D, d M Y H:i:s') . ' GMT')
            ->appendHeader('Cache-Control', 'no-store, no-cache, must-revalidate')
            ->appendHeader('Cache-Control', 'post-check=0, pre-check=0');
    }

    /**
     * Build a formatted subtitle for reports with date range.
     *
     * @param string $startDate Start date string
     * @param string $endDate End date string
     * @return string Formatted subtitle
     */
    protected function buildReportSubtitle(string $startDate, string $endDate): string
    {
        $dateFormat = $this->config['dateformat'] ?? 'm/d/Y';

        $start = strtotime($startDate);
        $end = strtotime($endDate);

        if ($start === false || $end === false) {
            return $startDate . ' - ' . $endDate;
        }

        return date($dateFormat, $start) . ' - ' . date($dateFormat, $end);
    }

    /**
     * Check if user has permission for a specific report.
     *
     * @param string $permissionId The permission ID to check
     * @return bool True if user has permission
     */
    protected function hasReportPermission(string $permissionId): bool
    {
        $personId = $this->employee->get_logged_in_employee_info()->person_id;
        return $this->employee->has_grant($permissionId, $personId);
    }

    /**
     * Return no access view if permission denied.
     *
     * @param string $permissionId The permission ID that was denied
     * @return string The no_access view
     */
    protected function reportNoAccess(string $permissionId): string
    {
        return view('no_access', [
            'module_name' => lang('Module.reports'),
            'permission_id' => $permissionId
        ]);
    }

    /**
     * Build standard tabular report data structure.
     *
     * @param string $title Report title
     * @param string $subtitle Report subtitle
     * @param array $headers Column headers
     * @param array $data Report data rows
     * @param array $summaryData Optional summary data
     * @return array The data array for the view
     */
    protected function buildReportData(string $title, string $subtitle, array $headers, array $data, array $summaryData = []): array
    {
        $reportData = [
            'title' => $title,
            'subtitle' => $subtitle,
            'headers' => $headers,
            'data' => $data,
        ];

        if (!empty($summaryData)) {
            $reportData['summary_data'] = $summaryData;
        }

        return $reportData;
    }

    /**
     * Render a tabular report view.
     *
     * @param string $title Report title
     * @param string $subtitle Report subtitle
     * @param array $headers Column headers
     * @param array $data Report data rows
     * @param array $summaryData Optional summary data
     * @return string The rendered view
     */
    protected function renderTabularReport(string $title, string $subtitle, array $headers, array $data, array $summaryData = []): string
    {
        $data = $this->buildReportData($title, $subtitle, $headers, $data, $summaryData);
        return view('reports/tabular', $data);
    }

    /**
     * Get date range from request with defaults.
     *
     * @return array{start_date: string, end_date: string}
     */
    protected function getReportDateRange(): array
    {
        $startDate = $this->request->getGet('start_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? date('Y-m-d', strtotime('-30 days'));
        $endDate = $this->request->getGet('end_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? date('Y-m-d');

        return [
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }

    /**
     * Format a currency value for reports.
     *
     * @param float $value The value to format
     * @return string Formatted currency string
     */
    protected function formatReportCurrency(float $value): string
    {
        return to_currency($value);
    }

    /**
     * Format a tax value for reports.
     *
     * @param float $value The value to format
     * @return string Formatted tax string
     */
    protected function formatReportTax(float $value): string
    {
        return to_currency_tax($value);
    }

    /**
     * Format a quantity value for reports.
     *
     * @param float $value The value to format
     * @return string Formatted quantity string
     */
    protected function formatReportQuantity(float $value): string
    {
        return to_quantity_decimals($value);
    }

    /**
     * Format a date for reports.
     *
     * @param int $timestamp Unix timestamp
     * @return string Formatted date string
     */
    protected function formatReportDate(int $timestamp): string
    {
        return to_date($timestamp);
    }

    /**
     * Format a datetime for reports.
     *
     * @param int $timestamp Unix timestamp
     * @return string Formatted datetime string
     */
    protected function formatReportDateTime(int $timestamp): string
    {
        return to_datetime($timestamp);
    }
}
