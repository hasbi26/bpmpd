<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PasswordResetModel;

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

    public function loginpage() {

        return view('login_form');

    }



    public function login()
    {
        // Validasi input
        $rules = [
            'username' => 'required',
            'password' => 'required|min_length[6]',
            'role' => 'required|in_list[desa,kecamatan,kabupaten]'
        ];

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $role = $this->request->getPost('role');
        $user = $this->userModel->getUserByUsernameAndRole($username, $role);

   //     dd($user);
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

// // Jika user tidak aktif
// if (!$user['is_active']) {

//     return redirect()->back()
//         ->withInput()
//         ->with('error', 'Akun Anda tidak aktif. Silakan hubungi administrator.');
// }
        // Set session data
        $sessionData = [
            'user_id'      => $user['user_id'],
            'username'     => $user['username'],
            'role'         => $user['role'],
            'email'         => $user['email'],
            'role_id'      => $user['role_id'],
            'wilayah_nama' => $user['wilayah_nama'], // langsung dapat
            'logged_in'    => true
        ];
    
        // Tambahkan data spesifik sesuai role
        if ($user['role'] === 'desa') {
            $sessionData['desa'] = [
                'id' => $user['user_id'], // id desa
                'nama' => $user['username'], // nama desa
                'kecamatan_id' => $user['kecamatan_id']
            ];
        } elseif ($user['role'] === 'kecamatan') {
            $sessionData['kecamatan'] = [
                'id' => $user['user_id'], // id kecamatan
                'nama' => $user['username'], // nama kecamatan
                'kabupaten_id' => $user['kabupaten_id']
            ];
        }
    
        $this->session->set($sessionData);


        // return view('/desa/dashboard');

        // return redirect()->to('/desa/dashboard');

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
    return redirect()->to("/");
    }

public function forgot()
{
    return view('forgot_password');
    // return redirect()->to('/forgot_password');
}

public function forgotPassword()
    {
        // dd($this->request->getPost());

        if ($this->request->getMethod() === 'POST') {
            $recaptchaResponse = $this->request->getPost('g-recaptcha-response');
            $username = $this->request->getPost('username');
           
            log_message('info', 'POST Data: ' . json_encode($this->request->getPost()));
            log_message('info', 'reCAPTCHA Response: ' . $recaptchaResponse);
            // ✅ reCAPTCHA check
            $secretKey = "6Lfx7aMrAAAAACLo-UeUwvyXRWBZIvB5UIrQYlUE";
            $verifyResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$recaptchaResponse}");
            $responseData = json_decode($verifyResponse);


            if (empty($recaptchaResponse) || !$responseData->success) {
                return redirect()->back()->withInput()->with('error', 'Verifikasi reCAPTCHA gagal.');
            }

            if (!$responseData->success) {
                return redirect()->back()->withInput()->with('error', 'Verifikasi reCAPTCHA gagal.');
            }

            // ✅ Cek user di DB
            $userModel = new UserModel();
            $user = $userModel->where('username', $username)->first();

            if (!$user) {
                return redirect()->back()->with('error', 'Nama desa / username tidak ditemukan.');
            }

            if (empty($user['email'])) {
                return redirect()->back()->with('error', 'Email untuk akun ini belum terdaftar. Hubungi admin.');
            }

            // ✅ Buat token dan simpan ke tabel password_resets
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', time() + 3600); // 1 jam

            $resetModel = new PasswordResetModel();
            $resetModel->insert([
                'user_id' => $user['id'],
                'token'   => $token,
                'expires_at' => $expires
            ]);

            // ✅ Link reset password
            $resetLink = base_url("reset-password/{$token}");

            // ✅ Kirim email
            //asdb kmtz iglz mcom
            $email = \Config\Services::email();
            $email->setFrom('keuasetdpmd@gmail.com', 'Admin');
            $email->setTo($user['email']);
            $email->setSubject('Reset Password');
            $email->setMessage("
                Halo {$user['username']},<br><br>
                Klik link berikut untuk reset password Anda:<br>
                <a href='{$resetLink}'>Reset Password</a><br><br>
                Link ini berlaku 1 jam.
            ");

            if ($email->send()) {
                return redirect()->back()->with('success', 'Link reset password telah dikirim ke email Anda.');
            } else {
                return redirect()->back()->with('error', 'Gagal mengirim email.');
            }
        }
        // return view('forgot_password');
        return view('forgot_password');
        
    }


    public function resetPassword($token)
    {
        $resetModel = new PasswordResetModel();
        $resetData = $resetModel->where('token', $token)
                                ->where('expires_at >', date('Y-m-d H:i:s'))
                                ->first();

        if (!$resetData) {
            return redirect()->to('forgot-password')->with('error', 'Token reset password tidak valid atau sudah kadaluarsa.');
        }

        return view('reset_password', ['token' => $token]);
    }

    public function processResetPassword()
    {
        $token = $this->request->getPost('token');
        $password = $this->request->getPost('password');
        $confirm  = $this->request->getPost('confirm');

        if ($password !== $confirm) {
            return redirect()->back()->with('error', 'Password dan konfirmasi tidak sama.');
        }

        $resetModel = new PasswordResetModel();
        $resetData = $resetModel->where('token', $token)
                                ->where('expires_at >', date('Y-m-d H:i:s'))
                                ->first();

        if (!$resetData) {
            return redirect()->to('forgot-password')->with('error', 'Token tidak valid atau sudah kadaluarsa.');
        }

        // ✅ Update password user
        $userModel = new UserModel();
        $userModel->update($resetData['user_id'], [
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);

        // ✅ Hapus token agar tidak dipakai lagi
        $resetModel->delete($resetData['id']);

        return redirect()->to('/')->with('success', 'Password berhasil diubah. Silakan login kembali.');
    }

}