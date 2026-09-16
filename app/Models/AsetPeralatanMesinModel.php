<?php

namespace App\Models;

use CodeIgniter\Model;

class AsetPeralatanMesinModel extends Model
{
    protected $table            = 'aset_peralatan_mesin';
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
        'merk_tipe',
        'tahun_perolehan',
        'nilai_perolehan',
        'kondisi',
        'keterangan',
        'foto',
    ];

    // created_at / updated_at sudah punya DEFAULT CURRENT_TIMESTAMP di level DB.
    protected $useTimestamps = false;

    protected $validationRules = [
        'desa_id'         => 'required|integer',
        'tanggal_rekap'   => 'required|valid_date[Y-m-d]',
        'nama_barang'     => 'required|max_length[255]',
        'tahun_perolehan' => 'permit_empty|integer',
        'nilai_perolehan' => 'permit_empty|decimal',
        'kondisi'         => 'permit_empty|in_list[B,RR,RB]',
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
            'required' => 'Jenis peralatan/mesin pada baris excel tidak boleh kosong.',
        ],
        'kondisi' => [
            'in_list' => 'Kondisi barang harus salah satu dari: B, RR, RB.',
        ],
    ];

    /**
     * Ganti TOTAL seluruh data aset peralatan/mesin milik satu desa dengan
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
     * Ambil semua data aset peralatan/mesin milik satu desa.
     */
    public function getByDesa(int $desaId): array
    {
        return $this->where('desa_id', $desaId)
            ->orderBy('kode_barang', 'ASC')
            ->orderBy('nama_barang', 'ASC')
            ->findAll();
    }

    /**
     * Ambil data aset peralatan/mesin untuk SEMUA desa di bawah satu
     * kecamatan, opsional difilter ke satu desa tertentu.
     */
    public function getByKecamatan(int $kecamatanId, ?int $desaId = null): array
    {
        $builder = \Config\Database::connect()
            ->table('aset_peralatan_mesin apm')
            ->select('apm.*, d.nama as nama_desa')
            ->join('desa d', 'd.id = apm.desa_id')
            ->where('d.kecamatan_id', $kecamatanId);

        if (!empty($desaId)) {
            $builder->where('apm.desa_id', $desaId);
        }

        return $builder->orderBy('d.nama', 'ASC')
            ->orderBy('apm.kode_barang', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Ambil data aset peralatan/mesin untuk SEMUA kecamatan & desa di bawah
     * satu kabupaten, opsional difilter ke satu kecamatan dan/atau satu desa.
     */
    public function getByKabupaten(int $kabupatenId, ?int $kecamatanId = null, ?int $desaId = null): array
    {
        $builder = \Config\Database::connect()
            ->table('aset_peralatan_mesin apm')
            ->select('apm.*, d.nama as nama_desa, k.nama as nama_kecamatan')
            ->join('desa d', 'd.id = apm.desa_id')
            ->join('kecamatan k', 'k.id = d.kecamatan_id')
            ->where('k.kabupaten_id', $kabupatenId);

        if (!empty($kecamatanId)) {
            $builder->where('d.kecamatan_id', $kecamatanId);
        }

        if (!empty($desaId)) {
            $builder->where('apm.desa_id', $desaId);
        }

        return $builder->orderBy('k.nama', 'ASC')
            ->orderBy('d.nama', 'ASC')
            ->orderBy('apm.kode_barang', 'ASC')
            ->get()
            ->getResultArray();
    }
}
