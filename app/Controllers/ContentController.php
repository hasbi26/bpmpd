<?php
namespace App\Controllers;
use App\Models\DocumentTemplatesDesaModel;
use App\Models\DocumentTemplatesKecamatanModel;
use App\Models\DesaModel;

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

        
            log_message('info', "Mencoba load content: role={$role}, type={$type}");
    
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
            $validContents = ['status', 'upload', 'settings','profil'];
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
                
                $desaModel = new \App\Models\DesaModel();

                $page   = (int) ($this->request->getGet('page') ?? 1);
                $length = (int) ($this->request->getGet('length') ?? 10);
                $search = trim((string) $this->request->getGet('search'));
            
                $profilDesa = $desaModel->getAllProfilDesa($search, $length, $page);
            } 
            
            return view($viewPath, [
                'role'        => $role,
                'type'        => $type,
                'namaWilayah' => $namaWilayah,
                'templates'   => $template,
                'idprofil'    => $idprofil,
                'profilDesa' =>  $profilDesa,
                'search'    =>  $search,
            ]);
            
        
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