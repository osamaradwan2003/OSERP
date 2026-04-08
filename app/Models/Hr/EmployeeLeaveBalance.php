<?php

namespace App\Models\Hr;

use CodeIgniter\Model;

class EmployeeLeaveBalance extends Model
{
    protected $table = 'employee_leave_balances';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'employee_id', 'leave_type_id', 'year', 'entitled_days', 'used_days', 'pending_days',
        'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function get_balance(int $employeeId, int $leaveTypeId, int $year): ?array
    {
        return $this->where('employee_id', $employeeId)
            ->where('leave_type_id', $leaveTypeId)
            ->where('year', $year)
            ->first();
    }

    public function get_all_balances(int $employeeId, int $year): array
    {
        return $this->select('ospos_employee_leave_balances.*, ospos_leave_types.name as leave_type_name,
                ospos_leave_types.default_days, ospos_leave_types.paid_unpaid')
            ->join('ospos_leave_types', 'ospos_leave_types.id = ospos_employee_leave_balances.leave_type_id', 'left')
            ->where('ospos_employee_leave_balances.employee_id', $employeeId)
            ->where('ospos_employee_leave_balances.year', $year)
            ->findAll();
    }

    public function initialize_balance(int $employeeId, int $leaveTypeId, int $year, float $entitledDays): bool
    {
        $existing = $this->get_balance($employeeId, $leaveTypeId, $year);

        if ($existing) {
            return true;
        }

        return (bool) $this->insert([
            'employee_id' => $employeeId,
            'leave_type_id' => $leaveTypeId,
            'year' => $year,
            'entitled_days' => $entitledDays,
            'used_days' => 0,
            'pending_days' => 0
        ]);
    }

    public function update_balance(int $employeeId, int $leaveTypeId, int $year, float $used, float $pending = 0): bool
    {
        $existing = $this->get_balance($employeeId, $leaveTypeId, $year);

        if ($existing) {
            return $this->update($existing['id'], [
                'used_days' => $existing['used_days'] + $used,
                'pending_days' => $existing['pending_days'] + $pending
            ]);
        }

        return false;
    }

    public function get_available_days(int $employeeId, int $leaveTypeId, int $year): float
    {
        $balance = $this->get_balance($employeeId, $leaveTypeId, $year);

        if (!$balance) {
            return 0;
        }

        return (float) $balance['entitled_days'] - $balance['used_days'] - $balance['pending_days'];
    }
}
