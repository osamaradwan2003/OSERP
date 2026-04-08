<?php

namespace App\Models;

use CodeIgniter\Model;

class Cashflow_attachment extends Model
{
    protected $table = 'cashflow_attachments';
    protected $primaryKey = 'attachment_id';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes = false;
    protected $returnType = 'array';
    protected $allowedFields = [
        'entry_id',
        'file_name',
        'file_path',
        'mime_type',
        'size',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
