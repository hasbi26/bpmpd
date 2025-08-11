<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class KabupatenController extends BaseController
{
    public function __construct()
    {
        helper(['auth']);
    }

    public function dashboard()
    {
        // Cek apakah user sudah login dan role-nya kabupaten
        if (!logged_in() || user()->role !== 'kabupaten') {
            return redirect()->to('/login/kabupaten')->with('error', 'Silakan login sebagai kabupaten');
        }

        $data = [
            'title' => 'Dashboard Kabupaten',
            'user' => user()
        ];

        return view('kabupaten/dashboard', $data);
    }
}