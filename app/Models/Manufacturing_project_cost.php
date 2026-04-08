<?php

namespace App\Models;

use CodeIgniter\Model;

class Manufacturing_project_cost extends Model
{
    protected $table = 'manufacturing_project_costs';
    protected $primaryKey = 'cost_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'project_id',
        'cost_type',
        'cost_source',
        'reference_id',
        'description',
        'amount',
        'cost_date',
        'created_by'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = false;

    /**
     * Get costs by project
     */
    public function get_by_project(int $project_id): array
    {
        return $this->where('project_id', $project_id)
                    ->orderBy('cost_date', 'DESC')
                    ->findAll();
    }

    /**
     * Get costs by type
     */
    public function get_by_type(int $project_id, string $cost_type): array
    {
        return $this->where('project_id', $project_id)
                    ->where('cost_type', $cost_type)
                    ->orderBy('cost_date', 'DESC')
                    ->findAll();
    }

    /**
     * Get total by type
     */
    public function get_total_by_type(int $project_id, string $cost_type): float
    {
        $result = $this->selectSum('amount')
                       ->where('project_id', $project_id)
                       ->where('cost_type', $cost_type)
                       ->get()
                       ->getRowArray();

        return (float) ($result['amount'] ?? 0);
    }

    /**
     * Get cost summary for project
     */
    public function get_summary(int $project_id): array
    {
        $types = ['material', 'labor', 'overhead', 'other'];
        $summary = [];

        foreach ($types as $type) {
            $summary[$type] = $this->get_total_by_type($project_id, $type);
        }

        $summary['total'] = array_sum($summary);

        return $summary;
    }

    /**
     * Get costs by date range
     */
    public function get_by_date_range(string $start_date, string $end_date): array
    {
        return $this->where('cost_date >=', $start_date)
                    ->where('cost_date <=', $end_date)
                    ->orderBy('cost_date', 'ASC')
                    ->findAll();
    }

    /**
     * Add manual cost entry
     */
    public function add_manual_cost(int $project_id, string $description, float $amount, string $cost_date, int $created_by): int
    {
        $data = [
            'project_id' => $project_id,
            'cost_type' => 'other',
            'cost_source' => 'manual_entry',
            'description' => $description,
            'amount' => $amount,
            'cost_date' => $cost_date,
            'created_by' => $created_by
        ];

        $this->insert($data);
        return (int) $this->getInsertID();
    }
}
