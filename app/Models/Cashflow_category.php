<?php

namespace App\Models;

use CodeIgniter\Model;

class Cashflow_category extends Model
{
    protected $table = 'cashflow_categories';
    protected $primaryKey = 'category_id';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes = false;
    protected $returnType = 'array';
    protected $allowedFields = [
        'name',
        'entry_type',
        'is_active',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getActiveOptions(?string $entryType = null): array
    {
        $builder = $this->where('is_active', 1)->orderBy('name', 'ASC');
        if ($entryType !== null && $entryType !== '') {
            $builder->where('entry_type', $entryType);
        }

        $rows = $builder->findAll();
        $options = [];
        foreach ($rows as $row) {
            $options[(int) $row['category_id']] = $row['name'];
        }

        return $options;
    }
}

