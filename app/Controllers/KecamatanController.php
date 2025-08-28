<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DocumentTemplatesKecamatanModel;
use App\Models\DocumentSubmissionsModel;
use Config\Database;


class KecamatanController extends BaseController
{
    protected $templateKecamatanModel;
    protected $session;
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->db = Database::connect();
        helper(['auth']);
        $this->templateKecamatanModel = new DocumentTemplatesKecamatanModel();

    }

    public function dashboard()
    {
        // Cek apakah user sudah login dan role-nya kecamatan
        if (!logged_in() || user()->role !== 'kecamatan') {
            return redirect()->to('/login/kecamatan')->with('error', 'Silakan login sebagai kecamatan');
        }

        $user = user();
    
        // Proses username jika diawali dengan "Kec" (case insensitive)
        $username = $user->username;
        if (strtolower(substr($username, 0, 3)) === 'kec') {
            $user->username = substr($username, 3); // Hapus 3 karakter pertama
        }



        $data = [
            'title' => 'Dashboard Kecamatan',
            'user' => $user
        ];


        // log_message('info', json_encode($user));


        return view('kecamatan/dashboard', $data);
    }


   public function getDataKecamatan() {

    // print_r($this->session->get());

    $role_id = $this->session->get('role_id');

    $request = service('request');
    $model = new DocumentSubmissionsModel();

    // Ambil parameter dari AJAX
    $search   = $request->getGet('search');
    $perPage  = $request->getGet('length') ?? 10;
    $page     = $request->getGet('page') ?? 1;

    $builder = $model->getStatusKecamatan($search, $role_id);

    // Hitung total
    $total = $builder->countAllResults(false);

    // Ambil data dengan pagination
    $data = $builder->paginate($perPage, 'default', $page);

    // Siapkan response JSON untuk DataTable
    return $this->response->setJSON([
        'recordsTotal'    => $total,
        'recordsFiltered' => $total,
        'data'            => $data,
    ]);

   }


   public function getDataStatusKecamatan()
   {
       $page    = (int) ($this->request->getGet('page')   ?? 1);
       $length  = (int) ($this->request->getGet('length') ?? 10);
       $search  = trim((string) $this->request->getGet('search'));

       if ($page < 1)   $page = 1;
       if ($length < 1) $length = 10;
   
       // Ambil desa_id dari session
       $KecamatanId   = session('role_id') ?? null;
   
       if (!$KecamatanId) {
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
               dt.id as template_id
           ')
           ->join('document_templates dt', 'dt.id = ds.template_id')
           ->join('desa dsa', 'dsa.id = ds.desa_id')
           ->join('kecamatan kca', 'dsa.kecamatan_id = kca.id')
           ->where('kca.id',$KecamatanId);
        //    ->groupStart()
        //       ->where('ds.status_desa',"submitted")
        //       ->orWhere('ds.status_kecamatan',"rejected")
        //    ->groupEnd();
   
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



   public function KecamatanDetail($id,$idTemplate,$desa)
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
                'status_kecamatan' => $submission['status_kecamatan'],
                'status_kabupaten' => $submission['status_kabupaten'],
                'created_at'    => $submission['created_at'],
            ],
            'files' => $files
        ];

        return $this->response->setJSON($result);
    }


}






