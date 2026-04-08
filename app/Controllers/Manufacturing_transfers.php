<?php

namespace App\Controllers;

use App\Models\Item;
use App\Models\Item_quantity;
use App\Models\Manufacturing_project;
use App\Models\Manufacturing_stock_transfer;
use App\Models\Stock_location;
use App\Traits\SearchableTrait;
use App\Traits\ValidatesInputTrait;
use CodeIgniter\HTTP\ResponseInterface;

require_once('Secure_Controller.php');

/**
 * Manufacturing Stock Transfers Controller
 */
class Manufacturing_transfers extends Secure_Controller
{
    use SearchableTrait;
    use ValidatesInputTrait;

    private Manufacturing_stock_transfer $transfer;
    private Manufacturing_project $project;
    private Stock_location $location;
    private Item $item;
    private Item_quantity $item_quantity;

    public function __construct()
    {
        parent::__construct('manufacturing');
        $this->transfer = model(Manufacturing_stock_transfer::class);
        $this->project = model(Manufacturing_project::class);
        $this->location = model(Stock_location::class);
        $this->item = model(Item::class);
        $this->item_quantity = model(Item_quantity::class);
    }

    /**
     * Transfer listing page
     */
    public function getIndex(): string
    {
        $data['table_headers'] = json_encode([
            ['field' => 'transfer_id', 'title' => lang('Manufacturing.transfer_code'), 'sortable' => true],
            ['field' => 'project_name', 'title' => lang('Manufacturing.project'), 'sortable' => false],
            ['field' => 'transfer_type', 'title' => lang('Manufacturing.transfer_type'), 'sortable' => true],
            ['field' => 'location_name', 'title' => lang('Manufacturing.source_location'), 'sortable' => false],
            ['field' => 'transfer_date', 'title' => lang('Manufacturing.transfer_date'), 'sortable' => true],
            ['field' => 'status', 'title' => lang('Manufacturing.transfer_status'), 'sortable' => true],
            ['field' => 'edit', 'title' => '', 'sortable' => false, 'escape' => false]
        ]);
        $data['controller_name'] = 'manufacturing_transfers';

        return view('manufacturing/transfers/manage', $data);
    }

    /**
     * Search transfers via AJAX
     */
    public function getSearch(): ResponseInterface
    {
        $params = $this->getSearchParams();
        $sort = $this->sanitizeSortColumn(['transfer_id', 'transfer_type', 'transfer_date', 'status'], $params['sort'], 'transfer_id');
        $order = $this->validateSortOrder($params['order']);

        $transfers = $this->transfer->search($params['search'], $params['limit'], $params['offset'], $sort, $order);
        $total_rows = $this->transfer->get_found_rows($params['search']);

        $data_rows = [];
        foreach ($transfers as $transfer) {
            $data_rows[] = $this->mapTransferRow($transfer);
        }

        return $this->buildSearchResponse($total_rows, $data_rows);
    }

    /**
     * Create new transfer form
     */
    public function getCreate(int $project_id = NEW_ENTRY): string
    {
        $transfer_code = $this->transfer->generate_transfer_code();
        $project = $this->project->get_info($project_id);

        $locations = ['' => lang('Manufacturing.select_location')];
        foreach ($this->location->get_all()->getResultArray() as $row) {
            $locations[$row['location_id']] = $row['location_name'];
        }

        $data = [
            'transfer' => [
                'transfer_id' => NEW_ENTRY,
                'transfer_code' => $transfer_code,
                'project_id' => $project_id,
                'project_name' => $project['project_name'] ?? '',
                'project_code' => $project['project_code'] ?? '',
                'source_location_id' => null,
                'transfer_type' => 'issue',
                'transfer_date' => date('Y-m-d H:i:s'),
                'reference' => '',
                'notes' => '',
                'status' => 'draft'
            ],
            'locations' => $locations,
            'items' => [],
            'controller_name' => 'manufacturing_transfers'
        ];

        return view('manufacturing/transfers/form', $data);
    }

    /**
     * View existing transfer
     */
    public function getView(int $transfer_id = NEW_ENTRY): string
    {
        $transfer = $this->transfer->get_info($transfer_id);

        if (empty($transfer)) {
            return view('errors/html/error_404');
        }

        $locations = ['' => lang('Manufacturing.select_location')];
        foreach ($this->location->get_all()->getResultArray() as $row) {
            $locations[$row['location_id']] = $row['location_name'];
        }

        $items = $this->transfer->get_items($transfer_id);

        $data = [
            'transfer' => $transfer,
            'locations' => $locations,
            'items' => $items,
            'controller_name' => 'manufacturing_transfers'
        ];

        return view('manufacturing/transfers/form', $data);
    }

    /**
     * Save transfer (draft)
     */
    public function postSave(int $transfer_id = NEW_ENTRY): ResponseInterface
    {
        $transfer_id = (int) $this->request->getPost('transfer_id');

        $data = [
            'project_id' => (int) $this->request->getPost('project_id'),
            'source_location_id' => (int) $this->request->getPost('source_location_id'),
            'transfer_type' => $this->request->getPost('transfer_type') ?? 'issue',
            'transfer_date' => $this->request->getPost('transfer_date') ?: date('Y-m-d H:i:s'),
            'reference' => $this->request->getPost('reference'),
            'notes' => $this->request->getPost('notes'),
            'created_by' => (int) $this->employee->get_logged_in_employee_info()->person_id
        ];

        if ($transfer_id === NEW_ENTRY) {
            $data['transfer_code'] = $this->transfer->generate_transfer_code();
            $this->transfer->insert($data);
            $transfer_id = (int) $this->transfer->getInsertID();
        } else {
            $this->transfer->update($transfer_id, $data);
        }

        return $this->response->setJSON([
            'success' => true,
            'id' => $transfer_id,
            'transfer_code' => $data['transfer_code'] ?? $this->transfer->find($transfer_id)['transfer_code']
        ]);
    }

    /**
     * Add item to transfer
     */
    public function postAddItem(): ResponseInterface
    {
        $transfer_id = (int) $this->request->getPost('transfer_id');
        $item_id = (int) $this->request->getPost('item_id');
        $quantity = (float) $this->request->getPost('quantity');

        $item_info = $this->item->get_info($item_id);
        if (!$item_info) {
            return $this->response->setJSON(['success' => false, 'message' => 'Item not found']);
        }

        $location_id = (int) $this->request->getPost('location_id');
        $unit_cost = (float) $item_info->cost_price;

        $item_id_db = $this->transfer->add_item($transfer_id, $item_id, $quantity, $unit_cost);

        return $this->response->setJSON([
            'success' => true,
            'item_id' => $item_id_db,
            'item_name' => $item_info->name,
            'item_number' => $item_info->item_number,
            'quantity' => $quantity,
            'unit_cost' => $unit_cost,
            'total_cost' => $quantity * $unit_cost
        ]);
    }

    /**
     * Delete item from transfer
     */
    public function getDeleteItem(int $item_id): ResponseInterface
    {
        if ($this->transfer->remove_item($item_id)) {
            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setJSON(['success' => false]);
    }

    /**
     * Confirm transfer
     */
    public function postConfirm(int $transfer_id): ResponseInterface
    {
        $confirmed_by = (int) $this->employee->get_logged_in_employee_info()->person_id;

        if ($this->transfer->confirm($transfer_id, $confirmed_by)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => lang('Manufacturing.successful_confirming')
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => lang('Manufacturing.error_confirming')
        ]);
    }

    /**
     * Cancel transfer
     */
    public function postCancel(int $transfer_id): ResponseInterface
    {
        if ($this->transfer->cancel($transfer_id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => lang('Manufacturing.successful_cancelling')
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => lang('Manufacturing.error_cancelling')
        ]);
    }

    /**
     * Get transfer receipt
     */
    public function getReceipt(int $transfer_id): string
    {
        $transfer = $this->transfer->get_info($transfer_id);
        $items = $this->transfer->get_items($transfer_id);

        $data = [
            'transfer' => $transfer,
            'items' => $items,
            'total_cost' => array_sum(array_column($items, 'total_cost'))
        ];

        return view('manufacturing/transfers/receipt', $data);
    }

    /**
     * Search items for dropdown
     */
    public function getItemSearch(): ResponseInterface
    {
        $search = $this->request->getGet('term');
        $suggestions = $this->item->get_search_suggestions($search, ['search_custom' => false, 'is_deleted' => false], true);

        return $this->response->setJSON($suggestions);
    }

    /**
     * Map transfer row for table
     */
    private function mapTransferRow(array $transfer): array
    {
        return [
            'transfer_id' => $transfer['transfer_id'],
            'transfer_code' => $transfer['transfer_code'],
            'project_name' => ($transfer['project_code'] ?? '') . ' - ' . ($transfer['project_name'] ?? ''),
            'transfer_type' => lang('Manufacturing.transfer_' . $transfer['transfer_type']),
            'location_name' => $transfer['location_name'] ?? '',
            'transfer_date' => $transfer['transfer_date'],
            'status' => lang('Manufacturing.status_' . $transfer['status']),
            'edit' => $transfer['status'] === 'draft' ? anchor(
                "manufacturing/transfers/view/{$transfer['transfer_id']}",
                '<span class="glyphicon glyphicon-edit"></span>',
                [
                    'class' => 'modal-dlg',
                    'data-btn-submit' => lang('Common.submit'),
                    'title' => lang('Manufacturing.edit_transfer')
                ]
            ) : anchor(
                "manufacturing/transfers/receipt/{$transfer['transfer_id']}",
                '<span class="glyphicon glyphicon-print"></span>',
                ['title' => lang('Common.print')]
            )
        ];
    }
}
