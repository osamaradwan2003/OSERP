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
        return $this->db->table('positions p')
            ->select('p.id, p.name, p.description, p.department_id, p.level, p.is_active, p.created_at, p.updated_at, d.name as department_name')
            ->join('departments d', 'd.id = p.department_id', 'left')
            ->where('p.is_active', 1)
            ->orderBy('d.name', 'ASC')
            ->orderBy('p.level', 'ASC')
            ->get()
            ->getResultArray();
    }
}
