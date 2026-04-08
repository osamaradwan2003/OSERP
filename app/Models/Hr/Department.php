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

    public function get_with_children(): array
    {
        $departments = $this->where('is_active', 1)->findAll();
        return $this->buildTree($departments);
    }

    private function buildTree(array $departments, ?int $parentId = null): array
    {
        $tree = [];
        foreach ($departments as $dept) {
            if ($dept['parent_id'] === $parentId) {
                $children = $this->buildTree($departments, $dept['id']);
                if ($children) {
                    $dept['children'] = $children;
                }
                $tree[] = $dept;
            }
        }
        return $tree;
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
