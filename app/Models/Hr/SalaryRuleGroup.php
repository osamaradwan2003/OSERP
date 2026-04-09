<?php

namespace App\Models\Hr;

use CodeIgniter\Model;

class SalaryRuleGroup extends Model
{
    protected $table = 'salary_rule_groups';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'name', 'type', 'calculation_order', 'is_active', 'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function get_all_active(): array
    {
        return $this->db->table('salary_rule_groups')
            ->select('id, name, type, calculation_order, is_active, created_at, updated_at')
            ->where('is_active', 1)
            ->orderBy('calculation_order', 'ASC')
            ->orderBy('name', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function get_options(): array
    {
        $result = [];
        foreach ($this->get_all_active() as $group) {
            $result[$group['id']] = $group['name'] . ' (' . ucfirst($group['type']) . ')';
        }
        return $result;
    }

    public function get_earning_groups(): array
    {
        return $this->db->table('salary_rule_groups')
            ->select('id, name, type, calculation_order, is_active, created_at, updated_at')
            ->where('is_active', 1)
            ->where('type', 'earning')
            ->orderBy('calculation_order', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function get_deduction_groups(): array
    {
        return $this->db->table('salary_rule_groups')
            ->select('id, name, type, calculation_order, is_active, created_at, updated_at')
            ->where('is_active', 1)
            ->where('type', 'deduction')
            ->orderBy('calculation_order', 'ASC')
            ->get()
            ->getResultArray();
    }
}
