<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DocumentTemplatesDesaModel;

use App\Models\DocumentSubmissionsModel;
use App\Models\DocumentSubmissionFilesModel;
use App\Models\DocumentDesaModel;
use App\Models\DesaModel;
use Config\Database;


class DesaController extends BaseController
{
    protected $templateDesaModel;

    public function __construct()
    {
        helper(['auth']);
        $this->templateDesaModel = new DocumentTemplatesDesaModel();
        $this->documentModel = new DocumentDesaModel();
        $this->db = Database::connect();
        $this->session = \Config\Services::session();
    }



   public function updateProfil(){

    $id_desa = $this->request->getPost('id_desa');
    $namaKepalaDesa = $this->request->getPost('namaKepalaDesa');
    $alamatDesa = $this->request->getPost('alamatDesa');
    $rekeningDesa = $this->request->getPost('rekeningDesa');

    $desaModel = new \App\Models\DesaModel();

    $role = $this->session->get('role');


    // Update ke tabel
    $update = $desaModel->update($id_desa, [
        'kepala_desa' => $namaKepalaDesa,
        'alamat_desa' => $alamatDesa,
        'no_rekening' => $rekeningDesa,
        'updated_at'  => date('Y-m-d H:i:s'),
        // 'updated_by'  => user_id() ?? null, // opsional kalau kamu simpan user yg update
    ]);

    if ($update) {
        return redirect()->to("{$role}/dashboard")->with('success', 'Update Berhasil');
    } else {
        return redirect()->to("{$role}/dashboard")->with('error', 'Update Gagal');
    }

   }

    public function dashboard()
    {
        // Cek apakah user sudah login dan role-nya desa
        if (!logged_in() || user()->role !== 'desa') {
            return redirect()->to('/login/desa')->with('error', 'Silakan login sebagai desa');
        }

        $data = [
            'title' => 'Dashboard Desa',
            'user' => user() // helper auth yang mengembalikan data user
        ];

        return view('desa/dashboard', $data);
    }

    public function getDataStatus()
    {
        $page    = (int) ($this->request->getGet('page')   ?? 1);
        $length  = (int) ($this->request->getGet('length') ?? 10);
        $search  = trim((string) $this->request->getGet('search'));
    
        if ($page < 1)   $page = 1;
        if ($length < 1) $length = 10;
    
        // Ambil desa_id dari session
        $desaId   = session('role_id') ?? null;
    
        if (!$desaId) {
            return $this->response->setStatusCode(401)->setJSON([
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => [],
                'message'         => 'Desa tidak teridentifikasi di session.'
            ]);
        }
    
        $db = \Config\Database::connect();
        $builder = $db->table('document_submissions ds');
        $builder->select('
                ds.id,
                ds.earmarked,
                ds.non_earmarked,
                ds.status_desa,
                ds.status_kecamatan,
                ds.keterangan_kecamatan,
                ds.status_kabupaten,
                ds.created_at,
                dt.title
            ')
            ->join('document_templates dt', 'dt.id = ds.template_id')
            ->where('ds.desa_id', $desaId);
    
        if (!empty($search)) {
            $builder->groupStart()
                    ->like('dt.title', $search)
                    ->orLike('ds.keterangan_kecamatan', $search)
                    ->groupEnd();
        }
    
        // total setelah filter
        $countBuilder = clone $builder;
        $total = (int) $countBuilder->countAllResults();
    
        // pagination
        $offset = ($page - 1) * $length;
        $rows = $builder->orderBy('ds.created_at', 'DESC')
                        ->limit($length, $offset)
                        ->get()
                        ->getResultArray();
    
        // Format hasil agar rapi ke frontend
        $data = [];
        $no = $offset + 1;
        foreach ($rows as $row) {
            $data[] = [
                'id'           => $row['id'],
                'no'           => $no++,
                'tanggal'      => date('d-m-Y H:i', strtotime($row['created_at'])),
                'title'        => $row['title'],
                'earmarked'    => $row['earmarked'] ?? '-',
                'non_earmarked'=> $row['non_earmarked'] ?? '-',
                'status_desa'   => $row['status_desa'], // atau kombinasikan dgn kec/kab
                'status_kecamatan' => $row['status_kecamatan'], // atau kombinasikan dgn kec/kab
                'keterangan'   => ($row['keterangan_kecamatan'] == null) ? " " :  $row['keterangan_kecamatan'] == null,
                'detail_url'   => base_url("document/detail/" . $row['id'])
            ];
        }
    
        return $this->response->setJSON([
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $data,
        ]);
    }
    



public function DesaDetail($id)
    {
        $desaId = session('role_id') ?? null;
        if (!$desaId) {
            return $this->response->setStatusCode(401)->setJSON([
                'message' => 'Desa tidak teridentifikasi di session.'
            ]);
        }

        // 🔹 Ambil data submission + template
        $submission = $this->db->table('document_submissions ds')
            ->select('ds.*, dt.title as template_title')
            ->join('document_templates dt', 'dt.id = ds.template_id')
            ->where('ds.id', $id)
            ->where('ds.desa_id', $desaId) // biar aman hanya lihat dokumen desanya sendiri
            ->get()
            ->getRowArray();

        if (!$submission) {
            return $this->response->setStatusCode(404)->setJSON([
                'message' => 'Data submission tidak ditemukan.'
            ]);
        }

        // 🔹 Ambil file-file yg diupload
        $files = $this->db->table('document_submission_files sf')
            ->select('sf.id, sf.file_name, sf.file_path, sf.status_verifikasi, sf.uploaded_at, dtd.nama_file')
            ->join('document_templates_detail dtd', 'dtd.id = sf.template_detail_id')
            ->where('sf.submission_id', $id)
            ->get()
            ->getResultArray();

        // 🔹 Format response
        $result = [
            'submission' => [
                'id'            => $submission['id'],
                'title'         => $submission['template_title'],
                'earmarked'     => $submission['earmarked'],
                'non_earmarked' => $submission['non_earmarked'],
                'status_desa'   => $submission['status_desa'],
                'keterangan_kecamatan' => $submission['keterangan_kecamatan'],
                'keterangan_kabupaten' => $submission['keterangan_kabupaten'],
                'status_kecamatan' => $submission['status_kecamatan'],
                'created_at'    => $submission['created_at'],
            ],
            'files' => $files
        ];

        return $this->response->setJSON($result);
    }


    public function getDataDesaAll(){

        $page    = (int) ($this->request->getGet('page')   ?? 1);
        $length  = (int) ($this->request->getGet('length') ?? 10);
        $search  = trim((string) $this->request->getGet('search'));
    
        if ($page < 1)   $page = 1;
        if ($length < 1) $length = 10;
    

    
        $db = \Config\Database::connect();
        $builder = $db->table('desa ds');
        $builder->select('
                ds.id,
                ds.nama,
                ds.email,
                ds.no_rekening,
                ds.alamat_desa,
                ds.kepala_desa,
                kca.nama as kecamatan
            ')
            ->join('kecamatan kca', 'kca.id = ds.kecamatan_id');
            // ->where('ds.status_kabupaten', "submitted");
    
        if (!empty($search)) {
            $builder->groupStart()
                    ->like('kca.nama', $search)
                    ->orLike('ds.nama', $search)
                    ->orLike('ds.kepala_desa', $search)
                    ->groupEnd();
        }
    
        // total setelah filter
        $countBuilder = clone $builder;
        $total = (int) $countBuilder->countAllResults();
    
        // pagination
        $offset = ($page - 1) * $length;
        $rows = $builder->orderBy('ds.created_at', 'DESC')
                        ->limit($length, $offset)
                        ->get()
                        ->getResultArray();
    
        // Format hasil agar rapi ke frontend
        $data = [];
        $no = $offset + 1;
        foreach ($rows as $row) {
            $data[] = [
                'id'           => $row['id'],
                'no'           => $no++,
                'nama'        => $row['nama'],
                'kepala_desa'        => $row['kepala_desa'],
                'no_rekening'     => $row['no_rekening'],
                'alamat'        => $row['alamat_desa'],
                'kecamatan'        => $row['kecamatan'],
            ];
        }
    
        $totalPages = (int) ceil($total / $length);
        return $this->response->setJSON([
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $data,
            'pagination'      => [
                'current_page' => $page,
                'per_page'     => $length,
                'total_pages'  => $totalPages,
                'total_records'=> $total
            ]
        ]);
    
      }


}