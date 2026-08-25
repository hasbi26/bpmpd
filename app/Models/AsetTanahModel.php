<?php

namespace App\Models;

use CodeIgniter\Model;

class AsetTanahModel extends Model
{
    protected $table            = 'aset_tanah';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'desa_id',
        'tanggal_rekap',
        'kode_barang',
        'nup',
        'nama_barang',
        'luas',
        'tahun_perolehan',
        'alas_hak',
        'nilai_perolehan',
        'keterangan',
        'foto',
    ];

    // created_at / updated_at sudah punya DEFAULT CURRENT_TIMESTAMP di level DB,
    // jadi useTimestamps dimatikan supaya tidak dobel-set dari CI4.
    protected $useTimestamps = false;

    protected $validationRules = [
        'desa_id'         => 'required|integer',
        'tanggal_rekap'   => 'required|valid_date[Y-m-d]',
        'nama_barang'     => 'required|max_length[255]',
        'luas'            => 'permit_empty|decimal',
        'tahun_perolehan' => 'permit_empty|integer',
        'nilai_perolehan' => 'permit_empty|decimal',
    ];

    protected $validationMessages = [
        'desa_id' => [
            'required' => 'Desa wajib diketahui sebelum menyimpan data aset.',
        ],
        'tanggal_rekap' => [
            'required'   => 'Tanggal rekap wajib diisi.',
            'valid_date' => 'Format tanggal rekap tidak valid (harus YYYY-MM-DD).',
        ],
        'nama_barang' => [
            'required' => 'Jenis tanah pada baris excel tidak boleh kosong.',
        ],
    ];

    /**
     * Ganti TOTAL seluruh data aset tanah milik satu desa dengan data baru
     * hasil import excel. Dibungkus 1 transaksi: kalau ada baris gagal
     * validasi, data LAMA tidak jadi terhapus (rollback).
     *
     * @param int   $desaId
     * @param array $rows    Array baris siap-insert (masing2 sesuai $allowedFields)
     * @return array{success:bool, inserted:int, errors:array}
     */
    public function replaceForDesa(int $desaId, array $rows): array
    {
        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Hapus semua data lama milik desa ini
        $this->where('desa_id', $desaId)->delete();

        // 2. Insert data baru
        $inserted = 0;
        $errors   = [];

        foreach ($rows as $i => $row) {
            $ok = $this->insert($row, false);
            if ($ok === false) {
                $errors[] = [
                    'baris' => $row['_excel_row'] ?? ($i + 2),
                    'pesan' => $this->errors(),
                ];
                continue;
            }
            $inserted++;
        }

        // Kalau ada satu saja baris gagal validasi, batalkan semuanya
        // (termasuk batal menghapus data lama)
        if (!empty($errors)) {
            $db->transRollback();
            return ['success' => false, 'inserted' => 0, 'errors' => $errors];
        }

        $db->transComplete();

        return [
            'success'  => $db->transStatus(),
            'inserted' => $inserted,
            'errors'   => [],
        ];
    }

    /**
     * Ambil semua data aset tanah milik satu desa (untuk export excel/pdf),
     * diurutkan berdasarkan kode barang lalu nama barang.
     */
    public function getByDesa(int $desaId): array
    {
        return $this->where('desa_id', $desaId)
            ->orderBy('kode_barang', 'ASC')
            ->orderBy('nama_barang', 'ASC')
            ->findAll();
    }
}