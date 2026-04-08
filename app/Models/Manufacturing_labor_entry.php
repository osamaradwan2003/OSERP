<?php

namespace App\Models;

use CodeIgniter\Model;

class Manufacturing_labor_entry extends Model
{
    protected $table = 'manufacturing_labor_entries';
    protected $primaryKey = 'entry_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'project_id',
        'stage_id',
        'employee_id',
        'work_date',
        'start_time',
        'end_time',
        'hours_worked',
        'hourly_rate',
        'total_cost',
        'work_description',
        'created_by'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get entry with project and employee info
     */
    public function get_info(int $entry_id): array
    {
        $builder = $this->db->table($this->table . ' AS l');
        $builder->select('l.*, p.project_code, p.project_name,
                          e.first_name, e.last_name,
                          s.stage_name');
        $builder->join('manufacturing_projects AS p', 'p.project_id = l.project_id', 'left');
        $builder->join('employees AS emp', 'emp.person_id = l.employee_id', 'left');
        $builder->join('people AS e', 'e.person_id = emp.person_id', 'left');
        $builder->join('manufacturing_project_stages AS s', 's.stage_id = l.stage_id', 'left');
        $builder->where('l.entry_id', $entry_id);

        return $builder->get()->getRowArray() ?? [];
    }

    /**
     * Search labor entries with pagination
     */
    public function search(string $search, int $limit = 20, int $offset = 0, string $sort = 'entry_id', string $order = 'desc'): array
    {
        $builder = $this->db->table($this->table . ' AS l');
        $builder->select('l.*, p.project_code, p.project_name,
                          e.first_name, e.last_name,
                          s.stage_name');
        $builder->join('manufacturing_projects AS p', 'p.project_id = l.project_id', 'left');
        $builder->join('employees AS emp', 'emp.person_id = l.employee_id', 'left');
        $builder->join('people AS e', 'e.person_id = emp.person_id', 'left');
        $builder->join('manufacturing_project_stages AS s', 's.stage_id = l.stage_id', 'left');

        if ($search !== '') {
            $builder->groupStart();
            $builder->like('p.project_code', $search);
            $builder->orLike('p.project_name', $search);
            $builder->orLike('e.first_name', $search);
            $builder->orLike('e.last_name', $search);
            $builder->orLike('l.work_description', $search);
            $builder->groupEnd();
        }

        $builder->orderBy('l.' . $sort, $order);
        $builder->limit($limit, $offset);

        return $builder->get()->getResultArray();
    }

    /**
     * Get found rows count for search
     */
    public function get_found_rows(string $search): int
    {
        $builder = $this->db->table($this->table . ' AS l');
        $builder->join('manufacturing_projects AS p', 'p.project_id = l.project_id', 'left');
        $builder->join('employees AS emp', 'emp.person_id = l.employee_id', 'left');
        $builder->join('people AS e', 'e.person_id = emp.person_id', 'left');

        if ($search !== '') {
            $builder->groupStart();
            $builder->like('p.project_code', $search);
            $builder->orLike('p.project_name', $search);
            $builder->orLike('e.first_name', $search);
            $builder->orLike('e.last_name', $search);
            $builder->orLike('l.work_description', $search);
            $builder->groupEnd();
        }

        return $builder->countAllResults();
    }

    /**
     * Get entries by project
     */
    public function get_by_project(int $project_id): array
    {
        $builder = $this->db->table($this->table . ' AS l');
        $builder->select('l.*, e.first_name, e.last_name, s.stage_name');
        $builder->join('employees AS emp', 'emp.person_id = l.employee_id', 'left');
        $builder->join('people AS e', 'e.person_id = emp.person_id', 'left');
        $builder->join('manufacturing_project_stages AS s', 's.stage_id = l.stage_id', 'left');
        $builder->where('l.project_id', $project_id);
        $builder->orderBy('l.work_date', 'DESC');

        return $builder->get()->getResultArray();
    }

    /**
     * Get entries by employee
     */
    public function get_by_employee(int $employee_id, ?string $start_date = null, ?string $end_date = null): array
    {
        $builder = $this->db->table($this->table . ' AS l');
        $builder->select('l.*, p.project_code, p.project_name, s.stage_name');
        $builder->join('manufacturing_projects AS p', 'p.project_id = l.project_id', 'left');
        $builder->join('manufacturing_project_stages AS s', 's.stage_id = l.stage_id', 'left');
        $builder->where('l.employee_id', $employee_id);

        if ($start_date !== null) {
            $builder->where('l.work_date >=', $start_date);
        }
        if ($end_date !== null) {
            $builder->where('l.work_date <=', $end_date);
        }

        $builder->orderBy('l.work_date', 'DESC');

        return $builder->get()->getResultArray();
    }

    /**
     * Get total hours by project
     */
    public function get_total_hours(int $project_id): float
    {
        $result = $this->selectSum('hours_worked')
                       ->where('project_id', $project_id)
                       ->get()
                       ->getRowArray();

        return (float) ($result['hours_worked'] ?? 0);
    }

    /**
     * Get total cost by project
     */
    public function get_total_cost(int $project_id): float
    {
        $result = $this->selectSum('total_cost')
                       ->where('project_id', $project_id)
                       ->get()
                       ->getRowArray();

        return (float) ($result['total_cost'] ?? 0);
    }

    /**
     * Create entry and cost record
     */
    public function create_entry(array $data): int
    {
        // Calculate total cost
        $data['total_cost'] = $data['hours_worked'] * $data['hourly_rate'];

        // Insert entry
        $this->insert($data);
        $entry_id = (int) $this->getInsertID();

        // Create cost entry
        $costModel = model(\App\Models\Manufacturing_project_cost::class);
        $costModel->insert([
            'project_id' => $data['project_id'],
            'cost_type' => 'labor',
            'cost_source' => 'labor_entry',
            'reference_id' => $entry_id,
            'description' => 'Labor: ' . ($data['work_description'] ?? 'Work entry'),
            'amount' => $data['total_cost'],
            'cost_date' => $data['work_date'],
            'created_by' => $data['created_by']
        ]);

        return $entry_id;
    }
}
