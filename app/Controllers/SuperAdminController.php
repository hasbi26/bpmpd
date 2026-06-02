<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\UserModel;
use App\Models\UserAdminModel;
use App\Models\DesaModel;
use App\Models\KecamatanModel;


class SuperAdminController extends BaseController
{
    protected $userAdminModel;

    public function __construct()
    {
        helper(['form', 'url', 'auth']);

        $this->userAdminModel = new UserAdminModel();
        $this->session = \Config\Services::session();
        // $this->authLogger = new AuthLogger(\Config\Services::request());

    }


    public function proses()
    {
        $file = $this->request->getFile('file_excel');

        if ($file && $file->isValid()) {
            $spreadsheet = IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();
        
            $userModel = new UserModel();
        
            for ($i = 1; $i < count($rows); $i++) {
                $id          = (int) trim($rows[$i][0]);
                $username    = trim($rows[$i][1]);
                $passwordRaw = trim($rows[$i][2]);
                $role_id     = trim($rows[$i][3]);
                $role        = trim($rows[$i][4]);
                $is_active   = trim($rows[$i][5]);
                $email       = null;
                $created_by  = "upload Excel";
                $created_at  = trim($rows[$i][8]);
                $update_at   = null;
        
                // Skip kalau ID kosong atau <= 0
                if ($id <= 0) {
                    continue;
                }
        
                $passwordHash = password_hash($passwordRaw ?: '12345', PASSWORD_BCRYPT);
                // var_dump($userModel); exit;
                // Gunakan save() untuk insert/update berdasarkan primary key
                $userModel->insert([
                    'id'         => $id,
                    'username'   => $username,
                    'password'   => $passwordHash,
                    'role_id'    => $role_id,
                    'role'       => $role,
                    'is_active'  => $is_active,
                    'email'      => $email,
                    'created_by' => $created_by,
                    'created_at' => $created_at ?: date('Y-m-d H:i:s'),
                    'update_at'  => $update_at
                ]);

                // echo $this->db->getLastQuery(); exit;
            }
        
            return "Import berhasil!";
        }
        return "File tidak ditemukan atau tidak valid.";
    }

    public function desa()
    {
        $file = $this->request->getFile('file_excel');

        if ($file && $file->isValid()) {
            $spreadsheet = IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();
        
            $desaModel = new DesaModel();
        
            for ($i = 1; $i < count($rows); $i++) {
                $id          = (int) trim($rows[$i][0]);
                $kecamatan_id    = trim($rows[$i][1]);
                $nama = trim($rows[$i][2]);
                $is_active = trim($rows[$i][3]);
                $email     = null;
                $created_by  = "upload Excel";
                $created_at  = null;
                $update_at   = null;
        
                // Skip kalau ID kosong atau <= 0
                if ($id <= 0) {
                    continue;
                }
                        // var_dump($userModel); exit;
                // Gunakan save() untuk insert/update berdasarkan primary key
                $desaModel->insert([
                    'id'         => $id,
                    'kecamatan_id'   => $kecamatan_id,
                    'nama'   => $nama,
                    'is_active'   => $is_active,
                    'email'    => $email,
                    'created_by'       => $created_by,
                    'created_at'  => $created_at ?: date('Y-m-d H:i:s'),
                    'update_at'      => $update_at
                ]);

                // echo $this->db->getLastQuery(); exit;
            }
        
            return "Import berhasil!";
        }
        return "File tidak ditemukan atau tidak valid.";
    }

        public function kecamatan()
    {
        $file = $this->request->getFile('file_excel');

        if ($file && $file->isValid()) {
            $spreadsheet = IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();
        
            $KecamatanModel = new KecamatanModel();
        
            for ($i = 1; $i < count($rows); $i++) {
                $id          = (int) trim($rows[$i][0]);
                $kabupaten_id    = trim($rows[$i][1]);
                $nama = trim($rows[$i][2]);
                $is_active = trim($rows[$i][3]);
                $email     = null;
                $created_by  = "upload Excel";
                $created_at  = null;
                $update_at   = null;
        
                // Skip kalau ID kosong atau <= 0
                if ($id <= 0) {
                    continue;
                }
                        // var_dump($userModel); exit;
                // Gunakan save() untuk insert/update berdasarkan primary key
                $KecamatanModel->insert([
                    'id'         => $id,
                    'kabupaten_id'   => $kabupaten_id,
                    'nama'   => $nama,
                    'is_active'   => $is_active,
                    'email'    => $email,
                    'created_by'       => $created_by,
                    'created_at'  => $created_at ?: date('Y-m-d H:i:s'),
                    'update_at'      => $update_at
                ]);

                // echo $this->db->getLastQuery(); exit;
            }
        
            return "Import berhasil!";
        }
        return "File tidak ditemukan atau tidak valid.";
    }


    public function prosesadmin()
    {
        $file = $this->request->getFile('file_excel');

        if ($file && $file->isValid()) {
            $spreadsheet = IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();
        
            $userModel = new UserAdminModel();
        
            for ($i = 1; $i < count($rows); $i++) {
                $id          = (int) trim($rows[$i][0]);
                $username    = trim($rows[$i][1]);
                $passwordRaw = trim($rows[$i][2]);
                $role_id     = trim($rows[$i][3]);
                $role        = trim($rows[$i][4]);
                $is_active   = trim($rows[$i][5]);
                $email       = trim($rows[$i][6]);;
                $created_by  = "upload Excel";
                $created_at  = trim($rows[$i][8]);
                $update_at   = null;
        
                // Skip kalau ID kosong atau <= 0
                if ($id <= 0) {
                    continue;
                }
        
                $passwordHash = password_hash($passwordRaw ?: '12345', PASSWORD_BCRYPT);
                // var_dump($userModel); exit;
                // Gunakan save() untuk insert/update berdasarkan primary key
                $userModel->insert([
                    'id'         => $id,
                    'username'   => $username,
                    'password'   => $passwordHash,
                    'role_id'    => $role_id,
                    'role'       => $role,
                    'is_active'  => $is_active,
                    'email'      => $email,
                    'created_by' => $created_by,
                    'created_at' => $created_at ?: date('Y-m-d H:i:s'),
                    'update_at'  => $update_at
                ]);

                // echo $this->db->getLastQuery(); exit;
            }
        
            return "Import berhasil!";
        }
        return "File tidak ditemukan atau tidak valid.";
    }


    public function logout()
    {
    // Ambil role sebelum menghapus session
    
    // Hancurkan session
    $this->session->destroy();
    
    // Redirect langsung ke halaman sesuai role
    return redirect()->to("/");
    }

    public function login(){
        return view('superadmin/login');
    }


    public function loginadmin()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required|min_length[6]',
            'role' => 'required|in_list[sa,admin]'
        ];

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $role = $this->request->getPost('role');
        $user = $this->userAdminModel->getUserByUsernameAndRole($username, $role);

// Jika user tidak ditemukan
if (!$user) {
    // Cek apakah username ada tapi role tidak sesuai
    $userWithDifferentRole = $this->$userModel->where('username', $username)->first();
    
    if ($userWithDifferentRole) {
        // Username ada tapi role tidak sesuai
        return redirect()->back()
            ->withInput()
            ->with('error', 'Username ditemukan tetapi tidak memiliki akses sebagai ' . ucfirst($role) . 
                  '. Silakan login dengan role ' . ucfirst($userWithDifferentRole['role']));
    } else {
        // Username tidak ada sama sekali
        return redirect()->back()
            ->withInput()
            ->with('error', 'Username tidak ditemukan');
    }
}

// // Jika akun terkunci karena terlalu banyak upaya gagal
// if ($this->userModel->isAccountLocked($user['id'])) {

//     $unlockTime = date('H:i', strtotime($user['updated_at'] . ' +15 minutes'));
//     return redirect()->back()
//         ->withInput()
//         ->with('error', 'Akun Anda terkunci sementara karena terlalu banyak upaya login gagal. 
//               Silakan coba lagi setelah ' . $unlockTime);
// }

if (!password_verify($password, $user['password'])) {
    // Catat upaya login gagal
    // $this->authLogger->logFailedAttempt($user['id']);
    
    return redirect()->back()
        ->withInput()
        ->with('error', 'Password salah. Silakan coba lagi.');
}


        // Set session data
        $sessionData = [
            'user_id'      => $user['user_id'],
            'username'     => $user['username'],
            'role'         => $user['role'],
            'role_id'      => $user['role_id'],
            // 'wilayah_nama' => $user['wilayah_nama'], // langsung dapat
            'logged_in'    => true
        ];
    
        
    
        $this->session->set($sessionData);

        // Redirect berdasarkan role
        switch ($user['role']) {
            case 'sa':
                return redirect()->to('/sa');
            case 'admin':
                return redirect()->to('/admindashboard');
            default:
                return redirect()->to('/');
        }
    }


   public function uploadBackground(){

    $file = $this->request->getFile('background');
        
    if ($file && $file->isValid() && !$file->hasMoved()) {
        // pakai nama fix supaya gampang dicek
        $newName = 'login-bg.' . $file->getExtension(); 
        $file->move(FCPATH . 'assets/adminlte/img', $newName, true);

        return redirect()->to('/admindashboard')->with('success', 'Background berhasil diubah!');
    }
    return redirect()->to('/admindashboard')->with('error', 'Upload gagal!');


   }
    public function  getBackground(){
        $customBgPath = FCPATH . 'assets/adminlte/img/login-bg.jpg';
        $customBgUrl  = file_exists($customBgPath)
            ? base_url('assets/adminlte/img/login-bg.jpg')
            : null;
    
        return $this->response->setJSON([
            'image' => $customBgUrl
        ]);

    }

   public function deleteBackground(){
    $filePath = FCPATH . 'assets/adminlte/img/login-bg.jpg';

    if (file_exists($filePath)) {
        unlink($filePath);
        return redirect()->to('/admindashboard')->with('success', 'Background berhasil dihapus');
    }    
    return redirect()->to('/admindashboard')->with('error', 'Background gagal dihapus');
   }
}
