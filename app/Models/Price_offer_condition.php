<?php

namespace App\Models;

use CodeIgniter\Model;

class Price_offer_condition extends Model
{
    protected $table = 'price_offer_conditions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'name',
        'title',
        'description',
        'sort',
        'is_active'
    ];
}
