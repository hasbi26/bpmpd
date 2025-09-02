<?php

namespace App\Controllers;

class Login extends BaseController
{
    public function index($tipe)
    {


        // Set judul dan keterangan berdasarkan tipe login
        switch ($tipe) {
            case 'desa':
                $data['title'] = ' Desa';
                $data['role']  = 'desa';
                break;
            case 'kecamatan':
                $data['title'] = ' Kecamatan';
                $data['role']  = 'kecamatan';
                break;
            case 'kabupaten':
                $data['title'] = ' Kabupaten';
                $data['role']  = 'kabupaten';
                break;
            default:
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        return view('login_form', $data);
    }
}
