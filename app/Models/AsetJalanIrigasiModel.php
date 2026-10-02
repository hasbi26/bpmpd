<?php

namespace App\Models;

use CodeIgniter\Model;

class AsetJalanIrigasiModel extends Model
{
    protected $table            = 'aset_jalan_irigasi';
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
        'type_bangunan',
        'nilai_perolehan',
        'keterangan',
        'foto',
    ];

    // created_at / updated_at sudah punya DEFAULT CURRENT_TIMESTAMP di level DB.
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
            'required' => 'Jenis jalan/irigasi pada baris excel tidak boleh kosong.',
        ],
    ];

    /**
     * Ganti TOTAL seluruh data aset jalan/irigasi milik satu desa dengan
     * data baru hasil import excel. Dibungkus 1 transaksi: kalau ada baris
     * gagal validasi, data LAMA tidak jadi terhapus (rollback).
     */
    public function replaceForDesa(int $desaId, array $rows): array
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $this->where('desa_id', $desaId)->delete();

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
     * Ambil semua data aset jalan/irigasi milik satu desa.
     */
    public function getByDesa(int $desaId): array
    {
        return $this->where('desa_id', $desaId)
            ->orderBy('kode_barang', 'ASC')
            ->orderBy('nama_barang', 'ASC')
            ->findAll();
    }

    /**
     * Ambil data aset jalan/irigasi untuk SEMUA desa di bawah satu
     * kecamatan, opsional difilter ke satu desa tertentu.
     */
    public function getByKecamatan(int $kecamatanId, ?int $desaId = null): array
    {
        $builder = \Config\Database::connect()
            ->table('aset_jalan_irigasi aji')
            ->select('aji.*, d.nama as nama_desa')
            ->join('desa d', 'd.id = aji.desa_id')
            ->where('d.kecamatan_id', $kecamatanId);

        if (!empty($desaId)) {
            $builder->where('aji.desa_id', $desaId);
        }

        return $builder->orderBy('d.nama', 'ASC')
            ->orderBy('aji.kode_barang', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Ambil data aset jalan/irigasi untuk SEMUA kecamatan & desa di bawah
     * satu kabupaten, opsional difilter ke satu kecamatan dan/atau satu desa.
     */
    public function getByKabupaten(int $kabupatenId, ?int $kecamatanId = null, ?int $desaId = null): array
    {
        $builder = \Config\Database::connect()
            ->table('aset_jalan_irigasi aji')
            ->select('aji.*, d.nama as nama_desa, k.nama as nama_kecamatan')
            ->join('desa d', 'd.id = aji.desa_id')
            ->join('kecamatan k', 'k.id = d.kecamatan_id')
            ->where('k.kabupaten_id', $kabupatenId);

        if (!empty($kecamatanId)) {
            $builder->where('d.kecamatan_id', $kecamatanId);
        }

        if (!empty($desaId)) {
            $builder->where('aji.desa_id', $desaId);
        }

        return $builder->orderBy('k.nama', 'ASC')
            ->orderBy('d.nama', 'ASC')
            ->orderBy('aji.kode_barang', 'ASC')
            ->get()
            ->getResultArray();
    }
}