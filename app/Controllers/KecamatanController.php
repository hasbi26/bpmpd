<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DocumentTemplatesKecamatanModel;


class KecamatanController extends BaseController
{
    protected $templateKecamatanModel;

    public function __construct()
    {
        helper(['auth']);
        $this->templateKecamatanModel = new DocumentTemplatesKecamatanModel();

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


   public function getDataKecamatan() {

    
    $request = service('request');
    $model = new DocumentTemplatesKecamatanModel();

    // Ambil parameter dari AJAX
    $search   = $request->getGet('search');
    $perPage  = $request->getGet('length') ?? 10;
    $page     = $request->getGet('page') ?? 1;

    $builder = $model->getActiveTemplates($search);

    // Hitung total
    $total = $builder->countAllResults(false);

    // Ambil data dengan pagination
    $data = $builder->paginate($perPage, 'default', $page);

    // Siapkan response JSON untuk DataTable
    return $this->response->setJSON([
        'recordsTotal'    => $total,
        'recordsFiltered' => $total,
        'data'            => $data,
    ]);

   }
}