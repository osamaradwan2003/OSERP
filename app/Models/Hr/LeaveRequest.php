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

    public function get_all_with_details(): array
    {
        return $this->db->table('leave_requests lr')
            ->select('lr.id, lr.employee_id, lr.leave_type_id, lr.start_date, lr.end_date, lr.total_days,
                lr.reason, lr.status, lr.approved_by, lr.approved_at, lr.rejection_reason,
                lr.created_at, lr.updated_at,
                lt.name as leave_type_name,
                p.first_name, p.last_name')
            ->join('leave_types lt', 'lt.id = lr.leave_type_id', 'left')
            ->join('people p', 'p.person_id = lr.employee_id', 'inner')
            ->orderBy('lr.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function get_employee_requests(int $employeeId, ?string $status = null): array
    {
        return $this->db->table('leave_requests lr')
            ->select('lr.id, lr.employee_id, lr.leave_type_id, lr.start_date, lr.end_date, lr.total_days,
                lr.reason, lr.status, lr.approved_by, lr.approved_at, lr.rejection_reason,
                lr.created_at, lr.updated_at,
                lt.name as leave_type_name')
            ->join('leave_types lt', 'lt.id = lr.leave_type_id', 'left')
            ->where('lr.employee_id', $employeeId)
            ->where($status ? ['lr.status' => $status] : '1=1')
            ->orderBy('lr.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function get_pending_requests(): array
    {
        return $this->db->table('leave_requests lr')
            ->select('lr.id, lr.employee_id, lr.leave_type_id, lr.start_date, lr.end_date, lr.total_days,
                lr.reason, lr.status, lr.approved_by, lr.approved_at, lr.rejection_reason,
                lr.created_at, lr.updated_at,
                lt.name as leave_type_name,
                p.first_name, p.last_name')
            ->join('leave_types lt', 'lt.id = lr.leave_type_id', 'left')
            ->join('people p', 'p.person_id = lr.employee_id', 'inner')
            ->where('lr.status', 'pending')
            ->orderBy('lr.created_at', 'ASC')
            ->get()
            ->getResultArray();
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
