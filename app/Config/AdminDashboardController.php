<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\UserModel;
use App\Models\UserAdminModel;
use App\Models\DesaModel;
use App\Models\KecamatanModel;
// use App\Models\DocumentTemplatesDesaModel;
use App\Models\DocumentTemplatesKecamatanModel;



class AdminDashboardController extends BaseController
{
    public function __construct()
    {
        helper(['form', 'url', 'auth']);

        $this->session = \Config\Services::session();

        // $this->templateDesaModel = new DocumentTemplatesDesaModel();
        $this->templateKecamatanModel = new DocumentTemplatesKecamatanModel();
        // $this->authLogger = new AuthLogger(\Config\Services::request());
    }


    public function adminsa()
    { 
        $data = [
            'user' => $this->session->get() 
        ];
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/login')
                             ->with('error', 'Silakan login terlebih dahulu');
        }
        return view('superadmin/admin_dashboard', $data);
    }


    public function admindashboard()
    {
        $customBgPath = FCPATH . 'assets/adminlte/img/login-bg.jpg';
        $customBgUrl  = file_exists($customBgPath) 
            ? base_url('assets/adminlte/img/login-bg.jpg') 
            : null; // null kalau file belum ada
        
        $data = [
            'user'     => $this->session->get(),
            'type'     => 'desa',
            'customBg' => $customBgUrl
        ];
        
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/login')
                             ->with('error', 'Silakan login terlebih dahulu');
        }

        return view('superadmin/admin_dashboard', $data);

    }


    public function getBackground()
{
    $customBgPath = FCPATH . 'assets/adminlte/img/login-bg.jpg';
    $customBgUrl  = file_exists($customBgPath)
        ? base_url('assets/adminlte/img/login-bg.jpg')
        : null;

    return $this->response->setJSON([
        'image' => $customBgUrl
    ]);
}

}