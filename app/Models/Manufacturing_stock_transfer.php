<?php

namespace App\Models;

use CodeIgniter\Model;
use ReflectionException;

class Manufacturing_stock_transfer extends Model
{
    protected $table = 'manufacturing_stock_transfers';
    protected $primaryKey = 'transfer_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'transfer_code',
        'project_id',
        'source_location_id',
        'transfer_type',
        'transfer_date',
        'reference',
        'notes',
        'status',
        'created_by',
        'confirmed_by',
        'confirmed_at',
        'deleted'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get transfer with project and location info
     */
    public function get_info(int $transfer_id): array
    {
        $builder = $this->db->table($this->table . ' AS t');
        $builder->select('t.*, p.project_code, p.project_name, sl.location_name,
                          e.first_name AS creator_first_name, e.last_name AS creator_last_name,
                          c.first_name AS confirmer_first_name, c.last_name AS confirmer_last_name');
        $builder->join('manufacturing_projects AS p', 'p.project_id = t.project_id', 'left');
        $builder->join('stock_locations AS sl', 'sl.location_id = t.source_location_id', 'left');
        $builder->join('employees AS emp_creator', 'emp_creator.person_id = t.created_by', 'left');
        $builder->join('people AS e', 'e.person_id = emp_creator.person_id', 'left');
        $builder->join('employees AS emp_confirmer', 'emp_confirmer.person_id = t.confirmed_by', 'left');
        $builder->join('people AS c', 'c.person_id = emp_confirmer.person_id', 'left');
        $builder->where('t.transfer_id', $transfer_id);

        return $builder->get()->getRowArray() ?? [];
    }

    /**
     * Search transfers with pagination
     */
    public function search(string $search, int $limit = 20, int $offset = 0, string $sort = 'transfer_id', string $order = 'desc'): array
    {
        $builder = $this->db->table($this->table . ' AS t');
        $builder->select('t.*, p.project_code, p.project_name, sl.location_name');
        $builder->join('manufacturing_projects AS p', 'p.project_id = t.project_id', 'left');
        $builder->join('stock_locations AS sl', 'sl.location_id = t.source_location_id', 'left');
        $builder->where('t.deleted', 0);

        if ($search !== '') {
            $builder->groupStart();
            $builder->like('t.transfer_code', $search);
            $builder->orLike('p.project_code', $search);
            $builder->orLike('p.project_name', $search);
            $builder->orLike('t.reference', $search);
            $builder->groupEnd();
        }

        $builder->orderBy('t.' . $sort, $order);
        $builder->limit($limit, $offset);

        return $builder->get()->getResultArray();
    }

    /**
     * Get found rows count for search
     */
    public function get_found_rows(string $search): int
    {
        $builder = $this->db->table($this->table . ' AS t');
        $builder->join('manufacturing_projects AS p', 'p.project_id = t.project_id', 'left');
        $builder->where('t.deleted', 0);

        if ($search !== '') {
            $builder->groupStart();
            $builder->like('t.transfer_code', $search);
            $builder->orLike('p.project_code', $search);
            $builder->orLike('p.project_name', $search);
            $builder->orLike('t.reference', $search);
            $builder->groupEnd();
        }

        return $builder->countAllResults();
    }

    /**
     * Generate unique transfer code
     */
    public function generate_transfer_code(): string
    {
        $year = date('Y');
        $prefix = 'MTR-' . $year . '-';

        $builder = $this->db->table($this->table);
        $builder->select('transfer_code');
        $builder->like('transfer_code', $prefix, 'after');
        $builder->orderBy('transfer_id', 'desc');
        $builder->limit(1);

        $result = $builder->get()->getRowArray();

        if ($result) {
            $last_number = (int) substr($result['transfer_code'], strlen($prefix));
            $new_number = $last_number + 1;
        } else {
            $new_number = 1;
        }

        return $prefix . str_pad($new_number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get transfer items
     */
    public function get_items(int $transfer_id): array
    {
        $builder = $this->db->table('manufacturing_stock_transfer_items AS ti');
        $builder->select('ti.*, i.name, i.item_number, i.description');
        $builder->join('items AS i', 'i.item_id = ti.item_id_fk', 'left');
        $builder->where('ti.transfer_id', $transfer_id);
        $builder->orderBy('ti.item_id', 'ASC');

        return $builder->get()->getResultArray();
    }

    /**
     * Add item to transfer
     */
    public function add_item(int $transfer_id, int $item_id, float $quantity, float $unit_cost, ?string $serial_number = null, ?string $notes = null): int
    {
        $data = [
            'transfer_id' => $transfer_id,
            'item_id_fk' => $item_id,
            'quantity' => $quantity,
            'unit_cost' => $unit_cost,
            'total_cost' => $quantity * $unit_cost,
            'serial_number' => $serial_number,
            'notes' => $notes
        ];

        $this->db->table('manufacturing_stock_transfer_items')->insert($data);
        return (int) $this->db->insertID();
    }

    /**
     * Remove item from transfer
     */
    public function remove_item(int $item_id): bool
    {
        return $this->db->table('manufacturing_stock_transfer_items')
            ->where('item_id', $item_id)
            ->delete();
    }

    /**
     * Confirm transfer - update inventory and create cost entry
     * @throws ReflectionException
     */
    public function confirm(int $transfer_id, int $confirmed_by): bool
    {
        $transfer = $this->find($transfer_id);

        if (!$transfer || $transfer['status'] !== 'draft') {
            return false;
        }

        // Update transfer status
        $this->update($transfer_id, [
            'status' => 'confirmed',
            'confirmed_by' => $confirmed_by,
            'confirmed_at' => date('Y-m-d H:i:s')
        ]);

        // Update inventory for each item
        $items = $this->get_items($transfer_id);
        $inventoryModel = model(\App\Models\Inventory::class);
        $itemQuantityModel = model(\App\Models\Item_quantity::class);

        foreach ($items as $item) {
            $quantity = $transfer['transfer_type'] === 'issue'
                ? -$item['quantity']
                : $item['quantity'];

            // Update item quantity
            $itemQuantityModel->change_quantity(
                $item['item_id_fk'],
                $transfer['source_location_id'],
                $quantity
            );

            // Record inventory transaction
            $inventoryModel->save([
                'trans_items' => $item['item_id_fk'],
                'trans_user' => $confirmed_by,
                'trans_date' => date('Y-m-d H:i:s'),
                'trans_comment' => 'Manufacturing Transfer: ' . $transfer['transfer_code'],
                'trans_location' => $transfer['source_location_id'],
                'trans_inventory' => $quantity
            ]);
        }

        // Create cost entry
        $total_cost = array_sum(array_column($items, 'total_cost'));
        $costModel = model(\App\Models\Manufacturing_project_cost::class);
        $costModel->insert([
            'project_id' => $transfer['project_id'],
            'cost_type' => 'material',
            'cost_source' => 'material_transfer',
            'reference_id' => $transfer_id,
            'description' => 'Material Transfer: ' . $transfer['transfer_code'],
            'amount' => $total_cost,
            'cost_date' => date('Y-m-d'),
            'created_by' => $confirmed_by
        ]);

        return true;
    }

    /**
     * Cancel transfer
     */
    public function cancel(int $transfer_id): bool
    {
        $transfer = $this->find($transfer_id);

        if (!$transfer || $transfer['status'] !== 'draft') {
            return false;
        }

        return $this->update($transfer_id, ['status' => 'cancelled']);
    }

    /**
     * Get transfers by project
     */
    public function get_by_project(int $project_id): array
    {
        return $this->where('project_id', $project_id)
                    ->where('deleted', 0)
                    ->orderBy('transfer_date', 'DESC')
                    ->findAll();
    }

    /**
     * Soft delete transfer
     */
    public function soft_delete(int $transfer_id): bool
    {
        return $this->update($transfer_id, ['deleted' => 1]);
    }
}
