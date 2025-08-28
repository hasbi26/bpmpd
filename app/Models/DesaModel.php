<?php

namespace App\Models;

use CodeIgniter\Model;

class DesaModel extends Model
{
    protected $table = 'desa';
    protected $primaryKey = 'id';
    protected $allowedFields = ['kecamatan_id', 'nama', 'is_active', 'email', 'is_active', 'created_by','created_at' ,'updated_at', 'alamat_desa','no_rekening', 'kepala_desa'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
 
 
 
    public function getProfilDesa($id)
    {
    return $this->select('kepala_desa, no_rekening, alamat_desa')
                ->where('id', $id)
                ->first();
    }

    public function getAllProfilDesa($search = null, $length = 10, $page = 1)
    {
    $builder = $this->select('desa.id, desa.nama AS nama_desa, desa.kepala_desa, desa.no_rekening, desa.alamat_desa, kecamatan.nama AS nama_kecamatan')
                    ->join('kecamatan', 'kecamatan.id = desa.kecamatan_id', 'left')
                    ->where('desa.is_active', 1);

    // Search filter
    if (!empty($search)) {
        $builder->groupStart()
                ->like('desa.nama', $search)
                ->orLike('desa.kepala_desa', $search)
                ->orLike('kecamatan.nama', $search)
                ->groupEnd();
    }

    // Pagination
    $offset = ($page - 1) * $length;

    $data = $builder->orderBy('desa.nama', 'ASC')
                    ->findAll($length, $offset);

    // Hitung total (untuk pagination)
    $total = $this->join('kecamatan', 'kecamatan.id = desa.kecamatan_id', 'left')
                  ->where('desa.is_active', 1)
                  ->countAllResults();

    return [
        'data'  => $data,
        'total' => $total,
        'page'  => $page,
        'length'=> $length
    ];
    }

}


