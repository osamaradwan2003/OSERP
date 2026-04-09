<?php

namespace App\Models\Hr;

use CodeIgniter\Model;

class SalaryRule extends Model
{
    protected $table = 'salary_rules';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'group_id', 'name', 'code', 'rule_type', 'value', 'formula', 'based_on',
        'conditions', 'attendance_type', 'attendance_rate', 'scope', 'scope_id',
        'is_active', 'is_recurring', 'priority', 'description', 'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function get_all_active(): array
    {
        return $this->db->table('salary_rules r')
            ->select('r.id, r.group_id, r.name, r.code, r.rule_type, r.value, r.formula, r.based_on,
                      r.conditions, r.attendance_type, r.attendance_rate, r.scope, r.scope_id,
                      r.is_active, r.is_recurring, r.priority, r.description, r.created_at, r.updated_at,
                      g.name as group_name, g.type as group_type')
            ->join('salary_rule_groups g', 'g.id = r.group_id', 'left')
            ->where('r.is_active', 1)
            ->orderBy('r.priority', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function get_all_with_group(): array
    {
        return $this->db->table('salary_rules r')
            ->select('r.id, r.group_id, r.name, r.code, r.rule_type, r.value, r.formula, r.based_on,
                      r.conditions, r.attendance_type, r.attendance_rate, r.scope, r.scope_id,
                      r.is_active, r.is_recurring, r.priority, r.description, r.created_at, r.updated_at,
                      g.name as group_name, g.type as group_type, g.calculation_order')
            ->join('salary_rule_groups g', 'g.id = r.group_id', 'left')
            ->orderBy('g.calculation_order', 'ASC')
            ->orderBy('r.priority', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function get_by_group(int $groupId): array
    {
        return $this->where('group_id', $groupId)
            ->where('is_active', 1)
            ->orderBy('priority', 'ASC')
            ->findAll();
    }

    public function get_global_rules(): array
    {
        return $this->where('scope', 'global')
            ->where('is_active', 1)
            ->orderBy('priority', 'ASC')
            ->findAll();
    }

    public function get_rules_for_employee(int $employeeId, ?int $departmentId = null, ?int $positionId = null): array
    {
        $builder = $this->db->table('salary_rules r');
        $builder->select('r.*, g.type as group_type');
        $builder->join('salary_rule_groups g', 'g.id = r.group_id', 'left');
        $builder->where('r.is_active', 1);
        
        $builder->groupStart();
        $builder->where('r.scope', 'global');
        $builder->where('(r.scope_id IS NULL OR r.scope_id = 0)', null, false);
        
        if ($departmentId !== null) {
            $builder->orWhere(['r.scope' => 'department', 'r.scope_id' => $departmentId]);
        }
        if ($positionId !== null) {
            $builder->orWhere(['r.scope' => 'position', 'r.scope_id' => $positionId]);
        }
        $builder->orWhere(['r.scope' => 'employee', 'r.scope_id' => $employeeId]);
        $builder->groupEnd();
        
        $builder->orderBy('r.priority', 'ASC');

        return $builder->get()->getResultArray();
    }

    public function get_by_code(string $code): ?array
    {
        return $this->where('code', $code)->first();
    }

    public function get_options(): array
    {
        $result = [];
        foreach ($this->get_all_active() as $rule) {
            $result[$rule['id']] = $rule['name'] . ' (' . $rule['code'] . ')';
        }
        return $result;
    }
}
