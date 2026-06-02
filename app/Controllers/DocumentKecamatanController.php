<?php

namespace App\Controllers;

use App\Models\DocumentDesaModel;
use CodeIgniter\Controller;

use App\Models\DocumentTemplatesModel;
use App\Models\DocumentTemplatesDetailModel;

use App\Models\DocumentSubmissionsModel;
// use App\Models\DocumentSubmissionFilesModel;
use App\Models\DesaModel;
use Config\Database;



class DocumentKecamatanController extends Controller
{
    protected $session;

    
    public function __construct()
    {
        $this->documentModel = new DocumentDesaModel();
        $this->session = session(); // ✅ sekarang bisa pakai $this->session
        $this->db = Database::connect();

    }

    public function documentKecamatanDetail($id, $idTemplate, $desa)
    {
        $role = session('role'); // ambil role user (desa/kecamatan)
        $desa_id = session('role_id'); // ambil role user (desa/kecamatan)
     

        $templateModel = new DocumentTemplatesModel();
        $detailModel   = new DocumentTemplatesDetailModel();
        // $detailSubmissionModel = new DocumentSubmissionFilesModel();
        $submisionModel = new DocumentSubmissionsModel();
        $desaModel = new DesaModel();
    
        // $detailSubmission = $detailSubmissionModel
        //     ->where('id', $id)
        //     ->first();

        $desaTitle = $desaModel->select('nama')->where('id', $desa)->first();

        // print_r($desaTitle);

        $submission = $submisionModel->where('id', $id)
        ->where('template_id', $idTemplate)
        ->where('desa_id', $desa)
        ->first();
    
        // if ($detailSubmission){
    
        // }
        // // Ambil parent
        $template = $templateModel
            ->where('id', $idTemplate)
            ->first();
    
        if (!$template) {
            return $this->response->setJSON(['error' => 'Template tidak ditemukan tot']);
        }
    
        // Ambil detail sesuai role
        $details = $detailModel
            ->where('id_templates', $idTemplate)
            ->where('role', $role) // khusus role dari session
            ->findAll();

            $files = $this->db->table('document_submission_files sf')
            ->select('sf.id, sf.file_name, sf.file_path, sf.status_verifikasi, sf.uploaded_at, dtd.nama_file')
            ->join('document_templates_detail dtd', 'dtd.id = sf.template_detail_id')
            ->where('sf.submission_id', $id)
            ->where('sf.uploader_role', "desa")
            ->get()
            ->getResultArray();
            
    
        return $this->response->setJSON([
            'template' => $template,
            'details'  => $details,
            // 'submissionDetail' => $detailSubmission,
            'submission' => $submission,
            'desaTitle' => $desaTitle,
            'files' => $files,
        ]);
    }


    public function upload_files()
    {
        $request = service('request');
        $response = service('response');
        $db = \Config\Database::connect();
    
        // User login (desa)
        // $desaId = session()->get('role_id'); // pastikan saat login Anda set role_id = id desa
        $role   = session()->get('role');    // 'desa'
    
        if ($role !== 'kecamatan') {
            return redirect()->to("{$role}/dashboard")->with('error', "session");

            // return $response->setJSON(['error' => 'Hanya kecamatan yang bisa upload di tahap ini']);
        }
    
        $idTemplate   = (int) $request->getPost('id_template');
        $desaId   = (int) $request->getPost('desa_id');
        $statusDesa = $request->getPost('status_desa');
        $keteranganKecamatan = $request->getPost('keterangan');
       
        // Validasi minimal
        if (!$idTemplate) {
            return redirect()->to("{$role}/dashboard")->with('error', "Template Kosong");

            // return $response->setJSON(['error' => 'id_template kosong. Pastikan input hidden di-set.']);
        }
    
        $submissionModel = new \App\Models\DocumentSubmissionsModel();
        $fileModel       = new \App\Models\DocumentSubmissionFilesModel();
    
    
        // Mulai transaksi (manual biar bisa commit/rollback + error detail)
        $db->transBegin();

        if($statusDesa == "approved"){
            $status_kecamatan ="submitted";
        } if ($statusDesa == "rejected"){
            $status_kecamatan ="pending";
        }

    
        try {
            // Cek existing submission
            $submission = $submissionModel
                ->where('template_id', $idTemplate)
                ->where('desa_id', $desaId)
                ->first();
    
            if ($submission) {
                $ok = $submissionModel->update($submission['id'], [
                    'status_desa'     => $statusDesa,
                    'status_kecamatan'     => $status_kecamatan,
                    'status_desa_at'     => date('Y-m-d H:i:s'),
                    'status_kecamatan_at'     => date('Y-m-d H:i:s'),
                    'keterangan_kecamatan'     => $keteranganKecamatan,
                    'updated_at'      => date('Y-m-d H:i:s')
                ]);
                if ($ok === false) {
                    // Validasi model (jika Anda pakai $validationRules di model)
                    $dbErr = $db->error();
                    $modelErr = $submissionModel->errors();
                    // throw new \RuntimeException('Gagal update submission: ' . json_encode([
                    //     'db_error' => $dbErr,
                    //     'model_error' => $modelErr
                    // ]));
                    return redirect()->to("{$role}/dashboard")->with('error', "Gagal update submission");

                }
                $submissionId = (int) $submission['id'];
            } else {
                $submissionId = $submissionModel->insert([
                    'template_id'      => $idTemplate,
                    'status_kecamatan'     => $status_kecamatan,
                    'desa_id'          => $desaId,
                    'status_desa'      => $statusDesa,
                    'status_kecamatan_at'     => date('Y-m-d H:i:s'),
                    'status_desa_at'     => date('Y-m-d H:i:s'),
                ], true); // true = returnID
    
                if (!$submissionId) {
                    $dbErr = $db->error();
                    $modelErr = $submissionModel->errors();
                    // throw new \RuntimeException('Gagal insert submission: ' . json_encode([
                    //     'db_error' => $dbErr,
                    //     'model_error' => $modelErr
                    // ]));
                    return redirect()->to("{$role}/dashboard")->with('error', "Gagal insert submission");

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
                        return redirect()->to("{$role}/dashboard")->with('error', "File harus PDF");
                        // throw new \RuntimeException('File harus PDF untuk detail_id ' . $detailId);
                    }
                    if ($file->getSize() > 1024 * 1024) {
                        // throw new \RuntimeException('Ukuran file maksimal 1MB untuk detail_id ' . $detailId);
                        return redirect()->to("{$role}/dashboard")->with('error', "Ukuran file maksimal 1MB");
                    }
    
                    // === Ambil nama kecamatan & desa ===
                    $desaData = $db->table('desa')
                        ->select('desa.nama as desa_nama, kecamatan.nama as kec_nama')
                        ->join('kecamatan', 'kecamatan.id = desa.kecamatan_id')
                        ->where('desa.id', $desaId)
                        ->get()
                        ->getRow();
    
                    if (!$desaData) {
                        return redirect()->to("{$role}/dashboard")->with('error', "Data Kecamatan tidak ditemukan untuk");

                        // throw new \RuntimeException("Data desa tidak ditemukan untuk ID {$desaId}");
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
                        return redirect()->to("{$role}/dashboard")->with('error', "Detail template tidak ditemukan untuk ID {$detailId}");

                        // throw new \RuntimeException("Detail template tidak ditemukan untuk ID {$detailId}");
                    }
    
                    $judulFolder = str_replace(' ', '_', $templateData->title);
    
                    $uploadPath = FCPATH . "uploads/kabupaten/kecamatan/{$kecamatan}/desa/{$desa}/{$judulFolder}/";
    
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }
    
                    $fileName = "kecamatan " . $detail->nama_file . ".pdf";
                    $finalFileName = str_replace(' ', '_', $fileName);
    
                    // Pindahkan file
                    if (!$file->move($uploadPath, $finalFileName, true)) {
                        // throw new \RuntimeException('Gagal memindahkan file: ' . $file->getErrorString());
                        return redirect()->to("{$role}/dashboard")->with('error', 'Transaksi DB gagal');

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
                            'uploader_role'      =>'kecamatan',
                            'updated_at'        => date('Y-m-d H:i:s'),
                        ]);
                        if ($ok === false) {
                            $dbErr = $db->error();
                            $modelErr = $fileModel->errors();
                            // throw new \RuntimeException('Gagal update file record: ' . json_encode([
                            //     'detail_id' => $detailId,
                            //     'db_error' => $dbErr,
                            //     'model_error' => $modelErr
                            // ]));
                            return redirect()->to("{$role}/dashboard")->with('error', 'Transaksi DB gagal');

                        }
                    } else {
                        // Insert entri baru
                        $ok = $fileModel->insert([
                            'submission_id'      => $submissionId,
                            'template_detail_id' => (int) $detailId,
                            'uploader_role'      => 'kecamatan',
                            'file_path'          => "uploads/kabupaten/kecamatan/{$kecamatan}/desa/{$desa}/{$judulFolder}/" . $finalFileName,
                            'file_name'          => $finalFileName,
                            'file_size'          => $file->getSize(),
                            'status_verifikasi'  => 'pending',
                            'uploaded_at'        => date('Y-m-d H:i:s'),
                        ]);
    
                        if ($ok === false) {
                            $dbErr = $db->error();
                            $modelErr = $fileModel->errors();
                            // throw new \RuntimeException('Gagal insert file record: ' . json_encode([
                            //     'detail_id' => $detailId,
                            //     'db_error' => $dbErr,
                            //     'model_error' => $modelErr
                            // ]));
                            
                            return redirect()->to("{$role}/dashboard")->with('error', 'Transaksi DB gagal');

                        }
                    }
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
                return redirect()->to("{$role}/dashboard")->with('error', 'Transaksi DB gagal');

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


}