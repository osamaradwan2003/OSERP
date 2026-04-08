<?php

namespace App\Models\Hr;

use CodeIgniter\Model;

class Shift extends Model
{
    protected $table = 'shifts';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'name', 'code', 'start_time', 'end_time', 'grace_period_minutes',
        'working_hours', 'overtime_threshold_minutes', 'night_shift_start',
        'night_shift_end', 'is_night_shift', 'is_active', 'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function get_all_active(): array
    {
        return $this->where('is_active', 1)->findAll();
    }

    public function get_options(): array
    {
        $result = [];
        foreach ($this->get_all_active() as $shift) {
            $result[$shift['id']] = $shift['name'] . ' (' . $shift['start_time'] . ' - ' . $shift['end_time'] . ')';
        }
        return $result;
    }

    public function get_simple_options(): array
    {
        $result = [];
        foreach ($this->get_all_active() as $shift) {
            $result[$shift['id']] = $shift['name'];
        }
        return $result;
    }

    public function get_by_code(string $code): ?array
    {
        return $this->where('code', $code)->first();
    }
}
