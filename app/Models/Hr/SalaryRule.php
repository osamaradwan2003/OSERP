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
        return $this->select('ospos_salary_rules.*, ospos_salary_rule_groups.name as group_name, ospos_salary_rule_groups.type as group_type')
            ->join('ospos_salary_rule_groups', 'ospos_salary_rule_groups.id = ospos_salary_rules.group_id', 'left')
            ->where('ospos_salary_rules.is_active', 1)
            ->orderBy('ospos_salary_rules.priority', 'ASC')
            ->findAll();
    }

    public function get_all_with_group(): array
    {
        return $this->select('ospos_salary_rules.*, ospos_salary_rule_groups.name as group_name, ospos_salary_rule_groups.type as group_type')
            ->join('ospos_salary_rule_groups', 'ospos_salary_rule_groups.id = ospos_salary_rules.group_id', 'left')
            ->orderBy('ospos_salary_rule_groups.calculation_order', 'ASC')
            ->orderBy('ospos_salary_rules.priority', 'ASC')
            ->findAll();
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
        $builder = $this->db->table('ospos_salary_rules');
        $builder->select('ospos_salary_rules.*, ospos_salary_rule_groups.type as group_type');
        $builder->join('ospos_salary_rule_groups', 'ospos_salary_rule_groups.id = ospos_salary_rules.group_id', 'left');
        $builder->where('ospos_salary_rules.is_active', 1);
        $builder->groupStart();
        $builder->where('ospos_salary_rules.scope', 'global');
        $builder->orWhere(function($q) use ($departmentId) {
            $q->where('ospos_salary_rules.scope', 'department');
            $q->where('ospos_salary_rules.scope_id', $departmentId);
        });
        $builder->orWhere(function($q) use ($positionId) {
            $q->where('ospos_salary_rules.scope', 'position');
            $q->where('ospos_salary_rules.scope_id', $positionId);
        });
        $builder->orWhere(function($q) use ($employeeId) {
            $q->where('ospos_salary_rules.scope', 'employee');
            $q->where('ospos_salary_rules.scope_id', $employeeId);
        });
        $builder->groupEnd();
        $builder->orderBy('ospos_salary_rules.priority', 'ASC');

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
