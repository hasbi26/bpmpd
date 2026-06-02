<?php

namespace App\Controllers;

use App\Models\DocumentDesaModel;
use CodeIgniter\Controller;

use App\Models\DocumentTemplatesModel;
use App\Models\DocumentTemplatesDetailModel;

use App\Models\DocumentSubmissionsModel;
use App\Models\DocumentSubmissionFilesModel;


class DocumentDesaController extends Controller
{
    protected $documentModel;
    protected $session;
    
    protected $templateModel;
    protected $detailModel;

    public function __construct()
    {
        $this->documentModel = new DocumentDesaModel();
        $this->session = session(); // ✅ sekarang bisa pakai $this->session

    }

    public function upload($id)
    {
        $request = service('request');
        $file = $this->request->getFile("file_$id");

        if (!$file->isValid()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $file->getErrorString()
            ]);
        }

        // ✅ Validasi extension
        if ($file->getClientExtension() !== 'pdf') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'File harus berupa PDF'
            ]);
        }

        // ✅ Validasi ukuran max 1MB
        if ($file->getSize() > 1024 * 1024) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Ukuran maksimal 1MB'
            ]);
        }

        // ✅ Ambil data user dari session
        $userId   = session('user_id');
        $tingkat = session('role'); // enum: 'desa' | 'kecamatan' | 'kabupaten'
        $desaId   = session('role_id'); // kalau user desa
        $kecamatanId = session('desa')['kecamatan_id'];

       


        // ✅ Ambil data desa + kecamatan dari database
        $db = db_connect();
        $desaData = $db->table('desa')
            ->select('desa.nama as desa_nama, kecamatan.nama as kec_nama')
            ->join('kecamatan', 'kecamatan.id = desa.kecamatan_id')
            ->where('desa.id', $desaId)
            ->get()
            ->getRow();


        if (!$desaData) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data desa tidak ditemukan'
            ]);
        }

        // ✅ Ambil judul dokumen dari tabel document_templates_desa
        $doc = $db->table('document_templates_desa')
            ->where('id', $id)
            ->get()
            ->getRow();

        if (!$doc) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Template dokumen tidak ditemukan'
            ]);
        }

        // ✅ Generate nama file
        $title     = preg_replace('/\s+/', '_', strtolower($doc->title));
        $kecamatan = preg_replace('/\s+/', '_', strtolower($desaData->kec_nama));
        $desa      = preg_replace('/\s+/', '_', strtolower($desaData->desa_nama));

        $newFileName = "{$title}_kec_{$kecamatan}_desa_{$desa}.pdf";

        // ✅ Path simpan file
        $kecamatan = str_replace(' ', '_', $kecamatan);
        $desa      = str_replace(' ', '_', $desa);

        $uploadPath = FCPATH . "uploads/kabupaten/kecamatan/{$kecamatan}/desa/{$desa}/";

        // Buat folder kalau belum ada
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        // ✅ Pindahkan file
        $file->move($uploadPath, $newFileName, true);

        // ✅ Simpan ke DB document_desa
        $this->documentModel->insert([
            'id'          => $id,
            'desa_id'     => $desaId,
            'nama'        => $newFileName,
            'filename'    => $newFileName,
            'path'        => "uploads/kabupaten/kecamatan/{$kecamatan}/desa/{$desa}/",
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Upload berhasil',
            'file'    => $newFileName
        ]);
    }

    public function getData()
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
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'message' => 'Desa tidak teridentifikasi di session.'
            ]);
        }

        $builder = $this->documentModel->builder('document_templates dt');
        $builder->select('dt.id, dt.title, dt.deskripsi, dt.is_active, dt.created_at')
                ->where('dt.is_active', 1);
        
        if (!empty($search)) {
            $builder->groupStart()
                    ->like('dt.title', $search)
                    ->orLike('dt.deskripsi', $search)
                    ->groupEnd();
        }
        

        // total setelah filter
        $countBuilder = clone $builder;
        $total = (int) $countBuilder->countAllResults();

        // pagination
        $offset = ($page - 1) * $length;
        $rows = $builder->orderBy('dt.created_at', 'DESC')
                        ->limit($length, $offset)
                        ->get()
                        ->getResultArray();

        return $this->response->setJSON([
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $rows,
        ]);
    }


    public function documentDesaDetail($id)
{
    $role = session('role'); // ambil role user (desa/kecamatan)
    $desa_id = session('role_id'); // ambil role user (desa/kecamatan)


    $templateModel = new DocumentTemplatesModel();
    $detailModel   = new DocumentTemplatesDetailModel();
    $detailSubmissionModel = new DocumentSubmissionsModel();

    $detailSubmission = $detailSubmissionModel
        ->where('desa_id', $desa_id)
        ->where('template_id', $id)
        ->first();

    // if ($detailSubmission){

    // }
    // Ambil parent
    $template = $templateModel
        ->where('id', $id)
        ->first();

    if (!$template) {
        return $this->response->setJSON(['error' => 'Template tidak ditemukan']);
    }

    // Ambil detail sesuai role
    $details = $detailModel
        ->where('id_templates', $id)
        ->where('role', $role) // khusus role dari session
        ->findAll();

    return $this->response->setJSON([
        'template' => $template,
        'details'  => $details,
        'submission' => $detailSubmission,
    ]);
}


public function upload_files()
{
    $request = service('request');
    $response = service('response');
    $db = \Config\Database::connect();

    // User login (desa)
    $desaId = session()->get('role_id'); // pastikan saat login Anda set role_id = id desa
    $role   = session()->get('role');    // 'desa'

    if ($role !== 'desa') {
        return $response->setJSON(['error' => 'Hanya desa yang bisa upload di tahap ini']);
    }

    $idTemplate   = (int) $request->getPost('id_template');
    $earmarked    = $request->getPost('earmarked');
    $nonEarmarked = $request->getPost('non_earmarked');

    // Validasi minimal
    if (!$idTemplate) {
        return $response->setJSON(['error' => 'id_template kosong. Pastikan input hidden di-set.']);
    }

    $submissionModel = new \App\Models\DocumentSubmissionsModel();
    $fileModel       = new \App\Models\DocumentSubmissionFilesModel();

    // Siapkan folder upload
    $uploadDir = FCPATH . 'uploads/desa';
    if (!is_dir($uploadDir)) {
        @mkdir($uploadDir, 0755, true);
    }
    if (!is_dir($uploadDir) || !is_writable($uploadDir)) {
        return $response->setJSON(['error' => 'Folder upload tidak bisa diakses: ' . $uploadDir]);
    }

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
                'earmarked'       => $earmarked,
                'non_earmarked'   => $nonEarmarked,
                'status_desa'     => 'submitted',
                'status_desa_at' => date('Y-m-d H:i:s'),
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
                'earmarked'        => $earmarked,
                'non_earmarked'    => $nonEarmarked,
                'status_desa'      => 'submitted',
                'status_desa_at' => date('Y-m-d H:i:s')
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

        // Handle file upload
        $files = $this->request->getFiles();
        if (isset($files['files']) && is_array($files['files'])) {
            foreach ($files['files'] as $detailId => $file) {
                if (empty($file) || !$file->isValid()) {
                    continue;
                }
                if ($file->hasMoved()) {
                    continue;
                }

                // Validasi file
                if ($file->getClientMimeType() !== 'application/pdf') {
                    throw new \RuntimeException('File harus PDF untuk detail_id ' . $detailId);
                }
                if ($file->getSize() > 1024 * 1024) {
                    throw new \RuntimeException('Ukuran file maksimal 1MB untuk detail_id ' . $detailId);
                }

                // === Ambil nama kecamatan & desa ===
                $desaData = $db->table('desa')
                    ->select('desa.nama as desa_nama, kecamatan.nama as kec_nama')
                    ->join('kecamatan', 'kecamatan.id = desa.kecamatan_id')
                    ->where('desa.id', $desaId)
                    ->get()
                    ->getRow();

                if (!$desaData) {
                    throw new \RuntimeException("Data desa tidak ditemukan untuk ID {$desaId}");
                }

                $kecamatan = url_title($desaData->kec_nama, '-', true);
                $desa      = url_title($desaData->desa_nama, '-', true);

                $templateData = $db->table('document_templates')
                    ->select('title')
                    ->where('id', $idTemplate)
                    ->get()
                    ->getRow();

                // === Ambil nama file dari template detail ===
                $detail = $db->table('document_templates_detail')
                    ->select('nama_file')
                    ->where('id', $detailId)
                    ->get()
                    ->getRow();

                if (!$detail) {
                    throw new \RuntimeException("Detail template tidak ditemukan untuk ID {$detailId}");
                }

                $judulFolder = str_replace(' ', '_', $templateData->title);

                $uploadPath = FCPATH . "uploads/kabupaten/kecamatan/{$kecamatan}/desa/{$desa}/{$judulFolder}/";

                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                $fileName = "Desa " . $detail->nama_file . ".pdf";
                $finalFileName = str_replace(' ', '_', $fileName);

                // Pindahkan file
                if (!$file->move($uploadPath, $finalFileName, true)) {
                    throw new \RuntimeException('Gagal memindahkan file: ' . $file->getErrorString());
                }

                // Cek apakah file sudah ada di database
                $existingFile = $fileModel
                    ->where('submission_id', $submissionId)
                    ->where('template_detail_id', $detailId)
                    ->first();

                if ($existingFile) {
                    // Update entri yang sudah ada
                    $ok = $fileModel->update($existingFile['id'], [
                        'file_path'         => "uploads/kabupaten/kecamatan/{$kecamatan}/desa/{$desa}/{$judulFolder}/" . $finalFileName,
                        'file_name'         => $finalFileName,
                        'file_size'         => $file->getSize(),
                        'status_verifikasi' => 'pending',
                        'updated_at'        => date('Y-m-d H:i:s'),
                    ]);
                    if ($ok === false) {
                        $dbErr = $db->error();
                        $modelErr = $fileModel->errors();
                        throw new \RuntimeException('Gagal update file record: ' . json_encode([
                            'detail_id' => $detailId,
                            'db_error' => $dbErr,
                            'model_error' => $modelErr
                        ]));
                    }
                } else {
                    // Insert entri baru
                    $ok = $fileModel->insert([
                        'submission_id'      => $submissionId,
                        'template_detail_id' => (int) $detailId,
                        'uploader_role'      => 'desa',
                        'file_path'          => "uploads/kabupaten/kecamatan/{$kecamatan}/desa/{$desa}/{$judulFolder}/" . $finalFileName,
                        'file_name'          => $finalFileName,
                        'file_size'          => $file->getSize(),
                        'status_verifikasi'  => 'pending',
                        'uploaded_at'        => date('Y-m-d H:i:s'),
                    ]);

                    if ($ok === false) {
                        $dbErr = $db->error();
                        $modelErr = $fileModel->errors();
                        throw new \RuntimeException('Gagal insert file record: ' . json_encode([
                            'detail_id' => $detailId,
                            'db_error' => $dbErr,
                            'model_error' => $modelErr
                        ]));
                    }
                }
            }
        }

        // Cek status transaksi
        if ($db->transStatus() === false) {
            $db->transRollback();
            return $response->setJSON([
                'error'      => 'Transaksi DB gagal',
                'db_error'   => $db->error(),
                'last_query' => (string) $db->getLastQuery()
            ]);
        }

        $db->transCommit();
        return $response->setJSON(['success' => true, 'message' => 'Dokumen berhasil disimpan']);

    } catch (\Throwable $e) {
        // Rollback dan kirim detail error
        if ($db->transStatus() !== false) {
            $db->transRollback();
        }
        log_message('error', 'Upload Desa Exception: {msg}', ['msg' => $e->getMessage()]);
        return $response->setJSON([
            'error'       => 'Gagal menyimpan data',
            'exception'   => $e->getMessage(),
            'db_error'    => $db->error(),
            'last_query'  => (string) $db->getLastQuery()
        ]);
    }
}



// public function upload_files()
//     {
//         $request = service('request');
//         $response = service('response');
//         $db = \Config\Database::connect();

//         // User login (desa)
//         $desaId = session()->get('role_id'); // pastikan saat login Anda set role_id = id desa
//         $role   = session()->get('role');    // 'desa'

//         if ($role !== 'desa') {
//             return $response->setJSON(['error' => 'Hanya desa yang bisa upload di tahap ini']);
//         }

//         $idTemplate   = (int) $request->getPost('id_template');
//         $earmarked    = $request->getPost('earmarked');
//         $nonEarmarked = $request->getPost('non_earmarked');

//         // Validasi minimal
//         if (!$idTemplate) {
//             return $response->setJSON(['error' => 'id_template kosong. Pastikan input hidden di-set.']);
//         }

//         $submissionModel = new \App\Models\DocumentSubmissionsModel();
//         $fileModel       = new \App\Models\DocumentSubmissionFilesModel();

//         // Siapkan folder upload
//         $uploadDir = FCPATH . 'uploads/desa';
//         if (!is_dir($uploadDir)) {
//             @mkdir($uploadDir, 0755, true);
//         }
//         if (!is_dir($uploadDir) || !is_writable($uploadDir)) {
//             return $response->setJSON(['error' => 'Folder upload tidak bisa diakses: ' . $uploadDir]);
//         }

//         // Mulai transaksi (manual biar bisa commit/rollback + error detail)
//         $db->transBegin();

//         try {
//             // Cek existing submission
//             $submission = $submissionModel
//                 ->where('template_id', $idTemplate)
//                 ->where('desa_id', $desaId)
//                 ->first();

//             if ($submission) {
//                 $ok = $submissionModel->update($submission['id'], [
//                     'earmarked'       => $earmarked,
//                     'non_earmarked'   => $nonEarmarked,
//                     'status_desa'     => 'resubmitted',
//                     'updated_at'      => date('Y-m-d H:i:s')
//                 ]);
//                 if ($ok === false) {
//                     // Validasi model (jika Anda pakai $validationRules di model)
//                     $dbErr = $db->error();
//                     $modelErr = $submissionModel->errors();
//                     throw new \RuntimeException('Gagal update submission: ' . json_encode([
//                         'db_error' => $dbErr,
//                         'model_error' => $modelErr
//                     ]));
//                 }
//                 $submissionId = (int) $submission['id'];

//             } else {
//                 $submissionId = $submissionModel->insert([
//                     'template_id'      => $idTemplate,
//                     'desa_id'          => $desaId,
//                     'earmarked'        => $earmarked,
//                     'non_earmarked'    => $nonEarmarked,
//                     'status_desa'      => 'submitted',
//                     'status_kecamatan' => 'submitted',
//                     'status_kabupaten' => 'pending',
//                 ], true); // true = returnID

//                 if (!$submissionId) {
//                     $dbErr = $db->error();
//                     $modelErr = $submissionModel->errors();
//                     throw new \RuntimeException('Gagal insert submission: ' . json_encode([
//                         'db_error' => $dbErr,
//                         'model_error' => $modelErr
//                     ]));
//                 }
//             }

//             // Handle file upload
// $files = $this->request->getFiles();
// if (isset($files['files']) && is_array($files['files'])) {
//     foreach ($files['files'] as $detailId => $file) {
//         if (empty($file) || !$file->isValid()) {
//             continue;
//         }
//         if ($file->hasMoved()) {
//             continue;
//         }

//         // Validasi file
//         if ($file->getClientMimeType() !== 'application/pdf') {
//             throw new \RuntimeException('File harus PDF untuk detail_id ' . $detailId);
//         }
//         if ($file->getSize() > 1024 * 1024) {
//             throw new \RuntimeException('Ukuran file maksimal 1MB untuk detail_id ' . $detailId);
//         }

//         // === Ambil nama kecamatan & desa ===
//         $desaData = $db->table('desa')
//             ->select('desa.nama as desa_nama, kecamatan.nama as kec_nama')
//             ->join('kecamatan', 'kecamatan.id = desa.kecamatan_id')
//             ->where('desa.id', $desaId)
//             ->get()
//             ->getRow();

//         if (!$desaData) {
//             throw new \RuntimeException("Data desa tidak ditemukan untuk ID {$desaId}");
//         }

//         $kecamatan = url_title($desaData->kec_nama, '-', true);
//         $desa      = url_title($desaData->desa_nama, '-', true);

//         $templateData = $db->table('document_templates')
//         ->select('title')
//         ->where('id', $idTemplate)
//         ->get()
//         ->getRow();

//         // === Ambil nama file dari template detail ===
//         $detail = $db->table('document_templates_detail')
//             ->select('nama_file')
//             ->where('id', $detailId)
//             ->get()
//             ->getRow();

//         if (!$detail) {
//             throw new \RuntimeException("Detail template tidak ditemukan untuk ID {$detailId}");
//         }

//         $judulFolder = str_replace(' ', '_', $templateData->title);

//         $uploadPath = FCPATH . "uploads/kabupaten/kecamatan/{$kecamatan}/desa/{$desa}/{$judulFolder}/";

//         if (!is_dir($uploadPath)) {
//             mkdir($uploadPath, 0777, true);
//         }

//         $fileName = "Desa ".$detail->nama_file . ".pdf";

//         $finalFileName = str_replace(' ', '_', $fileName);
//         // Pindahkan file
//         if (!$file->move($uploadPath, $finalFileName, true)) {
//             throw new \RuntimeException('Gagal memindahkan file: ' . $file->getErrorString());
//         }

//         // Simpan ke DB
//         $ok = $fileModel->insert([
//             'submission_id'      => $submissionId,
//             'template_detail_id' => (int) $detailId,
//             'uploader_role'      => 'desa',
//             'file_path'          => "uploads/kabupaten/kecamatan/{$kecamatan}/desa/{$desa}/{$judulFolder}/".$finalFileName,
//             'file_name'          => $finalFileName,
//             'file_size'          => $file->getSize(),
//             'status_verifikasi'  => 'pending',
//             'uploaded_at'        => date('Y-m-d H:i:s'),
//         ]);

//         if ($ok === false) {
//             $dbErr = $db->error();
//             $modelErr = $fileModel->errors();
//             throw new \RuntimeException('Gagal insert file record: ' . json_encode([
//                 'detail_id' => $detailId,
//                 'db_error' => $dbErr,
//                 'model_error' => $modelErr
//             ]));
//         }
//     }
// }
//             // Cek status transaksi
//             if ($db->transStatus() === false) {
//                 $db->transRollback();
//                 return $response->setJSON([
//                     'error'      => 'Transaksi DB gagal',
//                     'db_error'   => $db->error(),
//                     'last_query' => (string) $db->getLastQuery()
//                 ]);
//             }

//             $db->transCommit();
//             return $response->setJSON(['success' => true, 'message' => 'Dokumen berhasil disimpan']);

//         } catch (\Throwable $e) {
//             // Rollback dan kirim detail error
//             if ($db->transStatus() !== false) {
//                 $db->transRollback();
//             }
//             log_message('error', 'Upload Desa Exception: {msg}', ['msg' => $e->getMessage()]);
//             return $response->setJSON([
//                 'error'       => 'Gagal menyimpan data',
//                 'exception'   => $e->getMessage(),
//                 'db_error'    => $db->error(),
//                 'last_query'  => (string) $db->getLastQuery()
//             ]);
//         }
//     }


}
