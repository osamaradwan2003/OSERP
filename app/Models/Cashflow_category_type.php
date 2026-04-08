<?php

namespace App\Models;

use CodeIgniter\Model;

class Cashflow_category_type extends Model
{
    protected $table = 'cashflow_category_types';
    protected $primaryKey = 'type_code';
    protected $useAutoIncrement = false;
    protected $useSoftDeletes = false;
    protected $returnType = 'array';
    protected $allowedFields = [
        'type_code',
        'type_label',
        'calc_method',
        'is_active',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getActiveOptions(): array
    {
        $rows = $this->where('is_active', 1)->findAll();
        $options = [];
        foreach ($rows as $row) {
            $options[$row['type_code']] = $row['type_label'];
        }

        return $options;
    }

    public function getActiveTypeMap(): array
    {
        $rows = $this->where('is_active', 1)->findAll();
        $map = [];
        foreach ($rows as $row) {
            $map[$row['type_code']] = [
                'label' => $row['type_label'],
                'calc_method' => $row['calc_method']
            ];
        }

        return $map;
    }

    public function getCalcMethodMap(): array
    {
        $rows = $this->where('is_active', 1)->findAll();
        $map = [];
        foreach ($rows as $row) {
            $map[$row['type_code']] = $row['calc_method'];
        }

        return $map;
    }
}
