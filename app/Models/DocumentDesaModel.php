<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentDesaModel extends Model
{
    protected $table = 'document_desa';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'desa_id',
        'nama',
        'path',
        'status',
        'keterangan',
        'is_active',
        'created_at',
        'created_by',
        'updated_at',
    ];
}
