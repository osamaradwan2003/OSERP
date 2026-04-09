<?php

namespace App\Models\Hr;

use CodeIgniter\Model;

class Department extends Model
{
    protected $table = 'departments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'name', 'description', 'parent_id', 'is_active', 'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function get_all_active(): array
    {
        return $this->where('is_active', 1)->findAll();
    }

    public function get_with_parents(): array
    {
        return $this->db->table('departments d1')
            ->select('d1.id, d1.name, d1.description, d1.parent_id, d1.is_active, d1.created_at, d1.updated_at, d2.name as parent_name')
            ->join('departments d2', 'd2.id = d1.parent_id', 'left')
            ->where('d1.is_active', 1)
            ->orderBy('d1.name', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function get_options(): array
    {
        $result = [];
        foreach ($this->get_all_active() as $dept) {
            $result[$dept['id']] = $dept['name'];
        }
        return $result;
    }
}
