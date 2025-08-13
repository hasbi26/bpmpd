<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\UserModel;
use App\Models\UserAdminModel;
use App\Models\DesaModel;
use App\Models\KecamatanModel;


class AdminDashboardController extends BaseController
{
    public function __construct()
    {
        helper(['form', 'url', 'auth']);

        $this->userAdminModel = new UserAdminModel();
        $this->session = \Config\Services::session();
        // $this->authLogger = new AuthLogger(\Config\Services::request());
    }


    public function adminsa()
    { 


        // dd($this->session->get('logged_in'));

        if (!$this->session->get('logged_in')) {
            return redirect()->to('/login')
                             ->with('error', 'Silakan login terlebih dahulu');
        }
        
        return view('superadmin/upload_excel');
    }


}