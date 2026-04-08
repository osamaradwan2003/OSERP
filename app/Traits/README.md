# Controller Refactoring Documentation

This document describes the refactoring changes made to the controllers in the `app/Controllers` directory.

## Overview

The controllers have been analyzed for common patterns and duplications. Several traits have been created to extract reusable functionality and promote code consistency across the codebase.

## New Traits Created

### 1. SearchableTrait (`app/Traits/SearchableTrait.php`)

Provides standardized search functionality for controllers that implement data tables with pagination, sorting, and filtering.

**Usage:**
```php
use App\Traits\SearchableTrait;

class Items extends Secure_Controller
{
    use SearchableTrait;
    
    public function getSearch(): ResponseInterface
    {
        $params = $this->getSearchParams();
        $allowedColumns = ['item_id', 'name', 'category', 'price'];
        $sort = $this->validateSortColumn($allowedColumns, $params['sort'], 'item_id');
        $order = $this->validateSortOrder($params['order']);
        
        // ... perform search ...
        
        return $this->buildSearchResponse($total, $rows);
    }
}
```

**Methods:**
- `getSearchParams(): array` - Get sanitized search parameters from request
- `validateSortColumn(array $allowedColumns, string $requestedColumn, string $defaultColumn): string` - Validate sort column
- `validateSortOrder(string $order): string` - Validate sort order (asc/desc)
- `buildSearchResponse(int $total, array $rows): ResponseInterface` - Build standard JSON response
- `getCommonFilters(array $additionalFilters = []): array` - Get common search filters

### 2. ValidatesInputTrait (`app/Traits/ValidatesInputTrait.php`)

Provides standardized input validation and sanitization helpers.

**Usage:**
```php
use App\Traits\ValidatesInputTrait;

class Customers extends Persons
{
    use ValidatesInputTrait;
    
    public function postSave(int $customer_id = NEW_ENTRY): ResponseInterface
    {
        $firstName = $this->getTrimmedString('first_name');
        $lastName = $this->getTrimmedString('last_name');
        $email = strtolower($this->getTrimmedString('email', true));
        $discount = $this->getDecimal('discount', 0.0);
        $isActive = $this->getCheckbox('is_active');
        
        if ($this->isNewEntry($customer_id)) {
            // Handle new entry
        }
        
        // ... save logic ...
        
        return $this->successResponse(lang('Customers.successful_adding'), $customerId);
    }
}
```

**Methods:**
- `getPost(string $key, ?int $filter = null, mixed $default = null): mixed` - Get sanitized POST value
- `getQuery(string $key, ?int $filter = null, mixed $default = null): mixed` - Get sanitized GET value
- `getNullableInt(string $key): ?int` - Get nullable integer from POST
- `getDecimal(string $key, float $default = 0.0): float` - Get decimal/float value
- `getTrimmedString(string $key, bool $sanitize = true): string` - Get trimmed string
- `getCheckbox(string $key): bool` - Get boolean from checkbox
- `successResponse(string $message, mixed $id = null, array $additionalData = []): ResponseInterface` - Build success JSON
- `errorResponse(string $message, mixed $id = null, array $additionalData = []): ResponseInterface` - Build error JSON
- `validateRequired(array $fields): array` - Validate required fields
- `isNewEntry(int $id): bool` - Check if ID represents new entry

### 3. PersonDataTrait (`app/Traits/PersonDataTrait.php`)

Provides common patterns for handling person-related data (customers, employees, suppliers).

**Usage:**
```php
use App\Traits\PersonDataTrait;

class Customers extends Persons
{
    use PersonDataTrait;
    
    public function postSave(int $customer_id = NEW_ENTRY): ResponseInterface
    {
        $personData = $this->buildPersonData();
        $displayName = $this->formatCompanyName($companyName, $personName);
        $location = $this->buildLocationString($zip, $city);
        
        // ... save logic ...
    }
}
```

**Methods:**
- `buildPersonData(): array` - Build person data array from POST request
- `createEmptyStats(): stdClass` - Create empty stats object
- `formatPersonName(string $firstName, string $lastName): string` - Format person name
- `formatCompanyName(?string $companyName, string $personName): string` - Format company name with fallback
- `buildLocationString(?string $zip, ?string $city): string` - Build location string
- `nameize(string $input): string` - Properly capitalize names

### 4. ReportDataTrait (`app/Traits/ReportDataTrait.php`)

Provides standardized report functionality for controllers that generate reports.

**Usage:**
```php
use App\Traits\ReportDataTrait;

class Reports extends Secure_Controller
{
    use ReportDataTrait;
    
    public function summary_sales(string $start_date, string $end_date): string
    {
        $this->disableReportCache();
        
        if (!$this->hasReportPermission('reports_sales')) {
            return $this->reportNoAccess('reports_sales');
        }
        
        $subtitle = $this->buildReportSubtitle($start_date, $end_date);
        
        // ... build report data ...
        
        return $this->renderTabularReport($title, $subtitle, $headers, $data, $summary);
    }
}
```

**Methods:**
- `disableReportCache(): void` - Disable browser caching
- `buildReportSubtitle(string $startDate, string $endDate): string` - Build formatted subtitle
- `hasReportPermission(string $permissionId): bool` - Check report permission
- `reportNoAccess(string $permissionId): string` - Return no access view
- `buildReportData(string $title, string $subtitle, array $headers, array $data, array $summaryData = []): array` - Build report data structure
- `renderTabularReport(string $title, string $subtitle, array $headers, array $data, array $summaryData = []): string` - Render tabular report
- `getReportDateRange(): array` - Get date range from request
- `formatReportCurrency(float $value): string` - Format currency
- `formatReportTax(float $value): string` - Format tax
- `formatReportQuantity(float $value): string` - Format quantity
- `formatReportDate(int $timestamp): string` - Format date
- `formatReportDateTime(int $timestamp): string` - Format datetime

### 5. DeletesEntitiesTrait (`app/Traits/DeletesEntitiesTrait.php`)

Provides standardized delete functionality for controllers.

**Usage:**
```php
use App\Traits\DeletesEntitiesTrait;

class Items extends Secure_Controller
{
    use DeletesEntitiesTrait;
    
    public function postDelete(): ResponseInterface
    {
        if (!$this->hasDeletePermission('items_delete')) {
            return $this->deletePermissionDenied('Items');
        }
        
        $ids = $this->getDeleteIds();
        
        if (!$this->validateDeleteIds($ids)) {
            return $this->deleteErrorResponse('Items');
        }
        
        $count = $this->softDeleteEntities($this->item, $ids);
        
        return $this->deleteSuccessResponse($count, 'Items', 'Items');
    }
}
```

**Methods:**
- `getDeleteIds(): array` - Get IDs for deletion from POST
- `validateDeleteIds(array $ids): bool` - Validate IDs are not empty
- `deleteSuccessResponse(int $count, string $singleKey, string $pluralKey): ResponseInterface` - Build success response
- `deleteErrorResponse(string $cannotDeleteKey): ResponseInterface` - Build error response
- `hasDeletePermission(string $permissionId): bool` - Check delete permission
- `deletePermissionDenied(string $messageKey): ResponseInterface` - Return permission denied
- `softDeleteEntities(object $model, array $ids, string $deletedField = 'deleted'): int` - Execute soft delete
- `hardDeleteEntities(object $model, array $ids): int` - Execute hard delete

## Autoloader Configuration

The traits namespace has been added to the autoloader configuration in `app/Config/Autoload.php`:

```php
public $psr4 = [
    APP_NAMESPACE => APPPATH,
    'Config' => APPPATH . 'Config',
    'dompdf' => APPPATH . 'ThirdParty/dom_pdf/src',
    'App\Traits' => APPPATH . 'Traits'
];
```

## Benefits of Refactoring

1. **Code Reusability**: Common patterns are extracted into traits, reducing code duplication
2. **Consistency**: All controllers use the same validation and response patterns
3. **Maintainability**: Changes to common functionality only need to be made in one place
4. **Testability**: Traits can be tested independently
5. **Documentation**: Self-documenting code with clear method names and PHPDoc comments

## Migration Guide

To migrate existing controllers to use the new traits:

1. Add the `use` statement for the required trait(s) at the top of the controller class
2. Replace duplicated code with trait method calls
3. Ensure the controller has access to required properties (e.g., `$this->request`, `$this->response`, `$this->config`)
4. Test the refactored controller thoroughly

## Example: Refactoring a Controller

### Before:
```php
public function getSearch(): ResponseInterface
{
    $search = $this->request->getGet('search', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
    $limit = (int) ($this->request->getGet('limit', FILTER_SANITIZE_NUMBER_INT) ?? 25);
    $offset = (int) ($this->request->getGet('offset', FILTER_SANITIZE_NUMBER_INT) ?? 0);
    $sort = $this->request->getGet('sort', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? 'item_id';
    $order = strtolower($this->request->getGet('order', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? 'asc') === 'desc' ? 'desc' : 'asc';
    
    $allowedSort = ['item_id', 'name', 'category'];
    if (!in_array($sort, $allowedSort, true)) {
        $sort = 'item_id';
    }
    
    // ... search logic ...
    
    return $this->response->setJSON(['total' => $total, 'rows' => $rows]);
}
```

### After:
```php
use App\Traits\SearchableTrait;

class Items extends Secure_Controller
{
    use SearchableTrait;
    
    public function getSearch(): ResponseInterface
    {
        $params = $this->getSearchParams();
        $sort = $this->validateSortColumn(['item_id', 'name', 'category'], $params['sort'], 'item_id');
        $order = $this->validateSortOrder($params['order']);
        
        // ... search logic ...
        
        return $this->buildSearchResponse($total, $rows);
    }
}
```

## Future Improvements

1. Create additional traits for:
   - File upload handling
   - Email sending
   - PDF generation
   - Barcode generation

2. Consider creating abstract base controllers for:
   - `PersonController` (for Customers, Employees, Suppliers)
   - `ReportController` (for Reports, Cashflow_reports)
   - `TransactionController` (for Sales, Receivings, Transfers)

3. Add unit tests for all traits
