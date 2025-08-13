<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class KecamatanController extends BaseController
{
    public function __construct()
    {
        helper(['auth']);
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
}