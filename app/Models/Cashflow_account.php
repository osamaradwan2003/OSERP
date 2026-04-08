<?php

namespace App\Models;

use CodeIgniter\Model;

class Cashflow_account extends Model
{
    protected $table = 'cashflow_accounts';
    protected $primaryKey = 'account_id';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes = false;
    protected $returnType = 'array';
    protected $allowedFields = [
        'name',
        'type',
        'opening_balance',
        'is_active',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getActiveOptions(): array
    {
        $rows = $this->where('is_active', 1)->orderBy('name', 'ASC')->findAll();
        $options = [];
        foreach ($rows as $row) {
            $options[$row['account_id']] = $row['name'];
        }

        return $options;
    }
}
