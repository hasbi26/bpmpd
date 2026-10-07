<?php
namespace App\Controllers;
use App\Models\DocumentTemplatesDesaModel;
use App\Models\DocumentTemplatesKecamatanModel;
use App\Models\DesaModel;
use App\Models\AsetTanahModel;

class ContentController extends BaseController
{
    public function __construct()
    {
        helper(['form', 'url']);
        $this->session = \Config\Services::session();
        $this->templateDesaModel = new DocumentTemplatesDesaModel();
        $this->templateKecamatanModel = new DocumentTemplatesKecamatanModel();
        // $this->authLogger = new AuthLogger(\Config\Services::request());

    }

    public function loadContent($type)
    {
        try {
            $role = $this->request->getGet('role');

        
            // var_dump('info', "Mencoba load content: role={$role}, type={$type} ");
            // die;

            // Validasi parameter wajib
            if (empty($role) || empty($type)) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Parameter role dan type diperlukan');
            }
    
            // Validasi role yang diperbolehkan
            $allowedRoles = ['desa', 'kecamatan', 'kabupaten', 'sa', 'admin'];
            if (!in_array(strtolower($role), $allowedRoles)) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Role tidak valid');
            }
    
            // Daftar konten yang valid
            $validContents = ['status', 'upload', 'settings', 'profil', 'kendaraan', 'kiba', 'peralatan', 'kibc','kibd', 'kibe','kibf'];
            if (!in_array($type, $validContents)) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Tipe konten tidak valid');
            }


            $viewPath = "$role/{$type}_content";


    
            // CARA YANG BENAR UNTUK MENGECEK VIEW DI CODEIGNITER 4
            if (!is_file(APPPATH . 'Views/' . $viewPath . '.php')) {
                log_message('error', "View not found: {$viewPath}");
                throw new \CodeIgniter\Exceptions\PageNotFoundException('View tidak ditemukan');
            }
            $namaWilayah = $this->session->get('wilayah_nama');
            $template    = null;
            $idprofil = null;
            $profilDesa = null;
            $search = null;
            
            if ($role == "desa") {
                // pakai model desa
                $template = $this->templateDesaModel->getActiveTemplates(); 
                $idprofil =  $this->session->get('role_id');    
                $desaModel = new \App\Models\DesaModel();
                $profilDesa = $desaModel->getProfilDesa($idprofil);
            } elseif ($role == "kecamatan") {
                $template = $this->templateKecamatanModel->getActiveTemplates();
                $idprofil =  $this->session->get('role_id');  
            }elseif ($role == "kabupaten"){
                
                $idprofil = $this->session->get('role_id'); 
                $desaModel = new \App\Models\DesaModel();

                $page   = (int) ($this->request->getGet('page') ?? 1);
                $length = (int) ($this->request->getGet('length') ?? 10);
                $search = trim((string) $this->request->getGet('search'));
            
                $profilDesa = $desaModel->getAllProfilDesa($search, $length, $page);
            } 

 // ================== BAGIAN YANG BERUBAH ==================
 
 $rows           = [];
 $desaList       = [];
 $kecamatanList  = [];
 $selectedDesaId = null;
 $selectedKecamatanId = null;

 if ($role === 'desa' && $type === 'kiba') {
     $asetTanahModel = new \App\Models\AsetTanahModel();
     $rows = $asetTanahModel->getByDesa((int) $idprofil);

 } elseif ($role === 'desa' && $type === 'kendaraan') {
     $asetKendaraanModel = new \App\Models\AsetKendaraanModel();
     $rows = $asetKendaraanModel->getByDesa((int) $idprofil);

 } elseif ($role === 'desa' && $type === 'peralatan') {
     $asetPeralatanModel = new \App\Models\AsetPeralatanMesinModel();
     $rows = $asetPeralatanModel->getByDesa((int) $idprofil);

 } elseif ($role === 'desa' && $type === 'kibc') {
     $asetBangunanModel = new \App\Models\AsetBangunanModel();
     $rows = $asetBangunanModel->getByDesa((int) $idprofil);

 } elseif ($role === 'desa' && $type === 'kibd') {
     $asetJalanIrigasiModel = new \App\Models\AsetJalanIrigasiModel();
     $rows = $asetJalanIrigasiModel->getByDesa((int) $idprofil);

 } elseif ($role === 'desa' && $type === 'kibe') {
     $asetLainnyaModel = new \App\Models\AsetLainnyaModel();
     $rows = $asetLainnyaModel->getByDesa((int) $idprofil);

 } elseif ($role === 'desa' && $type === 'kibf') {
    $asetHilangRusakModel = new \App\Models\AsetHilangRusakModel();
    $rows = $asetHilangRusakModel->getByDesa((int) $idprofil);

} elseif ($role === 'kecamatan' && $type === 'kiba') {
     $asetTanahModel = new \App\Models\AsetTanahModel();

     // Filter desa dikirim lewat query string ?desa_id=... dari dropdown
     $desaIdRaw      = $this->request->getGet('desa_id');
     $selectedDesaId = ($desaIdRaw !== null && $desaIdRaw !== '') ? (int) $desaIdRaw : null;

     $rows = $asetTanahModel->getByKecamatan((int) $idprofil, $selectedDesaId);

     // Daftar desa di bawah kecamatan ini, untuk mengisi dropdown filter
     $desaList = \Config\Database::connect()
         ->table('desa')
         ->select('id, nama')
         ->where('kecamatan_id', $idprofil)
         ->orderBy('nama', 'ASC')
         ->get()
         ->getResultArray();

 } elseif ($role === 'kabupaten' && $type === 'kiba') {
     $asetTanahModel = new \App\Models\AsetTanahModel();

     $kecIdRaw  = $this->request->getGet('kecamatan_id');
     $desaIdRaw = $this->request->getGet('desa_id');
     $selectedKecamatanId = ($kecIdRaw !== null && $kecIdRaw !== '') ? (int) $kecIdRaw : null;
     $selectedDesaId      = ($desaIdRaw !== null && $desaIdRaw !== '') ? (int) $desaIdRaw : null;

     $rows = $asetTanahModel->getByKabupaten((int) $idprofil, $selectedKecamatanId, $selectedDesaId);

     // Daftar kecamatan di bawah kabupaten ini, untuk dropdown filter pertama
     $kecamatanList = \Config\Database::connect()
         ->table('kecamatan')
         ->select('id, nama')
         ->where('kabupaten_id', $idprofil)
         ->orderBy('nama', 'ASC')
         ->get()
         ->getResultArray();

     // Daftar desa untuk dropdown kedua: kalau kecamatan dipilih,
     // hanya desa di kecamatan itu; kalau tidak, semua desa di kabupaten ini.
     $desaBuilder = \Config\Database::connect()
         ->table('desa d')
         ->select('d.id, d.nama')
         ->join('kecamatan k', 'k.id = d.kecamatan_id')
         ->where('k.kabupaten_id', $idprofil);

     if (!empty($selectedKecamatanId)) {
         $desaBuilder->where('d.kecamatan_id', $selectedKecamatanId);
     }

     $desaList = $desaBuilder->orderBy('d.nama', 'ASC')->get()->getResultArray();

 } elseif ($role === 'kecamatan' && $type === 'kendaraan') {
     $asetKendaraanModel = new \App\Models\AsetKendaraanModel();

     $desaIdRaw      = $this->request->getGet('desa_id');
     $selectedDesaId = ($desaIdRaw !== null && $desaIdRaw !== '') ? (int) $desaIdRaw : null;

     $rows = $asetKendaraanModel->getByKecamatan((int) $idprofil, $selectedDesaId);

     $desaList = \Config\Database::connect()
         ->table('desa')
         ->select('id, nama')
         ->where('kecamatan_id', $idprofil)
         ->orderBy('nama', 'ASC')
         ->get()
         ->getResultArray();

 } elseif ($role === 'kabupaten' && $type === 'kendaraan') {
     $asetKendaraanModel = new \App\Models\AsetKendaraanModel();

     $kecIdRaw  = $this->request->getGet('kecamatan_id');
     $desaIdRaw = $this->request->getGet('desa_id');
     $selectedKecamatanId = ($kecIdRaw !== null && $kecIdRaw !== '') ? (int) $kecIdRaw : null;
     $selectedDesaId      = ($desaIdRaw !== null && $desaIdRaw !== '') ? (int) $desaIdRaw : null;

     $rows = $asetKendaraanModel->getByKabupaten((int) $idprofil, $selectedKecamatanId, $selectedDesaId);

     $kecamatanList = \Config\Database::connect()
         ->table('kecamatan')
         ->select('id, nama')
         ->where('kabupaten_id', $idprofil)
         ->orderBy('nama', 'ASC')
         ->get()
         ->getResultArray();

     $desaBuilder = \Config\Database::connect()
         ->table('desa d')
         ->select('d.id, d.nama')
         ->join('kecamatan k', 'k.id = d.kecamatan_id')
         ->where('k.kabupaten_id', $idprofil);

     if (!empty($selectedKecamatanId)) {
         $desaBuilder->where('d.kecamatan_id', $selectedKecamatanId);
     }

     $desaList = $desaBuilder->orderBy('d.nama', 'ASC')->get()->getResultArray();

 } elseif ($role === 'kecamatan' && $type === 'peralatan') {
     $asetPeralatanModel = new \App\Models\AsetPeralatanMesinModel();

     $desaIdRaw      = $this->request->getGet('desa_id');
     $selectedDesaId = ($desaIdRaw !== null && $desaIdRaw !== '') ? (int) $desaIdRaw : null;

     $rows = $asetPeralatanModel->getByKecamatan((int) $idprofil, $selectedDesaId);

     $desaList = \Config\Database::connect()
         ->table('desa')
         ->select('id, nama')
         ->where('kecamatan_id', $idprofil)
         ->orderBy('nama', 'ASC')
         ->get()
         ->getResultArray();

 } elseif ($role === 'kabupaten' && $type === 'peralatan') {
     $asetPeralatanModel = new \App\Models\AsetPeralatanMesinModel();

     $kecIdRaw  = $this->request->getGet('kecamatan_id');
     $desaIdRaw = $this->request->getGet('desa_id');
     $selectedKecamatanId = ($kecIdRaw !== null && $kecIdRaw !== '') ? (int) $kecIdRaw : null;
     $selectedDesaId      = ($desaIdRaw !== null && $desaIdRaw !== '') ? (int) $desaIdRaw : null;

     $rows = $asetPeralatanModel->getByKabupaten((int) $idprofil, $selectedKecamatanId, $selectedDesaId);

     $kecamatanList = \Config\Database::connect()
         ->table('kecamatan')
         ->select('id, nama')
         ->where('kabupaten_id', $idprofil)
         ->orderBy('nama', 'ASC')
         ->get()
         ->getResultArray();

     $desaBuilder = \Config\Database::connect()
         ->table('desa d')
         ->select('d.id, d.nama')
         ->join('kecamatan k', 'k.id = d.kecamatan_id')
         ->where('k.kabupaten_id', $idprofil);

     if (!empty($selectedKecamatanId)) {
         $desaBuilder->where('d.kecamatan_id', $selectedKecamatanId);
     }

     $desaList = $desaBuilder->orderBy('d.nama', 'ASC')->get()->getResultArray();

 } elseif ($role === 'kecamatan' && $type === 'kibc') {
     $asetBangunanModel = new \App\Models\AsetBangunanModel();

     $desaIdRaw      = $this->request->getGet('desa_id');
     $selectedDesaId = ($desaIdRaw !== null && $desaIdRaw !== '') ? (int) $desaIdRaw : null;

     $rows = $asetBangunanModel->getByKecamatan((int) $idprofil, $selectedDesaId);

     $desaList = \Config\Database::connect()
         ->table('desa')
         ->select('id, nama')
         ->where('kecamatan_id', $idprofil)
         ->orderBy('nama', 'ASC')
         ->get()
         ->getResultArray();

 } elseif ($role === 'kabupaten' && $type === 'kibc') {
     $asetBangunanModel = new \App\Models\AsetBangunanModel();

     $kecIdRaw  = $this->request->getGet('kecamatan_id');
     $desaIdRaw = $this->request->getGet('desa_id');
     $selectedKecamatanId = ($kecIdRaw !== null && $kecIdRaw !== '') ? (int) $kecIdRaw : null;
     $selectedDesaId      = ($desaIdRaw !== null && $desaIdRaw !== '') ? (int) $desaIdRaw : null;

     $rows = $asetBangunanModel->getByKabupaten((int) $idprofil, $selectedKecamatanId, $selectedDesaId);

     $kecamatanList = \Config\Database::connect()
         ->table('kecamatan')
         ->select('id, nama')
         ->where('kabupaten_id', $idprofil)
         ->orderBy('nama', 'ASC')
         ->get()
         ->getResultArray();

     $desaBuilder = \Config\Database::connect()
         ->table('desa d')
         ->select('d.id, d.nama')
         ->join('kecamatan k', 'k.id = d.kecamatan_id')
         ->where('k.kabupaten_id', $idprofil);

     if (!empty($selectedKecamatanId)) {
         $desaBuilder->where('d.kecamatan_id', $selectedKecamatanId);
     }

     $desaList = $desaBuilder->orderBy('d.nama', 'ASC')->get()->getResultArray();

 } elseif ($role === 'kecamatan' && $type === 'kibd') {
     $asetJalanIrigasiModel = new \App\Models\AsetJalanIrigasiModel();

     $desaIdRaw      = $this->request->getGet('desa_id');
     $selectedDesaId = ($desaIdRaw !== null && $desaIdRaw !== '') ? (int) $desaIdRaw : null;

     $rows = $asetJalanIrigasiModel->getByKecamatan((int) $idprofil, $selectedDesaId);

     $desaList = \Config\Database::connect()
         ->table('desa')
         ->select('id, nama')
         ->where('kecamatan_id', $idprofil)
         ->orderBy('nama', 'ASC')
         ->get()
         ->getResultArray();

 } elseif ($role === 'kabupaten' && $type === 'kibd') {
     $asetJalanIrigasiModel = new \App\Models\AsetJalanIrigasiModel();

     $kecIdRaw  = $this->request->getGet('kecamatan_id');
     $desaIdRaw = $this->request->getGet('desa_id');
     $selectedKecamatanId = ($kecIdRaw !== null && $kecIdRaw !== '') ? (int) $kecIdRaw : null;
     $selectedDesaId      = ($desaIdRaw !== null && $desaIdRaw !== '') ? (int) $desaIdRaw : null;

     $rows = $asetJalanIrigasiModel->getByKabupaten((int) $idprofil, $selectedKecamatanId, $selectedDesaId);

     $kecamatanList = \Config\Database::connect()
         ->table('kecamatan')
         ->select('id, nama')
         ->where('kabupaten_id', $idprofil)
         ->orderBy('nama', 'ASC')
         ->get()
         ->getResultArray();

     $desaBuilder = \Config\Database::connect()
         ->table('desa d')
         ->select('d.id, d.nama')
         ->join('kecamatan k', 'k.id = d.kecamatan_id')
         ->where('k.kabupaten_id', $idprofil);

     if (!empty($selectedKecamatanId)) {
         $desaBuilder->where('d.kecamatan_id', $selectedKecamatanId);
     }

     $desaList = $desaBuilder->orderBy('d.nama', 'ASC')->get()->getResultArray();
 }
 elseif ($role === 'kecamatan' && $type === 'kibe') {
    $asetLainnyaModel = new \App\Models\AsetLainnyaModel();

    $desaIdRaw      = $this->request->getGet('desa_id');
    $selectedDesaId = ($desaIdRaw !== null && $desaIdRaw !== '') ? (int) $desaIdRaw : null;

    $rows = $asetLainnyaModel->getByKecamatan((int) $idprofil, $selectedDesaId);

    $desaList = \Config\Database::connect()
        ->table('desa')
        ->select('id, nama')
        ->where('kecamatan_id', $idprofil)
        ->orderBy('nama', 'ASC')
        ->get()
        ->getResultArray();

} elseif ($role === 'kabupaten' && $type === 'kibe') {
    $asetLainnyaModel = new \App\Models\AsetLainnyaModel();

    $kecIdRaw  = $this->request->getGet('kecamatan_id');
    $desaIdRaw = $this->request->getGet('desa_id');
    $selectedKecamatanId = ($kecIdRaw !== null && $kecIdRaw !== '') ? (int) $kecIdRaw : null;
    $selectedDesaId      = ($desaIdRaw !== null && $desaIdRaw !== '') ? (int) $desaIdRaw : null;

    $rows = $asetLainnyaModel->getByKabupaten((int) $idprofil, $selectedKecamatanId, $selectedDesaId);

    $kecamatanList = \Config\Database::connect()
        ->table('kecamatan')
        ->select('id, nama')
        ->where('kabupaten_id', $idprofil)
        ->orderBy('nama', 'ASC')
        ->get()
        ->getResultArray();

    $desaBuilder = \Config\Database::connect()
        ->table('desa d')
        ->select('d.id, d.nama')
        ->join('kecamatan k', 'k.id = d.kecamatan_id')
        ->where('k.kabupaten_id', $idprofil);

    if (!empty($selectedKecamatanId)) {
        $desaBuilder->where('d.kecamatan_id', $selectedKecamatanId);
    }

    $desaList = $desaBuilder->orderBy('d.nama', 'ASC')->get()->getResultArray();
} elseif ($role === 'kecamatan' && $type === 'kibf') {
    $asetHilangRusakModel = new \App\Models\AsetHilangRusakModel();

    $desaIdRaw      = $this->request->getGet('desa_id');
    $selectedDesaId = ($desaIdRaw !== null && $desaIdRaw !== '') ? (int) $desaIdRaw : null;

    $rows = $asetHilangRusakModel->getByKecamatan((int) $idprofil, $selectedDesaId);

    $desaList = \Config\Database::connect()
        ->table('desa')
        ->select('id, nama')
        ->where('kecamatan_id', $idprofil)
        ->orderBy('nama', 'ASC')
        ->get()
        ->getResultArray();

} elseif ($role === 'kabupaten' && $type === 'kibf') {
    $asetHilangRusakModel = new \App\Models\AsetHilangRusakModel();

    $kecIdRaw  = $this->request->getGet('kecamatan_id');
    $desaIdRaw = $this->request->getGet('desa_id');
    $selectedKecamatanId = ($kecIdRaw !== null && $kecIdRaw !== '') ? (int) $kecIdRaw : null;
    $selectedDesaId      = ($desaIdRaw !== null && $desaIdRaw !== '') ? (int) $desaIdRaw : null;

    $rows = $asetHilangRusakModel->getByKabupaten((int) $idprofil, $selectedKecamatanId, $selectedDesaId);

    $kecamatanList = \Config\Database::connect()
        ->table('kecamatan')
        ->select('id, nama')
        ->where('kabupaten_id', $idprofil)
        ->orderBy('nama', 'ASC')
        ->get()
        ->getResultArray();

    $desaBuilder = \Config\Database::connect()
        ->table('desa d')
        ->select('d.id, d.nama')
        ->join('kecamatan k', 'k.id = d.kecamatan_id')
        ->where('k.kabupaten_id', $idprofil);

    if (!empty($selectedKecamatanId)) {
        $desaBuilder->where('d.kecamatan_id', $selectedKecamatanId);
    }

    $desaList = $desaBuilder->orderBy('d.nama', 'ASC')->get()->getResultArray();
}

 return view($viewPath, [
     'role'                => $role,
     'type'                => $type,
     'namaWilayah'         => $namaWilayah,
     'templates'           => $template,
     'idprofil'            => $idprofil,
     'profilDesa'          => $profilDesa,
     'search'              => $search,
     'rows'                => $rows,
     'desaList'            => $desaList,
     'kecamatanList'       => $kecamatanList,
     'selectedDesaId'      => $selectedDesaId,
     'selectedKecamatanId' => $selectedKecamatanId,
 ]);

 // ================== AKHIR BAGIAN YANG BERUBAH ==================

                       
            
        
        } catch (\Exception $e) {
            log_message('error', 'Error in ContentController: ' . $e->getMessage());
            
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(500)->setJSON([
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
            
            throw $e;
        }
    }
}