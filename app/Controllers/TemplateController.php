<?php namespace App\Controllers;

use App\Models\DocumentTemplatesDesaModel;
use App\Models\DocumentTemplatesKecamatanModel;

class TemplateController extends BaseController
{
    protected $templateDesaModel;
    protected $templateKecamatanModel;

    public function __construct()
    {
        $this->templateDesaModel = new DocumentTemplatesDesaModel();
        $this->templateKecamatanModel = new DocumentTemplatesKecamatanModel();
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

    public function storeDesa()
{
    $rules = [
        'title' => 'required|max_length[255]',
        'deskripsi' => 'permit_empty'
    ];

    if (!$this->validate($rules)) {
            return redirect()->to('/admindashboard') // ganti sesuai route dashboard kamu
            ->with('error', 'Template desa gagal di buat');
    }

    $data = [
        'title' => $this->request->getPost('title'),
        'deskripsi' => $this->request->getPost('deskripsi'),
        'created_by' => session('user_id')
    ];
    

    $this->templateDesaModel->save($data);
    
    return redirect()->to('/admindashboard') // ganti sesuai route dashboard kamu
    ->with('success', 'Template desa berhasil dibuat');
}

// Lakukan hal yang sama untuk updateDesa, storeKecamatan, dll

    // KECAMATAN TEMPLATES (Mirip dengan Desa)
    public function indexKecamatan()
    {
        $data = [
            'templates' => $this->templateKecamatanModel->getTemplatesByUser(session('user_id')),
            'type' => 'kecamatan'
        ];
        return view('templates/index', $data);
    }

    public function createKecamatan()
    {
        return view('templates/create', ['type' => 'kecamatan']);
    }

    public function storeKecamatan()
    {
        $rules = [
            'title' => 'required|max_length[255]',
            'deskripsi' => 'permit_empty'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/admindashboard') // ganti sesuai route dashboard kamu
            ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'created_by' => session('user_id')
        ];

        $this->templateKecamatanModel->save($data);
        return redirect()->to('/admindashboard') // ganti sesuai route dashboard kamu
        ->with('success', 'Template kecamatan berhasil dibuat');
    }

    // EDIT & DELETE (Contoh untuk Desa)
    public function editDesa($id)
    {
        $template = $this->templateDesaModel->find($id);
        return view('templates/edit', ['template' => $template, 'type' => 'desa']);
    }

    public function updateDesa()
    {
        $rules = [
            'title' => 'required|max_length[255]',
            'deskripsi' => 'permit_empty'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/admindashboard')->with('errors', $this->validator->getErrors());
        }

        $data = [
            'id' => $this->request->getPost('id'),
            'title' => $this->request->getPost('title'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'is_active' => ($this->request->getPost('is_active') == null) ? 0 : 1
        ];

        $this->templateDesaModel->save($data);
        return redirect()->to('/admindashboard')->with('success', 'Template desa berhasil diupdate');
    }


    public function updateKecamatan()
    {
        $rules = [
            'title' => 'required|max_length[255]',
            'deskripsi' => 'permit_empty'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/admindashboard')->with('errors', $this->validator->getErrors());
        }

        $data = [
            'id' => $this->request->getPost('id'),
            'title' => $this->request->getPost('title'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'is_active' => ($this->request->getPost('is_active') == null) ? 0 : 1
        ];

        $this->templateKecamatanModel->save($data);
        return redirect()->to('/admindashboard')->with('success', 'Template Kecamatan berhasil diupdate');
    }

    public function deleteDesa($id)
    {
        $this->templateDesaModel->delete($id);
        return redirect()->to('/admindashboard')->with('success', 'Template desa berhasil dihapus');
    }

    public function deleteKecamatan($id)
    {
        $this->templateKecamatanModel->delete($id);
        return redirect()->to('/admindashboard')->with('success', 'Template kecamatan berhasil dihapus');
    }


    // Di dalam TemplateController
public function loadTemplateContent($type)
{
    if ($type === 'desa') {
        $data['templates'] = $this->templateDesaModel->getTemplatesByUser(session('user_id'));
    } else {
        $data['templates'] = $this->templateKecamatanModel->getTemplatesByUser(session('user_id'));
    }
    
    $data['type'] = $type;
    
    return view('templates/partials/list', $data);
}

public function loadTemplateForm($type, $id = null)
{
    if ($id) {
        $model = $type === 'desa' ? $this->templateDesaModel : $this->templateKecamatanModel;
        $data['template'] = $model->find($id);
    }
    
    $data['type'] = $type;
    $view = $id ? 'templates/partials/edit_form' : 'templates/partials/create_form';
    
    return view($view, $data);
}


public function getDesaTemplates()
{
    $desaTemplates = $this->templateDesaModel->getTemplatesWithUser(session('user_id'));

    return $this->response->setJSON([
        'success' => true,
        'data' => $desaTemplates
    ]);
}


public function getKecamatanTemplates(){
    $kecamatanTemplates = $this->templateKecamatanModel->getTemplatesWithUser(session('user_id'));
    return $this->response->setJSON([
        'success' => true,
        'data' => $kecamatanTemplates
    ]);

}



}