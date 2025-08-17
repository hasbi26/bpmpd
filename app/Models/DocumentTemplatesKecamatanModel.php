<?php namespace App\Models;

use CodeIgniter\Model;

class DocumentTemplatesKecamatanModel extends Model
{
    protected $table = 'document_templates_kecamatan';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'deskripsi', 'created_by', 'is_active'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getTemplatesByUser($userId)
    {
        return $this->where('created_by', $userId)->findAll();
    }

    public function getTemplatesWithUser($userId = null)
    {
        $builder = $this->select('document_templates_kecamatan.*, user_admin.username, user_admin.email') // kolom dari kedua tabel
                        ->join('user_admin', 'user_admin.id = document_templates_kecamatan.created_by', 'left');
    
        if ($userId !== null) {
            $builder->where('document_templates_kecamatan.created_by', $userId);
        }
    
        return $builder->findAll();
    }



    public function getActiveTemplates($search = null)
    {
        $builder = $this->where('is_active', 1);
        if ($search) {
            $builder = $builder->like('title', $search)
                            ->orLike('deskripsi', $search);
        }
        return $builder->orderBy('created_at', 'DESC');
    }
}