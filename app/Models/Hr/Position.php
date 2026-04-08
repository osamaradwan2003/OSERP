<?php

namespace App\Models\Hr;

use CodeIgniter\Model;

class Position extends Model
{
    protected $table = 'positions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'name', 'description', 'department_id', 'level', 'is_active', 'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function get_all_active(): array
    {
        return $this->where('is_active', 1)->orderBy('level', 'ASC')->findAll();
    }

    public function get_by_department(int $departmentId): array
    {
        return $this->where('department_id', $departmentId)
            ->where('is_active', 1)
            ->orderBy('level', 'ASC')
            ->findAll();
    }

    public function get_options(): array
    {
        $result = [];
        foreach ($this->get_all_active() as $position) {
            $result[$position['id']] = $position['name'];
        }
        return $result;
    }

    public function get_with_department(): array
    {
        return $this->select('ospos_positions.*, ospos_departments.name as department_name')
            ->join('ospos_departments', 'ospos_departments.id = ospos_positions.department_id', 'left')
            ->where('ospos_positions.is_active', 1)
            ->orderBy('ospos_departments.name', 'ASC')
            ->orderBy('ospos_positions.level', 'ASC')
            ->findAll();
    }
}
