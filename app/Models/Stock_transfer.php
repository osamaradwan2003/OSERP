<?php

namespace App\Models;

use CodeIgniter\Database\ResultInterface;
use CodeIgniter\Model;
use ReflectionException;

/**
 * Stock_transfer class
 * Manages stock transfers between locations
 */
class Stock_transfer extends Model
{
    protected $table = 'stock_transfers';
    protected $primaryKey = 'transfer_id';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'transfer_datetime',
        'source_location_id',
        'destination_location_id',
        'employee_id',
        'reference',
        'comment',
        'transfer_status',
        'deleted'
    ];

    /**
     * Get transfer information with joined data
     *
     * @param int $transfer_id
     * @return ResultInterface
     */
    public function get_info(int $transfer_id): ResultInterface
    {
        $builder = $this->db->table('stock_transfers');
        $builder->join('stock_locations AS sl_source', 'sl_source.location_id = stock_transfers.source_location_id', 'LEFT');
        $builder->join('stock_locations AS sl_dest', 'sl_dest.location_id = stock_transfers.destination_location_id', 'LEFT');
        $builder->join('employees', 'employees.person_id = stock_transfers.employee_id', 'LEFT');
        $builder->join('people', 'people.person_id = employees.person_id', 'LEFT');
        $builder->where('transfer_id', $transfer_id);

        return $builder->get();
    }

    /**
     * Get all transfers with filtering
     *
     * @param array $filters Optional filters (source_location_id, destination_location_id, employee_id, deleted)
     * @param int $limit
     * @param int $offset
     * @return ResultInterface
     */
    public function get_all_transfers(array $filters = [], int $limit = 50, int $offset = 0): ResultInterface
    {
        $builder = $this->db->table('stock_transfers');
        $builder->join('stock_locations AS sl_source', 'sl_source.location_id = stock_transfers.source_location_id', 'LEFT');
        $builder->join('stock_locations AS sl_dest', 'sl_dest.location_id = stock_transfers.destination_location_id', 'LEFT');
        $builder->join('employees', 'employees.person_id = stock_transfers.employee_id', 'LEFT');
        $builder->join('people', 'people.person_id = employees.person_id', 'LEFT');

        if (isset($filters['source_location_id'])) {
            $builder->where('stock_transfers.source_location_id', $filters['source_location_id']);
        }

        if (isset($filters['destination_location_id'])) {
            $builder->where('stock_transfers.destination_location_id', $filters['destination_location_id']);
        }

        if (isset($filters['employee_id'])) {
            $builder->where('stock_transfers.employee_id', $filters['employee_id']);
        }

        if (!isset($filters['include_deleted']) || !$filters['include_deleted']) {
            $builder->where('stock_transfers.deleted', 0);
        }

        $builder->orderBy('stock_transfers.transfer_datetime', 'DESC');
        $builder->limit($limit, $offset);

        return $builder->get();
    }

    /**
     * Get transfer items
     *
     * @param int $transfer_id
     * @return ResultInterface
     */
    public function get_items(int $transfer_id): ResultInterface
    {
        $builder = $this->db->table('stock_transfers_items');
        $builder->join('items', 'items.item_id = stock_transfers_items.item_id', 'LEFT');
        $builder->where('transfer_id', $transfer_id);
        $builder->orderBy('line', 'ASC');

        return $builder->get();
    }

    /**
     * Save a new transfer
     *
     * @param int $source_location_id
     * @param int $destination_location_id
     * @param int $employee_id
     * @param array $items Array of items to transfer
     * @param string $reference Optional reference number
     * @param string $comment Optional comment
     * @return int Transfer ID or -1 on failure
     *
     * @throws ReflectionException
     */
    public function save_transfer(
        int $source_location_id,
        int $destination_location_id,
        int $employee_id,
        array $items,
        string $reference = '',
        string $comment = ''
    ): int {
        $this->db->transStart();

        try {
            // Insert transfer header
            $transfer_data = [
                'transfer_datetime'       => date('Y-m-d H:i:s'),
                'source_location_id'      => $source_location_id,
                'destination_location_id' => $destination_location_id,
                'employee_id'             => $employee_id,
                'reference'               => $reference,
                'comment'                 => $comment,
                'transfer_status'         => 'completed'
            ];

            $builder = $this->db->table('stock_transfers');
            $builder->insert($transfer_data);
            $transfer_id = $this->db->insertID();

            // Insert transfer items
            $item_quantity_model = model(Item_quantity::class);
            $line_number = 0;

            foreach ($items as $item) {
                $item_id = $item['item_id'];
                $quantity = $item['quantity'];

                // Insert line item
                $line_number++;
                $line_data = [
                    'transfer_id'   => $transfer_id,
                    'item_id'       => $item_id,
                    'line'          => $line_number,
                    'quantity'      => $quantity,
                    'description'   => $item['description'] ?? '',
                    'serialnumber'  => $item['serialnumber'] ?? ''
                ];

                $builder = $this->db->table('stock_transfers_items');
                $builder->insert($line_data);

                // Update inventory - remove from source
                $inventory_data = [
                    'trans_items'    => $item_id,
                    'trans_user'     => $employee_id,
                    'trans_date'     => date('Y-m-d H:i:s'),
                    'trans_comment'  => 'TRANSFER FROM ' . $source_location_id,
                    'trans_location' => $source_location_id,
                    'trans_inventory' => -$quantity
                ];

                $builder = $this->db->table('inventory');
                $builder->insert($inventory_data);

                // Update inventory - add to destination
                $inventory_data = [
                    'trans_items'    => $item_id,
                    'trans_user'     => $employee_id,
                    'trans_date'     => date('Y-m-d H:i:s'),
                    'trans_comment'  => 'TRANSFER TO ' . $destination_location_id,
                    'trans_location' => $destination_location_id,
                    'trans_inventory' => $quantity
                ];

                $builder = $this->db->table('inventory');
                $builder->insert($inventory_data);

                // Update item quantities
                $item_quantity_model->change_quantity($item_id, $source_location_id, -$quantity);
                $item_quantity_model->change_quantity($item_id, $destination_location_id, $quantity);
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $db_error = $this->db->error();
                if (!empty($db_error['message'])) {
                    log_message('error', 'Stock transfer DB error: ' . $db_error['message']);
                }
                return -1;
            }

            return $transfer_id;
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Stock transfer error: ' . $e->getMessage());
            return -1;
        }
    }

    /**
     * Delete a transfer
     *
     * @param int $transfer_id
     * @return bool
     */
    public function delete_transfer(int $transfer_id): bool
    {
        $builder = $this->db->table('stock_transfers');
        $builder->where('transfer_id', $transfer_id);

        return $builder->update(['deleted' => 1]);
    }

    /**
     * Check if transfer exists
     *
     * @param int $transfer_id
     * @return bool
     */
    public function exists(int $transfer_id): bool
    {
        $builder = $this->db->table('stock_transfers');
        $builder->where('transfer_id', $transfer_id);

        return ($builder->get()->getNumRows() >= 1);
    }

    /**
     * Get transfer count
     *
     * @param array $filters Optional filters
     * @return int
     */
    public function get_count(array $filters = []): int
    {
        $builder = $this->db->table('stock_transfers');

        if (isset($filters['source_location_id'])) {
            $builder->where('source_location_id', $filters['source_location_id']);
        }

        if (isset($filters['destination_location_id'])) {
            $builder->where('destination_location_id', $filters['destination_location_id']);
        }

        if (!isset($filters['include_deleted']) || !$filters['include_deleted']) {
            $builder->where('deleted', 0);
        }

        return $builder->countAllResults();
    }
}
