<?php

namespace App\Controllers;

use App\Models\Cashflow_account;
use App\Models\Cashflow_attachment;
use App\Models\Cashflow_category;
use App\Models\Cashflow_category_type;
use App\Models\Cashflow_entry;
use App\Models\Customer;
use App\Models\Receiving;
use App\Models\Sale;
use App\Models\Supplier;
use App\Traits\SearchableTrait;
use App\Traits\ValidatesInputTrait;
use App\Traits\DeletesEntitiesTrait;
use CodeIgniter\HTTP\ResponseInterface;
use Config\OSPOS;

class Cashflow extends Secure_Controller
{
    use SearchableTrait;
    use ValidatesInputTrait;
    use DeletesEntitiesTrait;

    private Cashflow_entry $cashflow_entry;
    private Cashflow_account $cashflow_account;
    private Cashflow_category $cashflow_category;
    private ?Cashflow_category_type $cashflow_category_type = null;
    private Cashflow_attachment $cashflow_attachment;
    private Customer $customer;
    private Sale $sale;
    private Receiving $receiving;
    private Supplier $supplier;
    private array $config;
    private string $entriesAlias;
    private ?array $calcMethodMap = null;

    public function __construct()
    {
        parent::__construct('cashflow');

        $this->cashflow_entry = model(Cashflow_entry::class);
        $this->cashflow_account = model(Cashflow_account::class);
        $this->cashflow_category = model(Cashflow_category::class);
        $this->cashflow_category_type = model(Cashflow_category_type::class);
        $this->cashflow_attachment = model(Cashflow_attachment::class);
        $this->customer = model(Customer::class);
        $this->sale = model(Sale::class);
        $this->receiving = model(Receiving::class);
        $this->supplier = model(Supplier::class);
        $this->config = config(OSPOS::class)->settings;
        $this->entriesAlias = db_connect()->getPrefix() . 'cashflow_entries';
    }

    public function getIndex(): string
    {
        $data['table_headers'] = json_encode([
            ['field' => 'state', 'checkbox' => true, 'title' => '', 'sortable' => false, 'switchable' => false, 'printIgnore' => true],
            ['field' => 'entry_id', 'title' => lang('Common.id'), 'sortable' => true, 'switchable' => true, 'checked' => true],
            ['field' => 'entry_date', 'title' => lang('Reports.date'), 'sortable' => true, 'switchable' => true],
            ['field' => 'category_type', 'title' => lang('Cashflow.category_type'), 'sortable' => false, 'switchable' => true],
            ['field' => 'category_name', 'title' => lang('Cashflow.category'), 'sortable' => false, 'switchable' => true],
            ['field' => 'amount', 'title' => lang('Cashflow.amount'), 'sortable' => true, 'switchable' => true],
            ['field' => 'amount_after', 'title' => lang('Cashflow.amount_after_transaction'), 'sortable' => false, 'switchable' => true],
            ['field' => 'account_display', 'title' => lang('Cashflow.account'), 'sortable' => false, 'switchable' => true],
            ['field' => 'party_name', 'title' => lang('Cashflow.party'), 'sortable' => false, 'switchable' => true],
            ['field' => 'status', 'title' => lang('Cashflow.status'), 'sortable' => true, 'switchable' => true],
            ['field' => 'description', 'title' => lang('Common.description'), 'sortable' => false, 'switchable' => true],
            ['field' => 'related_invoice', 'title' => lang('Cashflow.sale_reference'), 'sortable' => false, 'switchable' => true, 'escape' => false],
            ['field' => 'vendor_invoice', 'title' => lang('Cashflow.receiving_reference'), 'sortable' => false, 'switchable' => true, 'escape' => false],
            ['field' => 'details', 'title' => lang('Common.det'), 'sortable' => false, 'switchable' => false, 'escape' => false],
            ['field' => 'edit', 'title' => '', 'sortable' => false, 'switchable' => false, 'escape' => false]
        ]);
        $data['accounts'] = ['' => lang('Reports.all')] + $this->cashflow_account->getActiveOptions();
        $data['types'] = ['' => lang('Reports.all')] + $this->cashflow_category_type->getActiveOptions();
        $data['statuses'] = [
            'posted' => lang('Cashflow.posted')
        ];
        $data['categories'] = ['' => lang('Reports.all')] + $this->cashflow_category->getActiveOptions();
        $data['categories_by_type'] = $this->buildCategoryOptionsByType(lang('Reports.all'));
        $data['controller_name'] = 'cashflow';
        $data['default_status'] = 'posted';
        $data['status_fixed'] = true;
        $data['show_post_button'] = false;
        $data['is_drafts_list'] = false;

        return view('cashflow/manage', $data);
    }

    public function getDrafts(): string
    {
        $data['table_headers'] = json_encode([
            ['field' => 'state', 'checkbox' => true, 'title' => '', 'sortable' => false, 'switchable' => false, 'printIgnore' => true],
            ['field' => 'entry_id', 'title' => lang('Common.id'), 'sortable' => true, 'switchable' => true, 'checked' => true],
            ['field' => 'entry_date', 'title' => lang('Reports.date'), 'sortable' => true, 'switchable' => true],
            ['field' => 'category_type', 'title' => lang('Cashflow.category_type'), 'sortable' => false, 'switchable' => true],
            ['field' => 'category_name', 'title' => lang('Cashflow.category'), 'sortable' => false, 'switchable' => true],
            ['field' => 'amount', 'title' => lang('Cashflow.amount'), 'sortable' => true, 'switchable' => true],
            ['field' => 'amount_after', 'title' => lang('Cashflow.amount_after_transaction'), 'sortable' => false, 'switchable' => true],
            ['field' => 'account_display', 'title' => lang('Cashflow.account'), 'sortable' => false, 'switchable' => true],
            ['field' => 'party_name', 'title' => lang('Cashflow.party'), 'sortable' => false, 'switchable' => true],
            ['field' => 'status', 'title' => lang('Cashflow.status'), 'sortable' => true, 'switchable' => true],
            ['field' => 'description', 'title' => lang('Common.description'), 'sortable' => false, 'switchable' => true],
            ['field' => 'related_invoice', 'title' => lang('Cashflow.sale_reference'), 'sortable' => false, 'switchable' => true, 'escape' => false],
            ['field' => 'vendor_invoice', 'title' => lang('Cashflow.receiving_reference'), 'sortable' => false, 'switchable' => true, 'escape' => false],
            ['field' => 'details', 'title' => lang('Common.det'), 'sortable' => false, 'switchable' => false, 'escape' => false],
            ['field' => 'edit', 'title' => '', 'sortable' => false, 'switchable' => false, 'escape' => false]
        ]);
        $data['accounts'] = ['' => lang('Reports.all')] + $this->cashflow_account->getActiveOptions();
        $data['types'] = ['' => lang('Reports.all')] + $this->cashflow_category_type->getActiveOptions();
        $data['statuses'] = [
            'draft' => lang('Cashflow.draft')
        ];
        $data['categories'] = ['' => lang('Reports.all')] + $this->cashflow_category->getActiveOptions();
        $data['categories_by_type'] = $this->buildCategoryOptionsByType(lang('Reports.all'));
        $data['controller_name'] = 'cashflow';
        $data['default_status'] = 'draft';
        $data['status_fixed'] = true;
        $data['show_post_button'] = true;
        $data['is_drafts_list'] = true;

        return view('cashflow/manage', $data);
    }

    public function getSearch(): ResponseInterface
    {
        $params = $this->getSearchParams();
        $type = (string) ($this->request->getGet('entry_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
        $status = (string) ($this->request->getGet('status', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
        $accountId = (int) ($this->request->getGet('account_id', FILTER_SANITIZE_NUMBER_INT) ?? 0);
        $categoryId = (int) ($this->request->getGet('category_id', FILTER_SANITIZE_NUMBER_INT) ?? 0);
        $startDate = (string) ($this->request->getGet('start_date') ?? '');
        $endDate = (string) ($this->request->getGet('end_date') ?? '');
        $show_deleted = (bool) ($this->request->getGet('show_deleted', FILTER_SANITIZE_NUMBER_INT) ?? false);

        $builder = $this->buildEntriesQuery($show_deleted);
        $countBuilder = $this->buildEntriesQuery($show_deleted);

        $this->applyFilters($builder, $params['search'], $type, $status, $accountId, $categoryId, $startDate, $endDate);
        $this->applyFilters($countBuilder, $params['search'], $type, $status, $accountId, $categoryId, $startDate, $endDate);

        $allowedSort = ['entry_id', 'entry_date', 'entry_type', 'amount', 'status'];
        $sort = $this->validateSortColumn($allowedSort, $params['sort'], 'entry_id');
        $order = $this->validateSortOrder($params['order']);

        $rows = $builder->orderBy("{$this->entriesAlias}.$sort", $order)->limit($params['limit'], $params['offset'])->get()->getResultArray();
        $total = (clone $countBuilder)->countAllResults();
        $balanceMap = $this->buildAmountAfterMap($countBuilder, $accountId);

        $resultRows = [];
        foreach ($rows as $row) {
            $resultRows[] = $this->mapRow($row, $balanceMap[(int) $row['entry_id']] ?? null);
        }

        return $this->buildSearchResponse($total, $resultRows);
    }

    public function getRow(int $entry_id): ResponseInterface
    {
        $builder = $this->buildEntriesQuery();
        $builder->where("{$this->entriesAlias}.entry_id", $entry_id);
        $row = $builder->get()->getRowArray();

        if (empty($row)) {
            return $this->response->setJSON([]);
        }

        return $this->response->setJSON($this->mapRow($row));
    }

    public function getView(int $entry_id = NEW_ENTRY): string
    {
        $entry = $this->cashflow_entry->find($entry_id);
        if (!$entry) {
            $entry = [
                'entry_id' => NEW_ENTRY,
                'entry_date' => date('Y-m-d H:i:s'),
                'entry_type' => 'income',
                'amount' => '0.00',
                'description' => '',
                'status' => 'draft',
                'account_id' => null,
                'category_id' => null,
                'from_account_id' => null,
                'to_account_id' => null,
                'customer_id' => null,
                'supplier_id' => null,
                'sale_id' => null,
                'sale_payment_id' => null,
                'receiving_id' => null
            ];
        }

        $sale_reference = '';
        if (!empty($entry['sale_id'])) {
            $sale = $this->sale->find((int) $entry['sale_id']);
            if (!empty($sale)) {
                $sale_reference = !empty($sale['invoice_number'])
                    ? (string) $sale['invoice_number']
                    : 'POS ' . $entry['sale_id'];
            } else {
                $sale_reference = 'POS ' . $entry['sale_id'];
            }
        }

        $receiving_reference = '';
        if (!empty($entry['receiving_id'])) {
            $receiving = $this->receiving->find((int) $entry['receiving_id']);
            if (!empty($receiving)) {
                $receiving_reference = !empty($receiving['reference'])
                    ? (string) $receiving['reference']
                    : 'RECV ' . $entry['receiving_id'];
            } else {
                $receiving_reference = 'RECV ' . $entry['receiving_id'];
            }
        }

        $customers = ['' => lang('Common.none_selected_text')];
        foreach ($this->customer->get_all()->getResultArray() as $row) {
            $customers[$row['person_id']] = trim($row['first_name'] . ' ' . $row['last_name']);
        }

        $suppliers = ['' => lang('Common.none_selected_text')];
        foreach ($this->supplier->get_all()->getResultArray() as $row) {
            $suppliers[$row['person_id']] = $row['company_name'];
        }

        $recentLimit = 200;
        $db = db_connect();

        $sale_reference_values = [];
        $salesSelect = $this->tableHasColumn('sales', 'invoice_number')
            ? 'sale_id, invoice_number'
            : 'sale_id';
        $salesRows = $db->table('sales')
            ->select($salesSelect)
            ->orderBy('sale_time', 'DESC')
            ->limit($recentLimit)
            ->get()
            ->getResultArray();
        foreach ($salesRows as $row) {
            $sale_reference_values[] = 'POS ' . $row['sale_id'];
            if (!empty($row['invoice_number'] ?? '')) {
                $sale_reference_values[] = (string) $row['invoice_number'];
            }
        }
        if ($sale_reference !== '' && !in_array($sale_reference, $sale_reference_values, true)) {
            $sale_reference_values[] = $sale_reference;
        }
        $sale_reference_values = array_values(array_unique($sale_reference_values));
        $sale_reference_options = ['' => lang('Common.none_selected_text')];
        foreach ($sale_reference_values as $value) {
            $sale_reference_options[$value] = $value;
        }

        $receiving_reference_values = [];
        $receivingSelect = $this->tableHasColumn('receivings', 'reference')
            ? 'receiving_id, reference'
            : 'receiving_id';
        $receivingRows = $db->table('receivings')
            ->select($receivingSelect)
            ->orderBy('receiving_time', 'DESC')
            ->limit($recentLimit)
            ->get()
            ->getResultArray();
        foreach ($receivingRows as $row) {
            $receiving_reference_values[] = 'RECV ' . $row['receiving_id'];
            if (!empty($row['reference'] ?? '')) {
                $receiving_reference_values[] = (string) $row['reference'];
            }
        }
        if ($receiving_reference !== '' && !in_array($receiving_reference, $receiving_reference_values, true)) {
            $receiving_reference_values[] = $receiving_reference;
        }
        $receiving_reference_values = array_values(array_unique($receiving_reference_values));
        $receiving_reference_options = ['' => lang('Common.none_selected_text')];
        foreach ($receiving_reference_values as $value) {
            $receiving_reference_options[$value] = $value;
        }

        $data = [
            'entry' => $entry,
            'accounts' => ['' => lang('Common.none_selected_text')] + $this->cashflow_account->getActiveOptions(),
            'entry_types' => $this->cashflow_category_type->getActiveOptions(),
            'categories_by_type' => $this->buildCategoryOptionsByType(lang('Common.none_selected_text')),
            'type_calc_methods' => $this->cashflow_category_type->getCalcMethodMap(),
            'customers' => $customers,
            'suppliers' => $suppliers,
            'attachments' => $entry_id > 0 ? $this->cashflow_attachment->where('entry_id', $entry_id)->findAll() : [],
            'sale_reference' => $sale_reference,
            'receiving_reference' => $receiving_reference,
            'sale_reference_options' => $sale_reference_options,
            'receiving_reference_options' => $receiving_reference_options
        ];

        return view('cashflow/form', $data);
    }

    public function getDetails(int $entry_id): string
    {
        $builder = $this->buildEntriesQuery();
        $builder->where("{$this->entriesAlias}.entry_id", $entry_id);
        $entry = $builder->get()->getRowArray();

        $attachments = $entry ? $this->cashflow_attachment->where('entry_id', $entry_id)->findAll() : [];

        return view('cashflow/details', [
            'entry' => $entry,
            'attachments' => $attachments
        ]);
    }

    public function postSave(int $entry_id = NEW_ENTRY): ResponseInterface
    {
        $input = $this->extractSaveInput();

        $validationResult = $this->validateSaveInput($input);
        if ($validationResult !== null) {
            return $validationResult;
        }

        $typeInfo = $this->getTypeInfo($input['entryType']);
        if ($typeInfo === null) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.invalid_type')]);
        }
        $calcMethod = (string) ($typeInfo['calc_method'] ?? '');

        $saleId = $this->parseSaleReference($input['saleReference']);
        if ($saleId === false) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.invalid_sale_reference')]);
        }

        $receivingId = $this->parseReceivingReference($input['receivingReference']);
        if ($receivingId === false) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.invalid_receiving_reference')]);
        }

        $accountData = $this->resolveAccountData($input, $calcMethod);
        if ($accountData instanceof ResponseInterface) {
            return $accountData;
        }

        $entryDate = $this->parseEntryDate($input['entryDate']);

        $entryData = $this->buildEntryData(
            $input,
            $accountData,
            $saleId,
            $receivingId,
            $entryDate
        );

        $saveResult = $this->saveEntry($entry_id, $entryData);
        if ($saveResult instanceof ResponseInterface) {
            return $saveResult;
        }

        $id = $saveResult;

        return $this->response->setJSON([
            'success' => true,
            'message' => $entry_id === NEW_ENTRY ? lang('Cashflow.save_success_new') : lang('Cashflow.save_success_update'),
            'id' => $id
        ]);
    }

    /**
     * Extract and return all input data from the request.
     */
    private function extractSaveInput(): array
    {
        return [
            'entryType' => (string) $this->request->getPost('entry_type'),
            'amount' => parse_decimals((string) $this->request->getPost('amount')),
            'accountId' => $this->nullableInt($this->request->getPost('account_id')),
            'categoryId' => $this->nullableInt($this->request->getPost('category_id')),
            'fromAccountId' => $this->nullableInt($this->request->getPost('from_account_id')),
            'toAccountId' => $this->nullableInt($this->request->getPost('to_account_id')),
            'customerId' => $this->nullableInt($this->request->getPost('customer_id')),
            'supplierId' => $this->nullableInt($this->request->getPost('supplier_id')),
            'saleReference' => trim((string) $this->request->getPost('sale_reference')),
            'receivingReference' => trim((string) $this->request->getPost('receiving_reference')),
            'status' => (string) $this->request->getPost('status'),
            'entryDate' => (string) $this->request->getPost('entry_date'),
            'description' => $this->request->getPost('description', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
        ];
    }

    /**
     * Validate basic input data. Returns null on success, or ResponseInterface on failure.
     */
    private function validateSaveInput(array $input): ?ResponseInterface
    {
        if ($input['amount'] === false || $input['amount'] <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.invalid_amount')]);
        }
        if (!in_array($input['status'], ['draft', 'posted'], true)) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.invalid_status')]);
        }
        if ($input['customerId'] !== null && $input['supplierId'] !== null) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.customer_supplier_xor')]);
        }
        if ($input['saleReference'] !== '' && $input['receivingReference'] !== '') {
            return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.reference_xor')]);
        }
        return null;
    }

    /**
     * Get type information for the given entry type.
     */
    private function getTypeInfo(string $entryType): ?array
    {
        $typeMap = $this->cashflow_category_type->getActiveTypeMap();
        return $typeMap[$entryType] ?? null;
    }

    /**
     * Parse sale reference and return sale ID. Returns false on failure.
     * @return int|false|null
     */
    private function parseSaleReference(string $saleReference)
    {
        if ($saleReference === '') {
            return null;
        }

        $receipt = $saleReference;
        $receiptParts = preg_split('/\s+/', $receipt);
        $isPosReceipt = count($receiptParts) === 2
            && preg_match('/^(POS)$/i', (string) $receiptParts[0])
            && ctype_digit((string) $receiptParts[1]);

        if (!$isPosReceipt && !$this->tableHasColumn('sales', 'invoice_number')) {
            return false;
        }

        if (!$this->sale->is_valid_receipt($receipt)) {
            return false;
        }

        $receiptParts = preg_split('/\s+/', $receipt);
        $saleId = (int) ($receiptParts[1] ?? 0);

        return $saleId > 0 ? $saleId : false;
    }

    /**
     * Parse receiving reference and return receiving ID. Returns false on failure.
     * @return int|false|null
     */
    private function parseReceivingReference(string $receivingReference)
    {
        if ($receivingReference === '') {
            return null;
        }

        $receivingParts = preg_split('/\s+/', $receivingReference);

        if (count($receivingParts) === 2
            && preg_match('/^(RECV|KIT)$/i', (string) $receivingParts[0])
            && ctype_digit((string) $receivingParts[1])) {
            $candidate = (int) $receivingParts[1];
            if ($this->receiving->exists($candidate)) {
                return $candidate;
            }
        } elseif ($this->tableHasColumn('receivings', 'reference')) {
            $receivingRow = $this->receiving->get_receiving_by_reference($receivingReference)->getRowArray();
            if (!empty($receivingRow)) {
                return (int) $receivingRow['receiving_id'];
            }
        }

        return false;
    }

    /**
     * Resolve account data based on entry type (transfer vs regular).
     * Returns array with resolved data, or ResponseInterface on validation failure.
     */
    private function resolveAccountData(array $input, string $calcMethod): array|ResponseInterface
    {
        $isTransfer = $calcMethod === 'transfer';

        if ($isTransfer) {
            return $this->resolveTransferAccountData($input);
        }

        return $this->resolveRegularAccountData($input);
    }

    /**
     * Resolve account data for transfer entries.
     */
    private function resolveTransferAccountData(array $input): array|ResponseInterface
    {
        if ($input['fromAccountId'] === null || $input['toAccountId'] === null) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.transfer_accounts_required')]);
        }
        if ($input['fromAccountId'] === $input['toAccountId']) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.transfer_accounts_different')]);
        }

        if ($input['categoryId'] !== null) {
            $category = $this->cashflow_category->find($input['categoryId']);
            if (!$category || ($category['entry_type'] ?? '') !== $input['entryType'] || (int) ($category['is_active'] ?? 0) !== 1) {
                return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.invalid_category')]);
            }
        }

        return [
            'accountId' => null,
            'fromAccountId' => $input['fromAccountId'],
            'toAccountId' => $input['toAccountId'],
            'categoryId' => $input['categoryId'],
            'saleId' => null,
            'receivingId' => null,
        ];
    }

    /**
     * Resolve account data for regular (non-transfer) entries.
     */
    private function resolveRegularAccountData(array $input): array|ResponseInterface
    {
        if ($input['accountId'] === null) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.account_required')]);
        }
        if ($input['categoryId'] === null) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.category_required')]);
        }

        $category = $this->cashflow_category->find($input['categoryId']);
        if (!$category || ($category['entry_type'] ?? '') !== $input['entryType'] || (int) ($category['is_active'] ?? 0) !== 1) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.invalid_category')]);
        }

        return [
            'accountId' => $input['accountId'],
            'fromAccountId' => null,
            'toAccountId' => null,
            'categoryId' => $input['categoryId'],
        ];
    }

    /**
     * Parse and format entry date.
     */
    private function parseEntryDate(string $entryDate): string
    {
        $entryDateTs = strtotime($entryDate);
        if ($entryDateTs === false) {
            return date('Y-m-d H:i:s');
        }
        return date('Y-m-d H:i:s', $entryDateTs);
    }

    /**
     * Build the entry data array for database insertion/update.
     */
    private function buildEntryData(array $input, array $accountData, ?int $saleId, ?int $receivingId, string $entryDate): array
    {
        return [
            'entry_date' => $entryDate,
            'entry_type' => $input['entryType'],
            'category_id' => $accountData['categoryId'],
            'amount' => $input['amount'],
            'description' => $input['description'],
            'status' => $input['status'],
            'account_id' => $accountData['accountId'],
            'from_account_id' => $accountData['fromAccountId'],
            'to_account_id' => $accountData['toAccountId'],
            'customer_id' => $input['customerId'],
            'supplier_id' => $input['supplierId'],
            'sale_id' => $saleId ?? ($accountData['saleId'] ?? null),
            'receiving_id' => $receivingId ?? ($accountData['receivingId'] ?? null),
            'deleted' => 0
        ];
    }

    /**
     * Save entry to database. Returns entry ID on success, or ResponseInterface on failure.
     */
    private function saveEntry(int $entry_id, array $entryData): int|ResponseInterface
    {
        if ($entry_id === NEW_ENTRY) {
            $entryData['created_by'] = (int) $this->employee->get_logged_in_employee_info()->person_id;
        } else {
            $existing = $this->cashflow_entry->find($entry_id);
            if (!$existing) {
                return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.not_found')]);
            }
        }

        $success = $entry_id === NEW_ENTRY
            ? $this->cashflow_entry->insert($entryData)
            : $this->cashflow_entry->update($entry_id, $entryData);

        if (!$success) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.save_failed')]);
        }

        $id = $entry_id === NEW_ENTRY ? (int) $this->cashflow_entry->getInsertID() : $entry_id;
        $this->handleAttachments($id);

        return $id;
    }

    public function postDelete(): ResponseInterface
    {
    	if (!$this->employee->has_grant('cashflow_delete', $this->employee->get_logged_in_employee_info()->person_id)) {
    		return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.permission_denied')]);
    	}

    	$ids = $this->request->getPost('ids');
    	if (!is_array($ids)) {
    		$ids = [];
    	}

    	if (empty($ids)) {
    		return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.nothing_selected')]);
    	}

    	$this->cashflow_entry->whereIn('entry_id', $ids)->set(['deleted' => 1])->update();

    	return $this->response->setJSON([
    		'success' => true,
    		'message' => lang('Cashflow.delete_success')
    	]);
    }

    public function postRestore(): ResponseInterface
    {
        if (!$this->employee->has_grant('cashflow_delete', $this->employee->get_logged_in_employee_info()->person_id)) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.permission_denied')]);
        }

        $ids = $this->request->getPost('ids');
        if (!is_array($ids)) {
            $ids = [];
        }

        if (empty($ids)) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.nothing_selected')]);
        }

        $this->cashflow_entry->whereIn('entry_id', $ids)->set(['deleted' => 0])->update();

        return $this->response->setJSON([
            'success' => true,
            'message' => lang('Common.restore')
        ]);
    }

    public function postPost(): ResponseInterface
    {
        if (!$this->employee->has_grant('cashflow_post', $this->employee->get_logged_in_employee_info()->person_id)) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.permission_denied')]);
        }

        $ids = $this->request->getPost('ids');
        if (!is_array($ids)) {
            $ids = [];
        }

        if (empty($ids)) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.nothing_selected')]);
        }

        $this->cashflow_entry
            ->whereIn('entry_id', $ids)
            ->where('status', 'draft')
            ->set(['status' => 'posted'])
            ->update();

        return $this->response->setJSON([
            'success' => true,
            'message' => lang('Cashflow.post_success')
        ]);
    }

    public function getDownloadAttachment(int $attachment_id)
    {
        $attachment = $this->cashflow_attachment->find($attachment_id);
        if (!$attachment) {
            return redirect()->to(site_url('cashflow'));
        }

        $fullPath = WRITEPATH . $attachment['file_path'];
        if (!is_file($fullPath)) {
            return redirect()->to(site_url('cashflow'));
        }

        return $this->response->download($fullPath, null)->setFileName($attachment['file_name']);
    }
    public function postDeleteAttachment(int $attachment_id): ResponseInterface
    {
        $attachment = $this->cashflow_attachment->find($attachment_id);
        if (!$attachment) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.attachment_not_found')]);
        }

        $fullPath = WRITEPATH . $attachment['file_path'];
        if (is_file($fullPath)) {
            @unlink($fullPath);
        }

        $this->cashflow_attachment->delete($attachment_id);

        return $this->response->setJSON(['success' => true, 'message' => lang('Cashflow.attachment_deleted')]);
    }

    private function buildEntriesQuery(bool $show_deleted = false)
    {
        $db = db_connect();
        $builder = $db->table('cashflow_entries AS ' . $this->entriesAlias);
        $builder->select("{$this->entriesAlias}.*", false);
        $builder->select('category_main.name AS category_name', false);
        $builder->select('category_main.entry_type AS category_type', false);
        $builder->select('entry_types.type_label AS entry_type_label', false);
        $builder->select('entry_types.calc_method AS entry_calc_method', false);
        $builder->select('account_main.name AS account_name', false);
        $builder->select('from_accounts.name AS from_account_name', false);
        $builder->select('to_accounts.name AS to_account_name', false);
        $builder->select('CONCAT(cpeople.first_name, " ", cpeople.last_name) AS customer_name', false);
        $builder->select('supplier_main.company_name AS supplier_name', false);
        $builder->join('cashflow_accounts AS account_main', "account_main.account_id = {$this->entriesAlias}.account_id", 'left');
        $builder->join('cashflow_categories AS category_main', "category_main.category_id = {$this->entriesAlias}.category_id", 'left');
        $builder->join('cashflow_accounts AS from_accounts', "from_accounts.account_id = {$this->entriesAlias}.from_account_id", 'left');
        $builder->join('cashflow_accounts AS to_accounts', "to_accounts.account_id = {$this->entriesAlias}.to_account_id", 'left');
        $builder->join('cashflow_category_types AS entry_types', "entry_types.type_code = {$this->entriesAlias}.entry_type", 'left');
        $builder->join('customers', "customers.person_id = {$this->entriesAlias}.customer_id", 'left');
        $builder->join('people AS cpeople', 'cpeople.person_id = customers.person_id', 'left');
        $builder->join('suppliers AS supplier_main', "supplier_main.person_id = {$this->entriesAlias}.supplier_id", 'left');
        $builder->join('sales AS sales_main', "sales_main.sale_id = {$this->entriesAlias}.sale_id", 'left');
        $builder->join('receivings AS receivings_main', "receivings_main.receiving_id = {$this->entriesAlias}.receiving_id", 'left');
        $builder->where("{$this->entriesAlias}.deleted", $show_deleted ? 1 : 0);

        return $builder;
    }

    private function applyFilters($builder, string $search, string $type, string $status, int $accountId, int $categoryId, string $startDate, string $endDate): void
    {
        if ($type !== '') {
            $builder->where("{$this->entriesAlias}.entry_type", $type);
        }
        if ($status !== '') {
            $builder->where("{$this->entriesAlias}.status", $status);
        }
        if ($accountId > 0) {
            $builder->groupStart();
            $builder->where("{$this->entriesAlias}.account_id", $accountId);
            $builder->orWhere("{$this->entriesAlias}.from_account_id", $accountId);
            $builder->orWhere("{$this->entriesAlias}.to_account_id", $accountId);
            $builder->groupEnd();
        }
        if ($categoryId > 0) {
            $builder->where("{$this->entriesAlias}.category_id", $categoryId);
        }
        if ($startDate !== '' && $endDate !== '') {
            $builder->where("DATE({$this->entriesAlias}.entry_date) >=", $startDate);
            $builder->where("DATE({$this->entriesAlias}.entry_date) <=", $endDate);
        }
        if ($search !== '') {
            $db = db_connect();
            $escapedSearch = $db->escape('%' . $search . '%');
            $builder->groupStart();
            $builder->like("{$this->entriesAlias}.description", $search);
            $builder->orWhere("`account_main`.`name` LIKE " . $escapedSearch, null, false);
            $builder->orWhere("`from_accounts`.`name` LIKE " . $escapedSearch, null, false);
            $builder->orWhere("`to_accounts`.`name` LIKE " . $escapedSearch, null, false);
            $builder->orWhere("`category_main`.`name` LIKE " . $escapedSearch, null, false);
            $builder->orWhere("`supplier_main`.`company_name` LIKE " . $escapedSearch, null, false);
            $builder->orWhere("CONCAT(cpeople.first_name, ' ', cpeople.last_name) LIKE " . $escapedSearch, null, false);

            $saleIdMatch = null;
            $receivingIdMatch = null;
            if (preg_match('/^(POS)\\s*(\\d+)/i', $search, $match)) {
                $saleIdMatch = (int) $match[2];
            }
            if (preg_match('/^(RECV|KIT)\\s*(\\d+)/i', $search, $match)) {
                $receivingIdMatch = (int) $match[2];
            }
            if ($saleIdMatch !== null) {
                $builder->orWhere("{$this->entriesAlias}.sale_id", $saleIdMatch);
            }
            if ($receivingIdMatch !== null) {
                $builder->orWhere("{$this->entriesAlias}.receiving_id", $receivingIdMatch);
            }
            if (ctype_digit($search)) {
                $id = (int) $search;
                $builder->orWhere("{$this->entriesAlias}.sale_id", $id);
                $builder->orWhere("{$this->entriesAlias}.receiving_id", $id);
            }
            if ($this->tableHasColumn('sales', 'invoice_number')) {
                $builder->orWhere("`sales_main`.`invoice_number` LIKE " . $escapedSearch, null, false);
            }
            if ($this->tableHasColumn('receivings', 'reference')) {
                $builder->orWhere("`receivings_main`.`reference` LIKE " . $escapedSearch, null, false);
            }

            // Search by amount if the search term is numeric
            if (is_numeric($search)) {
                $amount = (float) $search;
                $builder->orWhere("{$this->entriesAlias}.amount", $amount);
            }

            $builder->groupEnd();
        }
    }

    private function mapRow(array $row, float|string|null $amountAfter = null): array
    {
        $partyName = $row['customer_name'] ?: ($row['supplier_name'] ?: '-');
        $accountDisplay = '-';
        $calcMethod = (string) ($row['entry_calc_method'] ?? '');
        if ($calcMethod === '') {
            $calcMethod = $this->getCalcMethodForType((string) ($row['entry_type'] ?? ''));
        }

        if ($calcMethod === 'transfer') {
            $accountDisplay = ($row['from_account_name'] ?? '-') . ' -> ' . ($row['to_account_name'] ?? '-');
        } else {
            $accountDisplay = $row['account_name'] ?? '-';
        }
        $entryTypeLabel = $row['entry_type_label'] ?? '';
        if ($entryTypeLabel === '' && !empty($row['entry_type'])) {
            $entryTypeLabel = lang('Cashflow.' . $row['entry_type']);
        }
        $categoryType = $entryTypeLabel !== '' ? $entryTypeLabel : '-';

        $categoryName = $row['category_name'] ?? '-';
        if ($calcMethod === 'transfer' && ($row['category_name'] ?? '') === '') {
            $categoryName = '-';
        }
        $relatedInvoice = '-';
        if (!empty($row['sale_id'])) {
            $saleId = (int) $row['sale_id'];
            $links = [];
            $links[] = anchor(
                "sales/receipt/{$saleId}",
                esc('POS ' . $saleId),
                ['title' => lang('Sales.receipt'), 'target' => '_blank']
            );
            $relatedInvoice = implode('<br>', $links);
        }

        $vendorInvoice = '-';
        if (!empty($row['receiving_id'])) {
            $receivingId = (int) $row['receiving_id'];
            $vendorInvoice = anchor(
                "receivings/receipt/{$receivingId}",
                esc('RECV ' . $receivingId),
                ['title' => lang('Receivings.receipt'), 'target' => '_blank']
            );
        }

        $amountAfterDisplay = '-';
        if ($amountAfter !== null) {
            $amountAfterDisplay = is_string($amountAfter) ? $amountAfter : to_currency($amountAfter);
        }

        return [
            'entry_id' => $row['entry_id'],
            'entry_date' => to_datetime(strtotime($row['entry_date'])),
            'entry_type' => $entryTypeLabel !== '' ? $entryTypeLabel : lang('Cashflow.' . $row['entry_type']),
            'category_type' => $categoryType,
            'category_name' => $categoryName,
            'amount' => to_currency($row['amount']),
            'amount_after' => $amountAfterDisplay,
            'account_display' => $accountDisplay,
            'party_name' => $partyName,
            'status' => lang('Cashflow.' . $row['status']),
            'description' => $row['description'],
            'related_invoice' => $relatedInvoice,
            'vendor_invoice' => $vendorInvoice,
            'details' => anchor(
                "cashflow/details/{$row['entry_id']}",
                '<span class="glyphicon glyphicon-list-alt"></span> ' . lang('Common.det'),
                [
                    'class' => 'btn btn-xs btn-default modal-dlg',
                    'title' => lang('Common.det')
                ]
            ),
            'edit' => anchor(
                "cashflow/view/{$row['entry_id']}",
                '<span class="glyphicon glyphicon-edit"></span>',
                [
                    'class' => 'modal-dlg',
                    'data-btn-submit' => lang('Common.submit'),
                    'title' => lang('Cashflow.edit_entry')
                ]
            )
        ];
    }

    private function buildAmountAfterMap($filteredBuilder, int $accountId): array
    {
        $balanceRows = $filteredBuilder
            ->orderBy("{$this->entriesAlias}.entry_date", 'ASC')
            ->orderBy("{$this->entriesAlias}.entry_id", 'ASC')
            ->get()
            ->getResultArray();

        $runningAmount = $this->getOpeningBalanceForContext($accountId);
        $amountAfter = [];

        if ($accountId > 0) {
            foreach ($balanceRows as $row) {
                if (($row['status'] ?? '') === 'posted') {
                    $runningAmount += $this->getEntryEffectOnContext($row, $accountId);
                }

                $amountAfter[(int) $row['entry_id']] = $runningAmount;
            }

            return $amountAfter;
        }

        $accountBalances = $this->getOpeningBalancesPerAccount();
        foreach ($balanceRows as $row) {
            if (($row['status'] ?? '') === 'posted') {
                $runningAmount += $this->getEntryEffectOnContext($row, $accountId);

                $entryType = (string) ($row['entry_type'] ?? '');
                $calcMethod = $this->getCalcMethodForType($entryType);
                $amount = (float) ($row['amount'] ?? 0);
                $accountIdRow = (int) ($row['account_id'] ?? 0);
                $fromAccountId = (int) ($row['from_account_id'] ?? 0);
                $toAccountId = (int) ($row['to_account_id'] ?? 0);

                if ($calcMethod === 'add' && $accountIdRow > 0) {
                    $accountBalances[$accountIdRow] = ($accountBalances[$accountIdRow] ?? 0) + $amount;
                } elseif ($calcMethod === 'subtract' && $accountIdRow > 0) {
                    $accountBalances[$accountIdRow] = ($accountBalances[$accountIdRow] ?? 0) - $amount;
                } elseif ($calcMethod === 'transfer') {
                    if ($fromAccountId > 0) {
                        $accountBalances[$fromAccountId] = ($accountBalances[$fromAccountId] ?? 0) - $amount;
                    }
                    if ($toAccountId > 0) {
                        $accountBalances[$toAccountId] = ($accountBalances[$toAccountId] ?? 0) + $amount;
                    }
                }
            }

            if ($this->getCalcMethodForType((string) ($row['entry_type'] ?? '')) === 'transfer') {
                $fromAccountId = (int) ($row['from_account_id'] ?? 0);
                $toAccountId = (int) ($row['to_account_id'] ?? 0);
                if ($fromAccountId > 0 && $toAccountId > 0) {
                    $fromBalance = $accountBalances[$fromAccountId] ?? null;
                    $toBalance = $accountBalances[$toAccountId] ?? null;
                    if ($fromBalance !== null && $toBalance !== null) {
                        $amountAfter[(int) $row['entry_id']] = to_currency($fromBalance) . ' -> ' . to_currency($toBalance);
                        continue;
                    }
                }
            } else {
                $entryAccountId = (int) ($row['account_id'] ?? 0);
                if ($entryAccountId > 0 && isset($accountBalances[$entryAccountId])) {
                    $amountAfter[(int) $row['entry_id']] = to_currency($accountBalances[$entryAccountId]);
                    continue;
                }
            }

            $amountAfter[(int) $row['entry_id']] = $runningAmount;
        }

        return $amountAfter;
    }

    private function getOpeningBalanceForContext(int $accountId): float
    {
        $db = db_connect();

        if ($accountId > 0) {
            $account = $db->table('cashflow_accounts')
                ->select('opening_balance')
                ->where('account_id', $accountId)
                ->get()
                ->getRowArray();

            return (float) ($account['opening_balance'] ?? 0);
        }

        $row = $db->table('cashflow_accounts')
            ->select('COALESCE(SUM(opening_balance), 0) AS opening_balance', false)
            ->get()
            ->getRowArray();

        return (float) ($row['opening_balance'] ?? 0);
    }

    private function getOpeningBalancesPerAccount(): array
    {
        $db = db_connect();
        $rows = $db->table('cashflow_accounts')
            ->select('account_id, opening_balance')
            ->get()
            ->getResultArray();

        $balances = [];
        foreach ($rows as $row) {
            $balances[(int) $row['account_id']] = (float) ($row['opening_balance'] ?? 0);
        }

        return $balances;
    }

    private function getCalcMethodForType(string $entryType): string
    {
        if ($this->calcMethodMap === null) {
            $this->calcMethodMap = $this->cashflow_category_type->getCalcMethodMap();
        }

        return (string) ($this->calcMethodMap[$entryType] ?? '');
    }

    private function buildCategoryOptionsByType(string $defaultLabel): array
    {
        $typeMap = $this->cashflow_category_type->getActiveTypeMap();
        $options = [];
        foreach ($typeMap as $typeCode => $typeInfo) {
            $options[$typeCode] = ['' => $defaultLabel] + $this->cashflow_category->getActiveOptions($typeCode);
        }

        return $options;
    }

    private function getEntryEffectOnContext(array $row, int $accountId): float
    {
        $amount = (float) ($row['amount'] ?? 0);
        $entryType = (string) ($row['entry_type'] ?? '');
        $calcMethod = $this->getCalcMethodForType($entryType);

        if ($accountId <= 0) {
            return match ($calcMethod) {
                'add' => $amount,
                'subtract' => -1 * $amount,
                default => 0.0
            };
        }

        if ($calcMethod === 'transfer') {
            if ((int) ($row['from_account_id'] ?? 0) === $accountId) {
                return -1 * $amount;
            }
            if ((int) ($row['to_account_id'] ?? 0) === $accountId) {
                return $amount;
            }

            return 0.0;
        }

        if ($calcMethod === 'add' && (int) ($row['account_id'] ?? 0) === $accountId) {
            return $amount;
        }

        if ($calcMethod === 'subtract' && (int) ($row['account_id'] ?? 0) === $accountId) {
            return -1 * $amount;
        }

        return 0.0;
    }

    private function nullableInt($value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (int) $value;
    }

    private function handleAttachments(int $entryId): void
    {
        $files = $this->request->getFiles();
        if (!isset($files['attachments'])) {
            return;
        }

        $attachmentFiles = $files['attachments'];
        if (!is_array($attachmentFiles)) {
            $attachmentFiles = [$attachmentFiles];
        }

        $targetDir = WRITEPATH . 'uploads/cashflow/' . $entryId . '/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0775, true);
        }

        foreach ($attachmentFiles as $file) {
            if (!$file || !$file->isValid() || $file->getError() !== UPLOAD_ERR_OK) {
                continue;
            }

            if ($file->getSize() > 5 * 1024 * 1024) {
                continue;
            }

            $safeName = $file->getRandomName();
            $file->move($targetDir, $safeName, true);

            $this->cashflow_attachment->insert([
                'entry_id' => $entryId,
                'file_name' => $file->getClientName(),
                'file_path' => 'uploads/cashflow/' . $entryId . '/' . $safeName,
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize()
            ]);
        }
    }

    private function tableHasColumn(string $table, string $column): bool
    {
        $db = db_connect();
        $tableName = $db->prefixTable($table);
        $row = $db->query(
            "SELECT COUNT(1) AS count
                FROM information_schema.columns
                WHERE table_schema = DATABASE()
                  AND table_name = ?
                  AND column_name = ?",
            [$tableName, $column]
        )->getRowArray();

        return (int) ($row['count'] ?? 0) > 0;
    }
}









