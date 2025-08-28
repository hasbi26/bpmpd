<?php namespace App\Models;

use CodeIgniter\Model;

class DocumentTemplatesModel extends Model
{
    protected $table      = 'document_templates';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'deskripsi', 'created_by', 'is_active', 'created_by'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getTemplatesQuery($userId, $search = null)
    {
        $builder = $this->db->table('document_templates dt')
            ->select('dt.*, u.username')
            ->join('user_admin u', 'u.id = dt.created_by', 'left')
            ->where('dt.created_by', $userId)
            ->orderBy('dt.created_at', 'DESC'); // 🔑 urutkan terbaru dulu

        if (!empty($search)) {
            $builder->groupStart()
                ->like('dt.title', $search)
                ->orLike('dt.deskripsi', $search)
                ->orLike('u.username', $search)
            ->groupEnd();
        }

        return $builder;
    }

    public function getTemplates($userId, $search = null, $perPage = 10, $page = 1)
    {
        return $this->getTemplatesQuery($userId, $search)
            ->get($perPage, ($page - 1) * $perPage)
            ->getResult();
    }

    public function countTemplates($userId, $search = null)
    {
        return $this->getTemplatesQuery($userId, $search)->countAllResults();
    }

    // 🔑 Method tambahan: ambil detail saat edit
    public function getTemplateWithDetail($id)
    {
        $builder = $this->db->table('document_templates dt')
            ->select('dt.*, u.username, dtd.id as detail_id, dtd.nama_file, dtd.role')
            ->join('user_admin u', 'u.id = dt.created_by', 'left')
            ->join('document_templates_detail dtd', 'dtd.id_templates = dt.id', 'left')
            ->where('dt.id', $id);

        return $builder->get()->getResult();
    }
    
}
