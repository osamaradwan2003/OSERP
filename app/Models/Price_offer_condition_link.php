<?php

namespace App\Models;

use CodeIgniter\Model;

class Price_offer_condition_link extends Model
{
    protected $table = 'price_offer_condition_links';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'sale_id',
        'condition_id'
    ];
}
