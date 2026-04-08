<?php

namespace App\Models\Hr;

use CodeIgniter\Model;

class LeaveRequest extends Model
{
    protected $table = 'leave_requests';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'employee_id', 'leave_type_id', 'start_date', 'end_date', 'total_days',
        'reason', 'status', 'approved_by', 'approved_at', 'rejection_reason',
        'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function get_employee_requests(int $employeeId, ?string $status = null): array
    {
        $builder = $this->select('ospos_leave_requests.*, ospos_leave_types.name as leave_type_name')
            ->join('ospos_leave_types', 'ospos_leave_types.id = ospos_leave_requests.leave_type_id', 'left')
            ->where('ospos_leave_requests.employee_id', $employeeId);

        if ($status) {
            $builder->where('ospos_leave_requests.status', $status);
        }

        return $builder->orderBy('ospos_leave_requests.created_at', 'DESC')->findAll();
    }

    public function get_pending_requests(): array
    {
        return $this->select('ospos_leave_requests.*, ospos_leave_types.name as leave_type_name,
                ospos_people.first_name, ospos_people.last_name')
            ->join('ospos_leave_types', 'ospos_leave_types.id = ospos_leave_requests.leave_type_id', 'left')
            ->join('ospos_people', 'ospos_people.person_id = ospos_leave_requests.employee_id', 'inner')
            ->where('ospos_leave_requests.status', 'pending')
            ->orderBy('ospos_leave_requests.created_at', 'ASC')
            ->findAll();
    }

    public function approve(int $requestId, int $approverId): bool
    {
        return $this->update($requestId, [
            'status' => 'approved',
            'approved_by' => $approverId,
            'approved_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function reject(int $requestId, string $reason): bool
    {
        return $this->update($requestId, [
            'status' => 'rejected',
            'rejection_reason' => $reason
        ]);
    }

    public function calculate_days(string $startDate, string $endDate): float
    {
        $start = new \DateTime($startDate);
        $end = new \DateTime($endDate);
        return (float) $start->diff($end)->days + 1;
    }
}
