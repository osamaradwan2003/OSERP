<?php

namespace App\Models\Hr;

use CodeIgniter\Model;

class Attendance extends Model
{
    protected $table = 'attendance';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'employee_id', 'date', 'clock_in', 'clock_out', 'scheduled_start', 'scheduled_end',
        'status', 'late_minutes', 'overtime_minutes', 'early_out_minutes', 'night_shift_hours',
        'worked_hours', 'notes', 'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function get_by_employee(int $employeeId, ?string $startDate = null, ?string $endDate = null): array
    {
        $builder = $this->where('employee_id', $employeeId);

        if ($startDate) {
            $builder->where('date >=', $startDate);
        }
        if ($endDate) {
            $builder->where('date <=', $endDate);
        }

        return $builder->orderBy('date', 'DESC')->findAll();
    }

    public function get_by_date(string $date): array
    {
        return $this->select('ospos_attendance.*, ospos_people.first_name, ospos_people.last_name')
            ->join('ospos_people', 'ospos_people.person_id = ospos_attendance.employee_id', 'inner')
            ->where('date', $date)
            ->findAll();
    }

    public function get_summary(int $employeeId, string $startDate, string $endDate): array
    {
        $result = $this->select('
                COUNT(*) as total_days,
                SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as days_present,
                SUM(CASE WHEN status = "absent" THEN 1 ELSE 0 END) as days_absent,
                SUM(CASE WHEN status = "late" THEN 1 ELSE 0 END) as days_late,
                SUM(CASE WHEN status = "on_leave" THEN 1 ELSE 0 END) as days_on_leave,
                SUM(worked_hours) as total_worked_hours,
                SUM(overtime_minutes) as total_overtime_minutes,
                SUM(late_minutes) as total_late_minutes,
                SUM(early_out_minutes) as total_early_out_minutes,
                SUM(night_shift_hours) as total_night_shift_hours
            ')
            ->where('employee_id', $employeeId)
            ->where('date >=', $startDate)
            ->where('date <=', $endDate)
            ->first();

        return $result ?: [
            'total_days' => 0, 'days_present' => 0, 'days_absent' => 0, 'days_late' => 0,
            'days_on_leave' => 0, 'total_worked_hours' => 0, 'total_overtime_minutes' => 0,
            'total_late_minutes' => 0, 'total_early_out_minutes' => 0, 'total_night_shift_hours' => 0
        ];
    }

    public function record_attendance(int $employeeId, string $date, array $data): bool
    {
        $existing = $this->where('employee_id', $employeeId)->where('date', $date)->first();

        $data['employee_id'] = $employeeId;
        $data['date'] = $date;

        if ($existing) {
            return $this->update($existing['id'], $data);
        }

        return (bool) $this->insert($data);
    }

    public function clock_in(int $employeeId, string $date, string $time): bool
    {
        $data = [
            'clock_in' => $date . ' ' . $time,
            'status' => 'present'
        ];

        return $this->record_attendance($employeeId, $date, $data);
    }

    public function clock_out(int $employeeId, string $date, string $time): bool
    {
        $existing = $this->where('employee_id', $employeeId)->where('date', $date)->first();

        if (!$existing) {
            return false;
        }

        $clockOut = $date . ' ' . $time;
        $clockIn = $existing['clock_in'];

        if ($clockIn) {
            $start = new \DateTime($clockIn);
            $end = new \DateTime($clockOut);
            $workedHours = $end->diff($start)->h + ($end->diff($start)->i / 60);

            $data = [
                'clock_out' => $clockOut,
                'worked_hours' => $workedHours
            ];

            if (isset($existing['scheduled_end'])) {
                $scheduledEnd = new \DateTime($date . ' ' . $existing['scheduled_end']);
                if ($end > $scheduledEnd) {
                    $diff = $end->diff($scheduledEnd);
                    $data['overtime_minutes'] = $diff->h * 60 + $diff->i;
                }
            }

            return $this->update($existing['id'], $data);
        }

        return $this->update($existing['id'], ['clock_out' => $clockOut]);
    }

    public function has_record(int $employeeId, string $date): bool
    {
        return (bool) $this->where('employee_id', $employeeId)->where('date', $date)->first();
    }
}
