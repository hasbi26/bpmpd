<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class DesaController extends BaseController
{
    public function __construct()
    {
        helper(['auth']);
    }

    public function dashboard()
    {
        // Cek apakah user sudah login dan role-nya desa
        if (!logged_in() || user()->role !== 'desa') {
            return redirect()->to('/login/desa')->with('error', 'Silakan login sebagai desa');
        }

        $data = [
            'title' => 'Dashboard Desa',
            'user' => user() // helper auth yang mengembalikan data user
        ];

        return view('desa/dashboard', $data);
    }
}