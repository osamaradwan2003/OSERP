<?php

namespace App\Models\Hr;

use CodeIgniter\Model;

class EmployeeProfile extends Model
{
    protected $table = 'employee_profiles';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'employee_id', 'department_id', 'position_id', 'shift_id', 'employee_number',
        'basic_salary', 'hourly_rate', 'hire_date', 'termination_date',
        'employment_type', 'employment_status', 'bank_name', 'bank_account',
        'tax_id', 'social_security_number', 'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function get_info(int $employeeId): ?array
    {
        return $this->select('ospos_employee_profiles.*,
                ospos_departments.name as department_name,
                ospos_positions.name as position_name,
                ospos_shifts.name as shift_name,
                ospos_shifts.start_time as shift_start_time,
                ospos_shifts.end_time as shift_end_time,
                ospos_shifts.working_hours as shift_working_hours')
            ->join('ospos_departments', 'ospos_departments.id = ospos_employee_profiles.department_id', 'left')
            ->join('ospos_positions', 'ospos_positions.id = ospos_employee_profiles.position_id', 'left')
            ->join('ospos_shifts', 'ospos_shifts.id = ospos_employee_profiles.shift_id', 'left')
            ->where('ospos_employee_profiles.employee_id', $employeeId)
            ->first();
    }

    public function get_all_with_details(): array
    {
        return $this->select('ospos_employee_profiles.*,
                ospos_people.first_name, ospos_people.last_name, ospos_people.email,
                ospos_departments.name as department_name,
                ospos_positions.name as position_name,
                ospos_shifts.name as shift_name')
            ->join('ospos_employees', 'ospos_employees.person_id = ospos_employee_profiles.employee_id', 'inner')
            ->join('ospos_people', 'ospos_people.person_id = ospos_employee_profiles.employee_id', 'inner')
            ->join('ospos_departments', 'ospos_departments.id = ospos_employee_profiles.department_id', 'left')
            ->join('ospos_positions', 'ospos_positions.id = ospos_employee_profiles.position_id', 'left')
            ->join('ospos_shifts', 'ospos_shifts.id = ospos_employee_profiles.shift_id', 'left')
            ->where('ospos_employees.deleted', 0)
            ->findAll();
    }

    public function save_profile(array $data, ?int $profileId = null): bool
    {
        if ($profileId) {
            return $this->update($profileId, $data);
        }
        return (bool) $this->insert($data);
    }

    public function exists(int $employeeId): bool
    {
        return (bool) $this->where('employee_id', $employeeId)->first();
    }
}
