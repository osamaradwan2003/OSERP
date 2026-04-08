<?php

namespace App\Models\Hr;

use CodeIgniter\Model;

class EmployeeShift extends Model
{
    protected $table = 'employee_shifts';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'employee_id', 'shift_id', 'effective_from', 'effective_to', 'is_active',
        'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function get_employee_shifts(int $employeeId): array
    {
        return $this->select('ospos_employee_shifts.*, ospos_shifts.name as shift_name,
                ospos_shifts.start_time, ospos_shifts.end_time, ospos_shifts.working_hours')
            ->join('ospos_shifts', 'ospos_shifts.id = ospos_employee_shifts.shift_id', 'left')
            ->where('ospos_employee_shifts.employee_id', $employeeId)
            ->where('ospos_employee_shifts.is_active', 1)
            ->orderBy('ospos_employee_shifts.effective_from', 'DESC')
            ->findAll();
    }

    public function get_current_shift(int $employeeId): ?array
    {
        $today = date('Y-m-d');
        return $this->select('ospos_employee_shifts.*, ospos_shifts.name as shift_name,
                ospos_shifts.start_time, ospos_shifts.end_time, ospos_shifts.working_hours,
                ospos_shifts.grace_period_minutes, ospos_shifts.night_shift_start, ospos_shifts.night_shift_end,
                ospos_shifts.is_night_shift, ospos_shifts.overtime_threshold_minutes')
            ->join('ospos_shifts', 'ospos_shifts.id = ospos_employee_shifts.shift_id', 'left')
            ->where('ospos_employee_shifts.employee_id', $employeeId)
            ->where('ospos_employee_shifts.is_active', 1)
            ->where('ospos_employee_shifts.effective_from <=', $today)
            ->groupStart()
            ->where('ospos_employee_shifts.effective_to IS NULL')
            ->orWhere('ospos_employee_shifts.effective_to >=', $today)
            ->groupEnd()
            ->orderBy('ospos_employee_shifts.effective_from', 'DESC')
            ->limit(1)
            ->first();
    }

    public function assign_shift(int $employeeId, int $shiftId, string $effectiveFrom, ?string $effectiveTo = null): bool
    {
        $this->where('employee_id', $employeeId)
            ->where('is_active', 1)
            ->where('effective_to IS NULL')
            ->update([
                'effective_to' => date('Y-m-d', strtotime('-1 day', strtotime($effectiveFrom))),
                'is_active' => 0
            ]);

        return (bool) $this->insert([
            'employee_id' => $employeeId,
            'shift_id' => $shiftId,
            'effective_from' => $effectiveFrom,
            'effective_to' => $effectiveTo,
            'is_active' => 1
        ]);
    }

    public function get_shift_for_date(int $employeeId, string $date): ?array
    {
        return $this->select('ospos_employee_shifts.*, ospos_shifts.name as shift_name,
                ospos_shifts.start_time, ospos_shifts.end_time, ospos_shifts.working_hours,
                ospos_shifts.grace_period_minutes, ospos_shifts.night_shift_start, ospos_shifts.night_shift_end,
                ospos_shifts.is_night_shift, ospos_shifts.overtime_threshold_minutes')
            ->join('ospos_shifts', 'ospos_shifts.id = ospos_employee_shifts.shift_id', 'left')
            ->where('ospos_employee_shifts.employee_id', $employeeId)
            ->where('ospos_employee_shifts.is_active', 1)
            ->where('ospos_employee_shifts.effective_from <=', $date)
            ->groupStart()
            ->where('ospos_employee_shifts.effective_to IS NULL')
            ->orWhere('ospos_employee_shifts.effective_to >=', $date)
            ->groupEnd()
            ->limit(1)
            ->first();
    }
}
