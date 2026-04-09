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
        return $this->db->table('employee_profiles ep')
            ->select('ep.*,
                d.name as department_name,
                p.name as position_name,
                s.name as shift_name,
                s.start_time as shift_start_time,
                s.end_time as shift_end_time,
                s.working_hours as shift_working_hours')
            ->join('departments d', 'd.id = ep.department_id', 'left')
            ->join('positions p', 'p.id = ep.position_id', 'left')
            ->join('shifts s', 's.id = ep.shift_id', 'left')
            ->where('ep.employee_id', $employeeId)
            ->get()
            ->getRowArray();
    }

    public function get_all_with_details(): array
    {
        return $this->db->table('employee_profiles ep')
            ->select('ep.id, ep.employee_id, ep.department_id, ep.position_id, ep.shift_id, ep.employee_number,
                ep.basic_salary, ep.hourly_rate, ep.hire_date, ep.termination_date,
                ep.employment_type, ep.employment_status, ep.bank_name, ep.bank_account,
                ep.tax_id, ep.social_security_number, ep.created_at, ep.updated_at,
                people.first_name, people.last_name, people.email,
                d.name as department_name,
                pos.name as position_name,
                s.name as shift_name')
            ->join('employees e', 'e.person_id = ep.employee_id', 'inner')
            ->join('people', 'people.person_id = ep.employee_id', 'inner')
            ->join('departments d', 'd.id = ep.department_id', 'left')
            ->join('positions pos', 'pos.id = ep.position_id', 'left')
            ->join('shifts s', 's.id = ep.shift_id', 'left')
            ->where('e.deleted', 0)
            ->get()
            ->getResultArray();
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
