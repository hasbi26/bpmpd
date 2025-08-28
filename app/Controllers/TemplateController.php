<?php namespace App\Controllers;

use App\Models\DocumentTemplatesDesaModel;
use App\Models\DocumentTemplatesKecamatanModel;
use App\Models\DocumentTemplatesModel;


class TemplateController extends BaseController
{
    protected $templateDesaModel;
    protected $templateKecamatanModel;
    protected $templateDocumentModel;

    public function __construct()
    {
        $this->templateDesaModel = new DocumentTemplatesDesaModel();
        $this->templateKecamatanModel = new DocumentTemplatesKecamatanModel();
        $this->templateDocumentModel = new DocumentTemplatesModel();
    }

    // DESA TEMPLATES
    public function indexDesa()
    {
        $data = [
            'templates' => $this->templateDesaModel->getTemplatesByUser(session('user_id')),
            'type' => 'desa'
        ];
        return view('templates/index', $data);
    }

    public function createDesa()
    {
        return view('templates/create', ['type' => 'desa']);
    }

    public function storeTemplates()
    {
            // Ambil data dari form
            $title      = trim((string) $this->request->getPost('title'));
            $deskripsi  = (string) $this->request->getPost('deskripsi');
            $desa       = (array) $this->request->getPost('desa');       // array of strings
            $kecamatan  = (array) $this->request->getPost('kecamatan');  // array of strings
    
            // Ambil user id (sesuaikan key session-mu)
            $userId = session()->get('user_id') ?? 0;
    
            // Validasi sederhana
            if ($title === '') {
                session()->setFlashdata('error', 'Judul (title) wajib diisi.');
                return redirect()->back()->withInput();
            }
    
            // Koneksi DB (CI4)
            $db = \Config\Database::connect();
    
            // Mulai transaksi
            $db->transStart();
    
            // 1) Insert ke tabel induk: document_templates
            $db->table('document_templates')->insert([
                'title'       => $title,
                'deskripsi'   => $deskripsi,
                'created_by'  => $userId,
                'created_at'  => date('Y-m-d H:i:s'),
                'is_active'   => 1,
                // 'earmarked'   => null,        // opsional, biarkan NULL jika tidak dari form
                // 'non_earmarked' => null,      // opsional
            ]);
    
            $templateId = $db->insertID(); // id dari insert induk
    
            // 2) Siapkan batch detail (desa & kecamatan) lalu insertBatch
            $detailRows = [];
    
            // Dari tab Desa
            foreach ($desa as $d) {
                if (!is_string($d)) continue;
                $nama = trim($d);
                if ($nama === '') continue;
    
                $detailRows[] = [
                    'nama_file'   => $nama,
                    'role'        => 'desa',
                    'id_templates'=> $templateId,
                ];
            }
    
            // Dari tab Kecamatan
            foreach ($kecamatan as $k) {
                if (!is_string($k)) continue;
                $nama = trim($k);
                if ($nama === '') continue;
    
                $detailRows[] = [
                    'nama_file'   => $nama,
                    'role'        => 'kecamatan',
                    'id_templates'=> $templateId,
                ];
            }
    
            if (!empty($detailRows)) {
                $db->table('document_templates_detail')->insertBatch($detailRows);
            }
    
            // Selesaikan transaksi
            $db->transComplete();
    
            if ($db->transStatus() === false) {
                session()->setFlashdata('error', 'Gagal menyimpan template.');
                return redirect()->back()->withInput();
            }
    
            session()->setFlashdata('success', 'Template berhasil disimpan.');
            return redirect()->to('/admindashboard') // ganti sesuai route dashboard kamu
            ->with('success', 'Template documents berhasil dibuat');
        
        }
    

public function getDocumentTemplates()
{
    $request = service('request');
    $model   = new DocumentTemplatesModel();

    $search   = $request->getGet('search') ?? null;
    $perPage  = (int) ($request->getGet('length') ?? 10);
    $page     = (int) ($request->getGet('page') ?? 1);
    $start    = ($page - 1) * $perPage; // 🔑 hitung offset manual

    // Total data
    $total = $model->getTemplatesQuery(session('user_id'), $search)->countAllResults();

    // Data per halaman
    $data = $model->getTemplatesQuery(session('user_id'), $search)
                  ->get($perPage, $start)
                  ->getResult();

    return $this->response->setJSON([
        'draw'            => $page,
        'recordsTotal'    => $total,
        'recordsFiltered' => $total,
        'data'            => $data,
    ]);

}


public function getKecamatanTemplates(){


    $request = service('request');
    $model = new DocumentTemplatesKecamatanModel();

    // Ambil parameter dari AJAX
    $search   = $request->getGet('search');
    $perPage  = $request->getGet('length') ?? 10;
    $page     = $request->getGet('page') ?? 1;

    $builder = $model->getTemplatesWithUser(session('user_id'), $search);

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


public function editDocument($id)
{
    $model = new DocumentTemplatesModel();
    $template = $model->getTemplateWithDetail($id);

    return $this->response->setJSON($template);
}

public function update_templates()
{
    $request = service('request');
    $db      = \Config\Database::connect();

    $id         = $request->getPost('id');
    $title      = $request->getPost('title');
    $deskripsi  = $request->getPost('deskripsi');
    $is_active  = $request->getPost('is_active'); // 1 atau 0
    $desa       = $request->getPost('desa') ?? [];
    $kecamatan  = $request->getPost('kecamatan') ?? [];

    // Mulai transaksi biar aman
    $db->transStart();

    // 🔹 Update parent document_templates
    $db->table('document_templates')
        ->where('id', $id)
        ->update([
            'title'      => $title,
            'deskripsi'  => $deskripsi,
            'is_active'  => $is_active,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

    // 🔹 Hapus detail lama
    $db->table('document_templates_detail')->where('id_templates', $id)->delete();

    // 🔹 Insert ulang detail desa
    foreach ($desa as $d) {
        if (!empty($d)) {
            $db->table('document_templates_detail')->insert([
                'id_templates' => $id,
                'nama_file'   => $d,
                'role'        => 'desa',
            ]);
        }
    }

    // 🔹 Insert ulang detail kecamatan
    foreach ($kecamatan as $k) {
        if (!empty($k)) {
            $db->table('document_templates_detail')->insert([
                'id_templates' => $id,
                'nama_file'   => $k,
                'role'        => 'kecamatan',
            ]);
        }
    }

    $db->transComplete();

    if ($db->transStatus() === false) {
        $error = $db->error();
        return redirect()->to('/admindashboard')->with('error', 'Update gagal: ' . ($error['message'] ?? 'Transaksi gagal.'));
    }

    // session()->setFlashdata('success', 'Template berhasil disimpan.');
    return redirect()->to('/admindashboard') // ganti sesuai route dashboard kamu
    ->with('success', 'Template documents berhasil diupdate');

}

public function deleteDesa($id){

    $db      = \Config\Database::connect();

    $db->transStart();
    $db->table('document_templates')->where('id', $id)->delete();
    $db->table('document_templates_detail')->where('id_templates', $id)->delete();

    $db->transComplete();

    if ($db->transStatus() === false) {
        $error = $db->error();
        return redirect()->to('/admindashboard')->with('error', 'hapus data gagal: ' . ($error['message'] ?? 'Transaksi gagal.'));
    }

    // session()->setFlashdata('success', 'Template berhasil disimpan.');
    return redirect()->to('/admindashboard') // ganti sesuai route dashboard kamu
    ->with('success', 'Template documents berhasil dihapus');
}


public function getDocumentDesa(){
    
}


}