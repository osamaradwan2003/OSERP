<?php

namespace App\Services\Hr;

use App\Models\Hr\Attendance;
use App\Models\Hr\EmployeeProfile;
use App\Models\Hr\EmployeeSalaryRule;
use App\Models\Hr\SalaryComponent;
use App\Models\Hr\SalaryRule;
use App\Models\Hr\SalaryRuleGroup;
use App\Models\Employee;

class SalaryCalculator
{
    private Attendance $attendance;
    private EmployeeProfile $employeeProfile;
    private EmployeeSalaryRule $employeeSalaryRule;
    private SalaryComponent $salaryComponent;
    private SalaryRule $salaryRule;
    private SalaryRuleGroup $salaryRuleGroup;
    private Employee $employee;

    private float $basicSalary = 0;
    private float $hourlyRate = 0;
    private array $attendanceData = [];
    private array $appliedComponents = [];
    private float $currentGross = 0;

    public function __construct()
    {
        $this->attendance = new Attendance();
        $this->employeeProfile = new EmployeeProfile();
        $this->employeeSalaryRule = new EmployeeSalaryRule();
        $this->salaryComponent = new SalaryComponent();
        $this->salaryRule = new SalaryRule();
        $this->salaryRuleGroup = new SalaryRuleGroup();
        $this->employee = new Employee();
    }

    public function calculate(int $employeeId, string $periodStart, string $periodEnd): array
    {
        $this->reset();

        $profile = $this->employeeProfile->get_info($employeeId);
        if (!$profile) {
            return ['success' => false, 'message' => 'Employee profile not found'];
        }

        $this->basicSalary = (float) ($profile['basic_salary'] ?? 0);
        $this->hourlyRate = (float) ($profile['hourly_rate'] ?? 0);

        if ($this->hourlyRate == 0 && $this->basicSalary > 0) {
            $this->hourlyRate = $this->basicSalary / 176;
        }

        $this->attendanceData = $this->attendance->get_summary($employeeId, $periodStart, $periodEnd);

        $this->salaryComponent->clear_employee_period($employeeId, $periodStart, $periodEnd);

        $rules = $this->get_applicable_rules($employeeId, $profile);

        $totalEarnings = 0;
        $totalDeductions = 0;

        foreach ($rules as $rule) {
            $result = $this->apply_rule($rule, $employeeId, $profile);

            if ($result['applied']) {
                $this->salaryComponent->save_component([
                    'employee_id' => $employeeId,
                    'period_start' => $periodStart,
                    'period_end' => $periodEnd,
                    'rule_id' => $rule['id'],
                    'rule_name' => $rule['name'],
                    'rule_type' => $rule['rule_type'],
                    'rule_group_type' => $rule['group_type'],
                    'calculated_value' => $result['value'],
                    'calculation_details' => json_encode($result['details'])
                ]);

                $this->appliedComponents[] = [
                    'name' => $rule['name'],
                    'type' => $rule['rule_type'],
                    'group_type' => $rule['group_type'],
                    'value' => $result['value'],
                    'details' => $result['details']
                ];

                if ($rule['group_type'] === 'earning') {
                    $totalEarnings += $result['value'];
                    $this->currentGross += $result['value'];
                } else {
                    $totalDeductions += $result['value'];
                    $this->currentGross -= $result['value'];
                }
            }
        }

        $netSalary = $totalEarnings - $totalDeductions;

        return [
            'success' => true,
            'employee_id' => $employeeId,
            'period_start' => $periodStart,
            'period_end' => $periodEnd,
            'basic_salary' => $this->basicSalary,
            'hourly_rate' => $this->hourlyRate,
            'attendance_data' => $this->attendanceData,
            'components' => $this->appliedComponents,
            'total_earnings' => $totalEarnings,
            'total_deductions' => $totalDeductions,
            'net_salary' => $netSalary
        ];
    }

    private function reset(): void
    {
        $this->basicSalary = 0;
        $this->hourlyRate = 0;
        $this->attendanceData = [];
        $this->appliedComponents = [];
        $this->currentGross = 0;
    }

    private function get_applicable_rules(int $employeeId, array $profile): array
    {
        $rules = $this->salaryRule->get_rules_for_employee(
            $employeeId,
            $profile['department_id'] ?? null,
            $profile['position_id'] ?? null
        );

        $employeeRules = $this->employeeSalaryRule->get_employee_rules($employeeId);
        $customValues = [];
        foreach ($employeeRules as $er) {
            if ($er['custom_value'] !== null) {
                $customValues[$er['rule_id']] = (float) $er['custom_value'];
            }
        }

        foreach ($rules as &$rule) {
            if (isset($customValues[$rule['id']])) {
                $rule['custom_value'] = $customValues[$rule['id']];
            }
        }

        return $rules;
    }

    private function apply_rule(array $rule, int $employeeId, array $profile): array
    {
        $value = 0;
        $applied = true;
        $details = [];

        switch ($rule['rule_type']) {
            case 'fixed':
                $value = $this->apply_fixed_rule($rule, $details);
                break;

            case 'percentage':
                $value = $this->apply_percentage_rule($rule, $details);
                break;

            case 'formula':
                $result = $this->apply_formula_rule($rule, $profile, $details);
                $value = $result['value'];
                $applied = $result['applied'];
                break;

            case 'conditional':
                $result = $this->apply_conditional_rule($rule, $profile, $details);
                $value = $result['value'];
                $applied = $result['applied'];
                break;
        }

        return [
            'applied' => $applied,
            'value' => round($value, 2),
            'details' => $details
        ];
    }

    private function apply_fixed_rule(array $rule, array &$details): float
    {
        $value = $rule['custom_value'] ?? (float) $rule['value'];
        $details['type'] = 'fixed';
        $details['base_value'] = $value;
        $details['description'] = "Fixed amount: " . number_format($value, 2);
        return $value;
    }

    private function apply_percentage_rule(array $rule, array &$details): float
    {
        $percentage = $rule['custom_value'] ?? (float) $rule['value'];
        $basedOn = $rule['based_on'] ?? 'none';

        $baseAmount = $this->get_base_amount($basedOn);
        $value = ($baseAmount * $percentage) / 100;

        $details['type'] = 'percentage';
        $details['percentage'] = $percentage;
        $details['base_amount'] = $baseAmount;
        $details['description'] = "{$percentage}% of " . ucfirst($basedOn) . " (" . number_format($baseAmount, 2) . ") = " . number_format($value, 2);

        return $value;
    }

    private function apply_formula_rule(array $rule, array $profile, array &$details): array
    {
        $formula = $rule['formula'] ?? '';
        if (empty($formula)) {
            return ['value' => 0, 'applied' => false];
        }

        $variables = $this->build_variables($profile);
        $result = $this->evaluate_formula($formula, $variables);

        $details['type'] = 'formula';
        $details['formula'] = $formula;
        $details['variables'] = $variables;
        $details['description'] = "Formula result: " . number_format($result['value'], 2);
        if (!empty($result['error'])) {
            $details['error'] = $result['error'];
        }

        return [
            'value' => $result['value'],
            'applied' => $result['value'] != 0 || empty($result['error'])
        ];
    }

    private function apply_conditional_rule(array $rule, array $profile, array &$details): array
    {
        $conditions = json_decode($rule['conditions'] ?? '[]', true);
        if (empty($conditions)) {
            return ['value' => 0, 'applied' => false];
        }

        $variables = $this->build_variables($profile);
        $conditionMet = $this->evaluate_conditions($conditions, $variables);

        $trueValue = $rule['custom_value'] ?? (float) ($rule['value'] ?? 0);
        $value = $conditionMet ? $trueValue : 0;

        $details['type'] = 'conditional';
        $details['conditions'] = $conditions;
        $details['condition_met'] = $conditionMet;
        $details['description'] = $conditionMet
            ? "Condition met, value: " . number_format($value, 2)
            : "Condition not met, value: 0";

        return ['value' => $value, 'applied' => true];
    }

    private function get_base_amount(string $basedOn): float
    {
        return match ($basedOn) {
            'basic' => $this->basicSalary,
            'gross' => $this->currentGross > 0 ? $this->currentGross : $this->basicSalary,
            'attendance' => $this->attendanceData['days_present'] ?? 0,
            default => $this->basicSalary
        };
    }

    private function build_variables(array $profile): array
    {
        return [
            'basic' => $this->basicSalary,
            'gross' => $this->currentGross > 0 ? $this->currentGross : $this->basicSalary,
            'hourly_rate' => $this->hourlyRate,
            'attendance' => $this->attendanceData['days_present'] ?? 0,
            'total_days' => $this->attendanceData['total_days'] ?? 0,
            'days_present' => $this->attendanceData['days_present'] ?? 0,
            'days_absent' => $this->attendanceData['days_absent'] ?? 0,
            'days_late' => $this->attendanceData['days_late'] ?? 0,
            'days_on_leave' => $this->attendanceData['days_on_leave'] ?? 0,
            'overtime_hours' => ($this->attendanceData['total_overtime_minutes'] ?? 0) / 60,
            'overtime_minutes' => $this->attendanceData['total_overtime_minutes'] ?? 0,
            'late_minutes' => $this->attendanceData['total_late_minutes'] ?? 0,
            'early_out_minutes' => $this->attendanceData['total_early_out_minutes'] ?? 0,
            'night_shift_hours' => $this->attendanceData['total_night_shift_hours'] ?? 0,
            'worked_hours' => $this->attendanceData['total_worked_hours'] ?? 0,
            'department_id' => $profile['department_id'] ?? 0,
            'position_id' => $profile['position_id'] ?? 0,
            'position' => $profile['position_name'] ?? '',
        ];
    }

    private function evaluate_formula(string $formula, array $variables): array
    {
        try {
            $expression = $this->replace_variables($formula, $variables);
            $result = $this->safe_eval($expression);
            return ['value' => (float) $result, 'error' => null];
        } catch (\Throwable $e) {
            return ['value' => 0, 'error' => $e->getMessage()];
        }
    }

    private function replace_variables(string $formula, array $variables): string
    {
        foreach ($variables as $name => $value) {
            $formula = str_replace($name, (string) $value, $formula);
        }
        return $formula;
    }

    private function safe_eval(string $expression): mixed
    {
        $sanitized = preg_replace('/[^0-9+\-*/().<>=!&|% ]/i', '', $expression);

        if (preg_match('/\bIF\s*\(/i', $sanitized)) {
            return $this->evaluate_if($sanitized);
        }

        return eval("return ({$sanitized});");
    }

    private function evaluate_if(string $expression): float
    {
        preg_match('/IF\s*\(\s*([^,]+)\s*,\s*([^,]+)\s*,\s*([^)]+)\s*\)/i', $expression, $matches);

        if (count($matches) < 4) {
            return 0;
        }

        $condition = trim($matches[1]);
        $trueValue = trim($matches[2]);
        $falseValue = trim($matches[3]);

        $conditionResult = $this->safe_eval($condition);
        $selectedValue = $conditionResult ? $trueValue : $falseValue;

        $sanitized = preg_replace('/[^0-9+\-*/(). ]/i', '', $selectedValue);
        return (float) eval("return ({$sanitized});");
    }

    private function evaluate_conditions(array $conditions, array $variables): bool
    {
        foreach ($conditions as $condition) {
            $field = $condition['field'] ?? '';
            $operator = $condition['operator'] ?? '=';
            $value = $condition['value'] ?? '';

            if (!isset($variables[$field])) {
                continue;
            }

            $fieldValue = $variables[$field];

            if (is_array($value)) {
                if ($operator === 'in' && !in_array($fieldValue, $value)) {
                    return false;
                }
                if ($operator === 'not_in' && in_array($fieldValue, $value)) {
                    return false;
                }
            } else {
                if (!$this->compare_values($fieldValue, $operator, $value)) {
                    return false;
                }
            }
        }

        return true;
    }

    private function compare_values(mixed $fieldValue, string $operator, mixed $compareValue): bool
    {
        $fieldValue = is_numeric($fieldValue) ? (float) $fieldValue : (string) $fieldValue;
        $compareValue = is_numeric($compareValue) ? (float) $compareValue : (string) $compareValue;

        return match ($operator) {
            '=', '==' => $fieldValue == $compareValue,
            '===' => $fieldValue === $compareValue,
            '!=' => $fieldValue != $compareValue,
            '>' => $fieldValue > $compareValue,
            '>=' => $fieldValue >= $compareValue,
            '<' => $fieldValue < $compareValue,
            '<=' => $fieldValue <= $compareValue,
            default => false
        };
    }

    public function calculate_bulk(string $periodStart, string $periodEnd, ?array $employeeIds = null): array
    {
        $results = [];

        if ($employeeIds === null) {
            $employees = $this->employee->get_all()->getResult();
            $employeeIds = array_map(fn($e) => $e->person_id, $employees);
        }

        foreach ($employeeIds as $employeeId) {
            $results[$employeeId] = $this->calculate($employeeId, $periodStart, $periodEnd);
        }

        return $results;
    }

    public function get_payslip(int $employeeId, string $periodStart, string $periodEnd): array
    {
        $calculation = $this->calculate($employeeId, $periodStart, $periodEnd);

        $employee = $this->employee->get_info($employeeId);
        $profile = $this->employeeProfile->get_info($employeeId);

        return [
            'employee' => $employee,
            'profile' => $profile,
            'period_start' => $periodStart,
            'period_end' => $periodEnd,
            'calculation' => $calculation
        ];
    }
}
