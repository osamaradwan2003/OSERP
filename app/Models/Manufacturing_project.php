<?php

namespace App\Models;

use CodeIgniter\Model;

class Manufacturing_project extends Model
{
    protected $table = 'manufacturing_projects';
    protected $primaryKey = 'project_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'project_code',
        'project_name',
        'customer_id',
        'sale_id',
        'project_status',
        'priority',
        'start_date',
        'target_completion_date',
        'actual_completion_date',
        'delivery_date',
        'estimated_hours',
        'actual_hours',
        'budgeted_material_cost',
        'budgeted_labor_cost',
        'budgeted_overhead_cost',
        'project_manager_id',
        'notes',
        'created_by',
        'deleted'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get project with customer and sale info
     */
    public function get_info(int $project_id): array
    {
        $builder = $this->db->table($this->table . ' AS p');
        $builder->select('p.*, c.first_name, c.last_name, cust.company_name AS customer_company,
            e.first_name AS manager_first_name, e.last_name AS manager_last_name,
            s.invoice_number, s.sale_time');
        $builder->join('customers AS cust', 'cust.person_id = p.customer_id', 'left');
        $builder->join('people AS c', 'c.person_id = cust.person_id', 'left');
        $builder->join('employees AS emp', 'emp.person_id = p.project_manager_id', 'left');
        $builder->join('people AS e', 'e.person_id = emp.person_id', 'left');
        $builder->join('sales AS s', 's.sale_id = p.sale_id', 'left');
        $builder->where('p.project_id', $project_id);
        $builder->where('p.deleted', 0);

        $result = $builder->get()->getRowArray();
        return $result ?? [];
    }

    /**
     * Search projects with pagination
     */
    public function search(string $search, int $limit = 20, int $offset = 0, string $sort = 'project_id', string $order = 'desc'): array
    {
        $builder = $this->db->table($this->table . ' AS p');
        $builder->select('p.*, c.first_name, c.last_name, cust.company_name AS customer_company,
            e.first_name AS manager_first_name, e.last_name AS manager_last_name');
        $builder->join('customers AS cust', 'cust.person_id = p.customer_id', 'left');
        $builder->join('people AS c', 'c.person_id = cust.person_id', 'left');
        $builder->join('employees AS emp', 'emp.person_id = p.project_manager_id', 'left');
        $builder->join('people AS e', 'e.person_id = emp.person_id', 'left');
        $builder->where('p.deleted', 0);

        if ($search !== '') {
            $builder->groupStart();
            $builder->like('p.project_code', $search);
            $builder->orLike('p.project_name', $search);
            $builder->orLike('c.first_name', $search);
            $builder->orLike('c.last_name', $search);
            $builder->orLike('cust.company_name', $search);
            $builder->groupEnd();
        }

        $builder->orderBy('p.' . $sort, $order);
        $builder->limit($limit, $offset);

        return $builder->get()->getResultArray();
    }

    /**
     * Get found rows count for search
     */
    public function get_found_rows(string $search): int
    {
        $builder = $this->db->table($this->table . ' AS p');
        $builder->join('customers AS cust', 'cust.person_id = p.customer_id', 'left');
        $builder->join('people AS c', 'c.person_id = cust.person_id', 'left');
        $builder->where('p.deleted', 0);

        if ($search !== '') {
            $builder->groupStart();
            $builder->like('p.project_code', $search);
            $builder->orLike('p.project_name', $search);
            $builder->orLike('c.first_name', $search);
            $builder->orLike('c.last_name', $search);
            $builder->orLike('cust.company_name', $search);
            $builder->groupEnd();
        }

        return $builder->countAllResults();
    }

    /**
     * Generate unique project code
     */
    public function generate_project_code(): string
    {
        $year = date('Y');
        $prefix = 'PRJ-' . $year . '-';

        $builder = $this->db->table($this->table);
        $builder->select('project_code');
        $builder->like('project_code', $prefix, 'after');
        $builder->orderBy('project_id', 'desc');
        $builder->limit(1);

        $result = $builder->get()->getRowArray();

        if ($result) {
            $last_number = (int) substr($result['project_code'], strlen($prefix));
            $new_number = $last_number + 1;
        } else {
            $new_number = 1;
        }

        return $prefix . str_pad($new_number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get project cost summary
     */
    public function get_cost_summary(int $project_id): array
    {
        // Material costs
        $material_cost = $this->db->table('manufacturing_stock_transfer_items AS ti')
            ->selectSum('total_cost')
            ->join('manufacturing_stock_transfers AS t', 't.transfer_id = ti.transfer_id')
            ->where('t.project_id', $project_id)
            ->where('t.status', 'confirmed')
            ->where('t.transfer_type', 'issue')
            ->where('t.deleted', 0)
            ->get()->getRowArray();

        // Labor costs
        $labor_cost = $this->db->table('manufacturing_labor_entries')
            ->selectSum('total_cost')
            ->where('project_id', $project_id)
            ->get()->getRowArray();

        // Overhead costs
        $overhead_cost = $this->db->table('manufacturing_project_costs')
            ->selectSum('amount')
            ->where('project_id', $project_id)
            ->where('cost_type', 'overhead')
            ->get()->getRowArray();

        return [
            'material_cost' => (float) ($material_cost['total_cost'] ?? 0),
            'labor_cost' => (float) ($labor_cost['total_cost'] ?? 0),
            'overhead_cost' => (float) ($overhead_cost['amount'] ?? 0),
            'total_cost' => (float) ($material_cost['total_cost'] ?? 0) +
                           (float) ($labor_cost['total_cost'] ?? 0) +
                           (float) ($overhead_cost['amount'] ?? 0)
        ];
    }

    /**
     * Get projects by status
     */
    public function get_by_status(string $status): array
    {
        return $this->where('project_status', $status)
                    ->where('deleted', 0)
                    ->findAll();
    }

    /**
     * Get projects by customer
     */
    public function get_by_customer(int $customer_id): array
    {
        return $this->where('customer_id', $customer_id)
                    ->where('deleted', 0)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Update project status
     */
    public function update_status(int $project_id, string $status): bool
    {
        $data = ['project_status' => $status];

        if ($status === 'completed') {
            $data['actual_completion_date'] = date('Y-m-d');
        } elseif ($status === 'delivered') {
            $data['delivery_date'] = date('Y-m-d');
        }

        return $this->update($project_id, $data);
    }

    /**
     * Soft delete project
     */
    public function soft_delete(int $project_id): bool
    {
        return $this->update($project_id, ['deleted' => 1]);
    }
}
