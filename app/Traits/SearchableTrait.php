<?php

namespace App\Traits;

use CodeIgniter\HTTP\ResponseInterface;

/**
 * Trait for standardized search functionality in controllers.
 *
 * Provides common search patterns used across multiple controllers including:
 * - Pagination (limit/offset)
 * - Sorting with validation
 * - Search filtering
 * - JSON response formatting
 *
 * @see Secure_Controller
 */
trait SearchableTrait
{
    /**
     * Get sanitized search parameters from the request.
     *
     * @return array{
     *     search: string,
     *     limit: int,
     *     offset: int,
     *     sort: string,
     *     order: string
     * }
     */
    protected function getSearchParams(): array
    {
        return [
            'search' => (string) ($this->request->getGet('search', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? ''),
            'limit' => (int) ($this->request->getGet('limit', FILTER_SANITIZE_NUMBER_INT) ?? 25),
            'offset' => (int) ($this->request->getGet('offset', FILTER_SANITIZE_NUMBER_INT) ?? 0),
            'sort' => (string) ($this->request->getGet('sort', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? ''),
            'order' => (string) ($this->request->getGet('order', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? 'asc'),
        ];
    }

    /**
     * Validate and sanitize sort column against allowed columns.
     *
     * @param array $allowedColumns List of allowed column names
     * @param string $requestedColumn The column name from the request
     * @param string $defaultColumn The default column to use if invalid
     * @return string The validated column name
     */
    protected function validateSortColumn(array $allowedColumns, string $requestedColumn, string $defaultColumn): string
    {
        if (empty($requestedColumn) || !in_array($requestedColumn, $allowedColumns, true)) {
            return $defaultColumn;
        }
        return $requestedColumn;
    }

    /**
     * Validate and sanitize sort order.
     *
     * @param string $order The order from the request
     * @return string Either 'asc' or 'desc'
     */
    protected function validateSortOrder(string $order): string
    {
        return strtolower($order) === 'desc' ? 'desc' : 'asc';
    }

    /**
     * Build a standard JSON response for search results.
     *
     * @param int $total Total number of records
     * @param array $rows The data rows
     * @return ResponseInterface
     */
    protected function buildSearchResponse(int $total, array $rows): ResponseInterface
    {
        return $this->response->setJSON([
            'total' => $total,
            'rows' => $rows
        ]);
    }

    /**
     * Apply common search filters from request.
     *
     * @param array $additionalFilters Additional filters to merge
     * @return array Combined filters array
     */
    protected function getCommonFilters(array $additionalFilters = []): array
    {
        $filters = [
            'start_date' => $this->request->getGet('start_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'end_date' => $this->request->getGet('end_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
        ];

        return array_merge($filters, $additionalFilters);
    }
}
