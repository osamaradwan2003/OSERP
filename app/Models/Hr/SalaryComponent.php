<?php

namespace App\Models\Hr;

use CodeIgniter\Model;

class SalaryComponent extends Model
{
    protected $table = 'salary_components';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'employee_id', 'period_start', 'period_end', 'rule_id', 'rule_name',
        'rule_type', 'rule_group_type', 'calculated_value', 'calculation_details', 'created_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function get_employee_components(int $employeeId, string $periodStart, string $periodEnd): array
    {
        return $this->where('employee_id', $employeeId)
            ->where('period_start', $periodStart)
            ->where('period_end', $periodEnd)
            ->findAll();
    }

    public function get_earnings(int $employeeId, string $periodStart, string $periodEnd): float
    {
        $result = $this->selectSum('calculated_value')
            ->where('employee_id', $employeeId)
            ->where('period_start', $periodStart)
            ->where('period_end', $periodEnd)
            ->where('rule_group_type', 'earning')
            ->first();

        return (float) ($result['calculated_value'] ?? 0);
    }

    public function get_deductions(int $employeeId, string $periodStart, string $periodEnd): float
    {
        $result = $this->selectSum('calculated_value')
            ->where('employee_id', $employeeId)
            ->where('period_start', $periodStart)
            ->where('period_end', $periodEnd)
            ->where('rule_group_type', 'deduction')
            ->first();

        return (float) ($result['calculated_value'] ?? 0);
    }

    public function save_component(array $data): bool
    {
        return (bool) $this->insert($data);
    }

    public function clear_period(string $periodStart, string $periodEnd): bool
    {
        return $this->where('period_start', $periodStart)
            ->where('period_end', $periodEnd)
            ->delete();
    }

    public function clear_employee_period(int $employeeId, string $periodStart, string $periodEnd): bool
    {
        return $this->where('employee_id', $employeeId)
            ->where('period_start', $periodStart)
            ->where('period_end', $periodEnd)
            ->delete();
    }

    public function get_by_period(string $periodStart, string $periodEnd): array
    {
        return $this->select('ospos_salary_components.*, ospos_people.first_name, ospos_people.last_name')
            ->join('ospos_people', 'ospos_people.person_id = ospos_salary_components.employee_id', 'inner')
            ->where('period_start', $periodStart)
            ->where('period_end', $periodEnd)
            ->findAll();
    }
}
