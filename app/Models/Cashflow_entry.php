<?php

namespace App\Models;

use CodeIgniter\Model;

class Cashflow_entry extends Model
{
    protected $table = 'cashflow_entries';
    protected $primaryKey = 'entry_id';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes = false;
    protected $returnType = 'array';
    protected $allowedFields = [
        'entry_date',
        'entry_type',
        'category_id',
        'amount',
        'description',
        'status',
        'account_id',
        'from_account_id',
        'to_account_id',
        'customer_id',
        'supplier_id',
        'sale_id',
        'sale_payment_id',
        'receiving_id',
        'created_by',
        'deleted',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
