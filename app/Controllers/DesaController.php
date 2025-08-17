<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DocumentTemplatesDesaModel;


class DesaController extends BaseController
{
    protected $templateDesaModel;

    public function __construct()
    {
        helper(['auth']);
        $this->templateDesaModel = new DocumentTemplatesDesaModel();

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

    public function getDataDesa()
    {
        $request = service('request');
        $model = new DocumentTemplatesDesaModel();

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