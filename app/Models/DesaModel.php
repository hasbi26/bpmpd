<?php

namespace App\Models;

use CodeIgniter\Model;

class DesaModel extends Model
{
    protected $table = 'desa';
    protected $primaryKey = 'id';
    protected $allowedFields = ['kecamatan_id', 'nama', 'is_active', 'email', 'is_active', 'created_by','created_at' ,'updated_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}