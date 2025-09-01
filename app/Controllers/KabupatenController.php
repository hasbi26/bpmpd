<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Config\Database;


class KabupatenController extends BaseController
{
    public function __construct()
    {
        $this->db = Database::connect();
        helper(['auth']);
    }

    public function dashboard()
    {
        // Cek apakah user sudah login dan role-nya kabupaten
        if (!logged_in() || user()->role !== 'kabupaten') {
            return redirect()->to('/login/kabupaten')->with('error', 'Silakan login sebagai kabupaten');
        }

        $data = [
            'title' => 'Dashboard Kabupaten',
            'user' => user()
        ];

        return view('kabupaten/dashboard', $data);
    }



    public function getDataStatusKabupaten(){
        
        $page    = (int) ($this->request->getGet('page')   ?? 1);
        $length  = (int) ($this->request->getGet('length') ?? 10);
        $search  = trim((string) $this->request->getGet('search'));
    
        if ($page < 1)   $page = 1;
        if ($length < 1) $length = 10;
    
        // Ambil desa_id dari session
        $roleId   = session('role_id') ?? null;
    
        if (!$roleId) {
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
                dt.title,
                dsa.nama,
                dsa.id as desa_id,
                dt.id as template_id,
                kca.nama as kecamatan
            ')
            ->join('document_templates dt', 'dt.id = ds.template_id')
            ->join('desa dsa', 'dsa.id = ds.desa_id')
            ->join('kecamatan kca', 'kca.id = dsa.kecamatan_id')
            ->where('ds.status_kecamatan', "submitted");
    
        if (!empty($search)) {
            $builder->groupStart()
                    ->like('dt.title', $search)
                    ->orLike('dsa.nama', $search)
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
                'desa_id'        => $row['desa_id'],
                'template_id'     => $row['template_id'],
                'nama'        => $row['nama'],
                'kecamatan'        => $row['kecamatan'],
                'earmarked'    => $row['earmarked'] ?? '-',
                'non_earmarked'=> $row['non_earmarked'] ?? '-',
                'status_desa'   => $row['status_desa'], // atau kombinasikan dgn kec/kab
                'status_kecamatan' => $row['status_kecamatan'], // atau kombinasikan dgn kec/kab
                'status_kabupaten' => $row['status_kabupaten'], // atau kombinasikan dgn kec/kab
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


    public function KabupatenDetail($id,$idTemplate,$desa)
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
            ->where('ds.desa_id', $desa) // biar aman hanya lihat dokumen desanya sendiri
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
                'status_kabupaten' => $submission['status_kabupaten'],
                'created_at'    => $submission['created_at'],
            ],
            'files' => $files
        ];

        return $this->response->setJSON($result);
    }


    public function upload_files()
    {
        $request = service('request');
        $response = service('response');
        $db = \Config\Database::connect();
    
        // User login (desa)
        // $desaId = session()->get('role_id'); // pastikan saat login Anda set role_id = id desa
        $role   = session()->get('role');    // 'desa'
    
        if ($role !== 'kabupaten') {
            return redirect()->to("{$role}/dashboard")->with('error', 'Hanya Kabpuaten yang bisa upload di tahap ini');

        }
    
        $idTemplate   = (int) $request->getPost('id_template');
        $desaId   = (int) $request->getPost('desa_id');
        $statusDesa = $request->getPost('status_kecamatan');
        $keteranganKecamatan = $request->getPost('keterangan');
       
        // Validasi minimal
        if (!$idTemplate) {
            return redirect()->to("{$role}/dashboard")->with('error', 'id_template kosong. Pastikan input hidden di-set');
        }
    
        $submissionModel = new \App\Models\DocumentSubmissionsModel();    
    
        // Mulai transaksi (manual biar bisa commit/rollback + error detail)
        $db->transBegin();
    
        try {
            // Cek existing submission
            $submission = $submissionModel
                ->where('template_id', $idTemplate)
                ->where('desa_id', $desaId)
                ->first();
    
            if ($submission) {
                $ok = $submissionModel->update($submission['id'], [
                    'status_kecamatan'     => $statusDesa,
                    'status_kabupaten'     => $statusDesa,
                    'keterangan_kecamatan'     => $keteranganKecamatan,
                    'updated_at'      => date('Y-m-d H:i:s')
                ]);
                if ($ok === false) {
                    // Validasi model (jika Anda pakai $validationRules di model)
                    $dbErr = $db->error();
                    $modelErr = $submissionModel->errors();
                    throw new \RuntimeException('Gagal update submission: ' . json_encode([
                        'db_error' => $dbErr,
                        'model_error' => $modelErr
                    ]));
                }
                $submissionId = (int) $submission['id'];
            } else {
                $submissionId = $submissionModel->insert([
                    'template_id'      => $idTemplate,
                    'desa_id'          => $desaId,
                    'status_kecamatan' => $statusDesa,
                    'status_kabupaten' => $statusDesa,
                ], true); // true = returnID
    
                if (!$submissionId) {
                    $dbErr = $db->error();
                    $modelErr = $submissionModel->errors();
                    throw new \RuntimeException('Gagal insert submission: ' . json_encode([
                        'db_error' => $dbErr,
                        'model_error' => $modelErr
                    ]));
                }
            }
    
            // Cek status transaksi
            if ($db->transStatus() === false) {
                $db->transRollback();
                // return $response->setJSON([
                //     'error'      => 'Transaksi DB gagal',
                //     'db_error'   => $db->error(),
                //     'last_query' => (string) $db->getLastQuery()
                // ]);
                return redirect()->to("{$role}/dashboard")->with('error', 'Verifikasi Gagal');

            }
    
            $db->transCommit();
            return redirect()->to("{$role}/dashboard")->with('success', 'Verifikasi Berhasil');

    
        } catch (\Throwable $e) {
            // Rollback dan kirim detail error
            if ($db->transStatus() !== false) {
                $db->transRollback();
            }
            log_message('error', 'Upload Desa Exception: {msg}', ['msg' => $e->getMessage()]);
            // return $response->setJSON([
            //     'error'       => 'Gagal menyimpan data',
            //     'exception'   => $e->getMessage(),
            //     'db_error'    => $db->error(),
            //     'last_query'  => (string) $db->getLastQuery()
            // ]);
            return redirect()->to("{$role}/dashboard")->with('error', 'Verifikasi Gagal');

        }
    }


  public function getDataStatusKabupatenAll(){

    $page    = (int) ($this->request->getGet('page')   ?? 1);
    $length  = (int) ($this->request->getGet('length') ?? 10);
    $search  = trim((string) $this->request->getGet('search'));

    if ($page < 1)   $page = 1;
    if ($length < 1) $length = 10;

    // Ambil desa_id dari session
    $roleId   = session('role_id') ?? null;

    if (!$roleId) {
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
            dt.title,
            dsa.nama,
            dsa.id as desa_id,
            dt.id as template_id,
            kca.nama as kecamatan
        ')
        ->join('document_templates dt', 'dt.id = ds.template_id')
        ->join('desa dsa', 'dsa.id = ds.desa_id')
        ->join('kecamatan kca', 'kca.id = dsa.kecamatan_id');
        // ->where('ds.status_kabupaten', "submitted");

    if (!empty($search)) {
        $builder->groupStart()
                ->like('dt.title', $search)
                ->orLike('dsa.nama', $search)
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
            'desa_id'        => $row['desa_id'],
            'template_id'     => $row['template_id'],
            'nama'        => $row['nama'],
            'kecamatan'        => $row['kecamatan'],
            'earmarked'    => $row['earmarked'] ?? '-',
            'non_earmarked'=> $row['non_earmarked'] ?? '-',
            'status_desa'   => $row['status_desa'], // atau kombinasikan dgn kec/kab
            'status_kecamatan' => $row['status_kecamatan'], // atau kombinasikan dgn kec/kab
            'status_kabupaten' => $row['status_kabupaten'], // atau kombinasikan dgn kec/kab
            'keterangan'   => ($row['keterangan_kecamatan'] == null) ? " " :  $row['keterangan_kecamatan'] == null,
            'detail_url'   => base_url("document/detail/" . $row['id'])
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

public function landingPage(){
    return view("kabupaten/landing_page");
}
    

}