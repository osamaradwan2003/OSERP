<?php

namespace App\Models\Hr;

use CodeIgniter\Model;

class LeaveType extends Model
{
    protected $table = 'leave_types';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'name', 'code', 'paid_unpaid', 'default_days', 'is_active', 'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function get_all_active(): array
    {
        return $this->db->table('leave_types')
            ->select('id, name, code, paid_unpaid, default_days, is_active, created_at, updated_at')
            ->where('is_active', 1)
            ->orderBy('name', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function get_options(): array
    {
        $result = [];
        foreach ($this->get_all_active() as $type) {
            $result[$type['id']] = $type['name'] . ' (' . ($type['paid_unpaid'] === 'paid' ? 'Paid' : 'Unpaid') . ')';
        }
        return $result;
    }

    public function get_paid_types(): array
    {
        return $this->db->table('leave_types')
            ->select('id, name, code, paid_unpaid, default_days, is_active, created_at, updated_at')
            ->where('is_active', 1)
            ->where('paid_unpaid', 'paid')
            ->get()
            ->getResultArray();
    }
}
