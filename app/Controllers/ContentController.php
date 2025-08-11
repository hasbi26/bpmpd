<?php
namespace App\Controllers;

class ContentController extends BaseController
{
    public function loadContent()
    {
        $role = $this->request->getGet('role');
        $type = $this->request->getGet('type');

        var_dump($role,$type);
        die;

        try {
            log_message('info', "Mencoba load content: role={$role}, type={$type}");

        // Validasi role yang diperbolehkan
        $allowedRoles = ['desa', 'kecamatan', 'kabupaten'];
        if (!in_array($role, $allowedRoles)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Daftar konten yang valid
        $validContents = ['status', 'upload', 'settings'];
        if (!in_array($type, $validContents)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $viewPath = "$role/{$type}_content";

        // Pastikan file view ada
        if (!view_exists($viewPath)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view($viewPath);
    
    } catch (\Exception $e) {
        log_message('error', 'Error: ' . $e->getMessage());
        throw $e;
    }
    }

    
}