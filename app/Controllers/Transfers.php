<?php

namespace App\Controllers;

use App\Libraries\Transfer_lib;
use App\Models\Employee;
use App\Models\Item;
use App\Models\Stock_location;
use App\Models\Stock_transfer;
use CodeIgniter\HTTP\ResponseInterface;
use ReflectionException;

/**
 * Transfers controller
 *
 * Handles stock transfers between locations
 */
class Transfers extends Secure_Controller
{
    private Transfer_lib $transfer_lib;
    private Stock_transfer $stock_transfer;
    private Stock_location $stock_location;
    private Item $item;

    public function __construct()
    {
        parent::__construct('transfers');

        $this->transfer_lib = new Transfer_lib();
        $this->stock_transfer = model(Stock_transfer::class);
        $this->stock_location = model(Stock_location::class);
        $this->item = model(Item::class);
        $this->employee = model(Employee::class);
    }

    /**
     * Display transfers page
     *
     * @return string
     */
    public function getIndex(): string
    {
        return $this->_view_transfers();
    }

    /**
     * Display transfer create page
     *
     * @return string
     */
    public function getTransfer(): string
    {
        $data = $this->buildTransferFormData();

        return view('transfers/create', $data);
    }

    /**
     * Search for items in transfers
     *
     * @return ResponseInterface
     */
    public function getItemSearch(): ResponseInterface
    {
        $search = $this->request->getGet('term');
        $suggestions = $this->item->get_stock_search_suggestions($search, ['search_custom' => false, 'is_deleted' => false], true);

        return $this->response->setJSON($suggestions);
    }

    /**
     * Change transfer mode (source/destination locations)
     *
     * @return ResponseInterface
     */
    public function postChangeLocations(): ResponseInterface
    {
        $all_locations = $this->getAllLocations();
        $redirect_url = site_url('transfers/transfer');

        if (empty($all_locations)) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'No available locations were found.',
                'redirect_url' => $redirect_url
            ]);
        }

        $source_location = (int) $this->request->getPost('source_location', FILTER_SANITIZE_NUMBER_INT);
        $destination_location = (int) $this->request->getPost('destination_location', FILTER_SANITIZE_NUMBER_INT);

        if ($source_location === 0 || !isset($all_locations[$source_location])) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Please select a valid source location.',
                'redirect_url' => $redirect_url
            ]);
        }

        $available_destinations = $all_locations;
        unset($available_destinations[$source_location]);

        if (empty($available_destinations)) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Please configure at least two locations for transfers.',
                'redirect_url' => $redirect_url
            ]);
        }

        if ($destination_location === 0 || !isset($available_destinations[$destination_location])) {
            $destination_location = (int) array_key_first($available_destinations);
        }

        $this->transfer_lib->set_source_location($source_location);
        $this->transfer_lib->set_destination_location($destination_location);

        return $this->response->setJSON([
            'success' => true,
            'source_location' => $source_location,
            'destination_location' => $destination_location,
            'redirect_url' => $redirect_url
        ]);
    }

    /**
     * Add item to transfer
     *
     * @return string
     */
    public function postAddItem(): ResponseInterface
    {
        $data = ['success' => false];
        $item_id = $this->request->getPost('item', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $quantity = (int) $this->request->getPost('quantity', FILTER_SANITIZE_NUMBER_INT);

        if ($quantity <= 0) {
            $data['message'] = lang('Transfers.invalid_quantity');
        } elseif (!$this->transfer_lib->add_item($item_id, $quantity)) {
            $data['message'] = lang('Transfers.unable_to_add_item');
        } else {
            $data['success'] = true;
            $data['message'] = 'Item added to transfer.';
        }

        return $this->response->setJSON($data);
    }

    /**
     * Delete item from transfer
     *
     * @param int $line
     * @return string
     */
    public function getDeleteItem(int $line): string
    {
        $this->transfer_lib->delete_item($line);

        return $this->_reload();
    }

    /**
     * Edit item in transfer
     *
     * @param int $line
     * @return string
     */
    public function postEditItem(int $line): ResponseInterface
    {
        $data = ['success' => false];

        $quantity = (int) $this->request->getPost('quantity', FILTER_SANITIZE_NUMBER_INT);
        $description = $this->request->getPost('description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $serialnumber = $this->request->getPost('serialnumber', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';

        if ($quantity <= 0) {
            $data['message'] = lang('Transfers.invalid_quantity');
        } else {
            $this->transfer_lib->edit_item($line, $quantity, $description, $serialnumber);
            $data['success'] = true;
            $data['message'] = 'Item updated.';
        }

        return $this->response->setJSON($data);
    }

    /**
     * Set transfer reference
     *
     * @return ResponseInterface
     */
    public function postSetReference(): ResponseInterface
    {
        $reference = $this->request->getPost('transfer_reference', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $this->transfer_lib->set_reference($reference);

        return $this->response->setJSON(['success' => true]);
    }

    /**
     * Set transfer comment
     *
     * @return ResponseInterface
     */
    public function postSetComment(): ResponseInterface
    {
        $comment = $this->request->getPost('comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $this->transfer_lib->set_comment($comment);

        return $this->response->setJSON(['success' => true]);
    }

    /**
     * Complete transfer
     *
     * @return string
     * @throws ReflectionException
     */
    public function postComplete(): string
    {
        $data = [];
        $db = db_connect();

        if (!$db->tableExists('stock_transfers') || !$db->tableExists('stock_transfers_items')) {
            $data['error'] = 'Transfer tables are missing. Please run the stock transfer setup SQL/migration.';
            return $this->_reload($data);
        }

        $all_locations = $this->getAllLocations();
        $source_location = (int) $this->request->getPost('source_location', FILTER_SANITIZE_NUMBER_INT);
        $destination_location = (int) $this->request->getPost('destination_location', FILTER_SANITIZE_NUMBER_INT);

        if ($source_location === 0) {
            $source_location = $this->transfer_lib->get_source_location();
        }
        if ($destination_location === 0) {
            $destination_location = $this->transfer_lib->get_destination_location();
        }

        if (!isset($all_locations[$source_location]) || !isset($all_locations[$destination_location])) {
            $data['error'] = 'Please select valid transfer locations.';
            return $this->_reload($data);
        }

        $this->transfer_lib->set_source_location($source_location);
        $this->transfer_lib->set_destination_location($destination_location);

        // Validate locations are different
        if ($source_location == $destination_location) {
            $data['error'] = lang('Transfers.error_same_location');

            return $this->_reload($data);
        }

        // Validate cart has items
        $cart = $this->transfer_lib->get_cart();
        if (count($cart) == 0) {
            $data['error'] = lang('Transfers.error_empty_cart');

            return $this->_reload($data);
        }

        // Get employee info
        $employee_id = $this->employee->get_logged_in_employee_info()->person_id;
        $employee_info = $this->employee->get_info($employee_id);

        // Prepare transfer data
        $data['cart'] = $cart;
        $data['total_items'] = $this->transfer_lib->get_total_items();
        $data['source_location_id'] = $source_location;
        $data['source_location_name'] = $this->stock_location->get_location_name($source_location);
        $data['destination_location_id'] = $destination_location;
        $data['destination_location_name'] = $this->stock_location->get_location_name($destination_location);
        $data['reference'] = $this->transfer_lib->get_reference();
        $data['comment'] = $this->transfer_lib->get_comment();
        $data['employee'] = $employee_info->first_name . ' ' . $employee_info->last_name;
        $data['transfer_date'] = to_datetime(time());

        // Save transfer to database
        $transfer_id = $this->stock_transfer->save_transfer(
            $source_location,
            $destination_location,
            $employee_id,
            $cart,
            $data['reference'],
            $data['comment']
        );

        if ($transfer_id == -1) {
            $data['error'] = lang('Transfers.transaction_failed');
            $data['error_message'] = lang('Transfers.transaction_failed');

            return $this->_reload($data);
        }

        $data['transfer_id'] = 'TRANSFER ' . $transfer_id;

        // Clear session data
        $this->transfer_lib->clear_all();

        return view('transfers/receipt', $data);
    }

    /**
     * View transfer details
     *
     * @param int $transfer_id
     * @return string
     */
    public function getView(int $transfer_id = NEW_ENTRY): string
    {
        // Load create page for new transfer
        if ($transfer_id === NEW_ENTRY) {
            $data = $this->buildTransferFormData();

            return view('transfers/create', $data);
        }

        // Load existing transfer for viewing
        $transfer_info = $this->stock_transfer->get_info($transfer_id)->getRowArray();

        if (empty($transfer_info)) {
            return view('transfers/not_found');
        }

        $data['transfer'] = $transfer_info;
        $data['items'] = $this->stock_transfer->get_items($transfer_id)->getResultArray();

        return view('transfers/view', $data);
    }

    /**
     * View transfers list
     *
     * @return string
     */
    private function _view_transfers(): string
    {
        $data['transfers'] = $this->stock_transfer->get_all_transfers([], 100, 0)->getResultArray();

        return view('transfers/list', $data);
    }

    /**
     * Reload transfer form
     *
     * @param array $data
     * @return string
     */
    private function _reload(array $data = []): string
    {
        $data = $this->buildTransferFormData($data);

        return view('transfers/create', $data);
    }

    /**
     * Prepare transfer form data with dynamic source/destination options
     *
     * @param array $data
     * @return array
     */
    private function buildTransferFormData(array $data = []): array
    {
        $all_locations = $this->getAllLocations();
        $source_locations = $all_locations;
        $source_location = (int) $this->transfer_lib->get_source_location();
        $destination_location = (int) $this->transfer_lib->get_destination_location();

        if (!isset($source_locations[$source_location])) {
            $source_location = !empty($source_locations) ? (int) array_key_first($source_locations) : 0;
        }

        $destination_locations = $all_locations;
        if ($source_location > 0) {
            unset($destination_locations[$source_location]);
        }

        if (!isset($destination_locations[$destination_location])) {
            $destination_location = !empty($destination_locations) ? (int) array_key_first($destination_locations) : 0;
        }

        if ($source_location > 0) {
            $this->transfer_lib->set_source_location($source_location);
        }
        if ($destination_location > 0) {
            $this->transfer_lib->set_destination_location($destination_location);
        }

        if (empty($destination_locations)) {
            $data['error'] = 'Please configure at least two locations for transfers.';
        }

        $data['controller_name'] = 'transfers';
        $data['cart'] = $this->transfer_lib->get_cart();
        $data['source_location'] = $source_location;
        $data['destination_location'] = $destination_location;
        $data['source_locations'] = $source_locations;
        $data['destination_locations'] = $destination_locations;
        $data['show_locations'] = count($source_locations) > 0;
        $data['reference'] = $this->transfer_lib->get_reference();
        $data['comment'] = $this->transfer_lib->get_comment();

        return $data;
    }

    /**
     * Get all undeleted locations as [location_id => location_name]
     *
     * @return array
     */
    private function getAllLocations(): array
    {
        $all_locations = $this->stock_location->get_all()->getResultArray();
        $locations = [];

        foreach ($all_locations as $location) {
            $locations[(int) $location['location_id']] = $location['location_name'];
        }

        return $locations;
    }
}
