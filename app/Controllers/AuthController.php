<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    protected $userModel;
    protected $session;


    protected $authLogger;

    public function __construct()
    {
        helper(['form', 'url']);
        $this->userModel = new UserModel();
        $this->session = \Config\Services::session();
        // $this->authLogger = new AuthLogger(\Config\Services::request());

    }


    public function login()
    {
        // Validasi input
        $rules = [
            'username' => 'required',
            'password' => 'required|min_length[6]',
            'role' => 'required|in_list[desa,kecamatan,kabupaten]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $role = $this->request->getPost('role');

        $user = $this->userModel->getUserByUsernameAndRole($username, $role);

// Jika user tidak ditemukan
if (!$user) {
    // Cek apakah username ada tapi role tidak sesuai
    $userWithDifferentRole = $this->userModel->where('username', $username)->first();
    
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

// Jika akun terkunci karena terlalu banyak upaya gagal
if ($this->userModel->isAccountLocked($user['id'])) {
    $unlockTime = date('H:i', strtotime($user['updated_at'] . ' +15 minutes'));
    return redirect()->back()
        ->withInput()
        ->with('error', 'Akun Anda terkunci sementara karena terlalu banyak upaya login gagal. 
              Silakan coba lagi setelah ' . $unlockTime);
}

// Jika user ditemukan tetapi password salah
// if (!password_verify($password, $user['password'])) {
//     // Catat upaya login gagal (opsional)
//     // $this->authLogger->logFailedAttempt($user['id']);
    
//     return redirect()->back()
//         ->withInput()
//         ->with('error', 'Password salah. Silakan coba lagi.');
// }
if (md5($password) !== $user['password']) {
    // Catat upaya login gagal
    // $this->authLogger->logFailedAttempt($user['id']);
    
    return redirect()->back()
        ->withInput()
        ->with('error', 'Password salah. Silakan coba lagi.');
}


// Jika user tidak aktif
if (!$user['is_active']) {
    return redirect()->back()
        ->withInput()
        ->with('error', 'Akun Anda tidak aktif. Silakan hubungi administrator.');
}


        // Set session data
        $sessionData = [
            'user_id' => $user['id'],
            'username' => $user['username'],
            'role' => $user['role'],
            'role_id' => $user['role_id'],
            'logged_in' => true
        ];

        $this->session->set($sessionData);

        // Redirect berdasarkan role
        switch ($user['role']) {
            case 'desa':
                return redirect()->to('/desa/dashboard');
            case 'kecamatan':
                return redirect()->to('/kecamatan/dashboard');
            case 'kabupaten':
                return redirect()->to('/kabupaten/dashboard');
            default:
                return redirect()->to('/');
        }
    }

    public function logout()
{
    // Ambil role sebelum menghapus session
    $role = strtolower($this->session->get('role') ?? 'login');
    
    // Hancurkan session
    $this->session->destroy();
    
    // Redirect langsung ke halaman sesuai role
    return redirect()->to("/$role");
}

}