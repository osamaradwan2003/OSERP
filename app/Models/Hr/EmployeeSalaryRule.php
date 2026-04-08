<?php

namespace App\Models\Hr;

use CodeIgniter\Model;

class EmployeeSalaryRule extends Model
{
    protected $table = 'employee_salary_rules';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'employee_id', 'rule_id', 'custom_value', 'is_active', 'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function get_employee_rules(int $employeeId): array
    {
        return $this->select('ospos_employee_salary_rules.*, ospos_salary_rules.name, ospos_salary_rules.code, ospos_salary_rules.rule_type')
            ->join('ospos_salary_rules', 'ospos_salary_rules.id = ospos_employee_salary_rules.rule_id', 'left')
            ->where('ospos_employee_salary_rules.employee_id', $employeeId)
            ->where('ospos_employee_salary_rules.is_active', 1)
            ->findAll();
    }

    public function assign_rule(int $employeeId, int $ruleId, ?float $customValue = null): bool
    {
        $existing = $this->where('employee_id', $employeeId)
            ->where('rule_id', $ruleId)
            ->first();

        if ($existing) {
            return $this->update($existing['id'], [
                'custom_value' => $customValue,
                'is_active' => 1
            ]);
        }

        return (bool) $this->insert([
            'employee_id' => $employeeId,
            'rule_id' => $ruleId,
            'custom_value' => $customValue,
            'is_active' => 1
        ]);
    }

    public function remove_rule(int $employeeId, int $ruleId): bool
    {
        return (bool) $this->where('employee_id', $employeeId)
            ->where('rule_id', $ruleId)
            ->set('is_active', 0)
            ->update();
    }

    public function get_custom_value(int $employeeId, int $ruleId): ?float
    {
        $result = $this->where('employee_id', $employeeId)
            ->where('rule_id', $ruleId)
            ->where('is_active', 1)
            ->first();

        return $result ? (float) $result['custom_value'] : null;
    }
}
