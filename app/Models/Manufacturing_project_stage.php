<?php

namespace App\Models;

use CodeIgniter\Model;

class Manufacturing_project_stage extends Model
{
    protected $table = 'manufacturing_project_stages';
    protected $primaryKey = 'stage_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'project_id',
        'stage_name',
        'stage_sequence',
        'stage_status',
        'start_date',
        'end_date',
        'assigned_to',
        'notes'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get all stages for a project
     */
    public function get_by_project(int $project_id): array
    {
        return $this->where('project_id', $project_id)
                    ->orderBy('stage_sequence', 'ASC')
                    ->findAll();
    }

    /**
     * Get stage with employee info
     */
    public function get_info(int $stage_id): array
    {
        $builder = $this->db->table($this->table . ' AS s');
        $builder->select('s.*, e.first_name, e.last_name');
        $builder->join('employees AS emp', 'emp.person_id = s.assigned_to', 'left');
        $builder->join('people AS e', 'e.person_id = emp.person_id', 'left');
        $builder->where('s.stage_id', $stage_id);

        return $builder->get()->getRowArray() ?? [];
    }

    /**
     * Update stage status
     */
    public function update_status(int $stage_id, string $status): bool
    {
        $data = ['stage_status' => $status];

        if ($status === 'in_progress') {
            $data['start_date'] = date('Y-m-d H:i:s');
        } elseif ($status === 'completed') {
            $data['end_date'] = date('Y-m-d H:i:s');
        }

        return $this->update($stage_id, $data);
    }

    /**
     * Get next pending stage for a project
     */
    public function get_next_pending(int $project_id): array
    {
        return $this->where('project_id', $project_id)
                    ->where('stage_status', 'pending')
                    ->orderBy('stage_sequence', 'ASC')
                    ->first() ?? [];
    }

    /**
     * Calculate project progress percentage
     */
    public function get_progress(int $project_id): float
    {
        $stages = $this->where('project_id', $project_id)->findAll();
        $total = count($stages);

        if ($total === 0) {
            return 0.0;
        }

        $completed = 0;
        foreach ($stages as $stage) {
            if ($stage['stage_status'] === 'completed') {
                $completed++;
            }
        }

        return round(($completed / $total) * 100, 2);
    }
}
