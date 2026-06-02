<?php namespace App\Models;

use CodeIgniter\Model;

class DocumentSubmissionsModel extends Model
{
    protected $table = 'document_submissions';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'template_id','desa_id','earmarked','non_earmarked',
        'status_desa','status_kecamatan','status_kabupaten',
        'keterangan_kecamatan','keterangan_kabupaten','created_at','updated_at',
        'status_desa_at','status_kecamatan_at','status_kabupaten_at'
    ];
    protected $useTimestamps = false;


    public function getStatusDocument($search = null, $desa_id)
{
    $builder = $this->where('desa_id', $desa_id);
    if ($search) {
        $builder = $builder->like('title', $search)
                           ->orLike('deskripsi', $search);
    }

    return $builder->orderBy('created_at', 'DESC');
}

public function getStatusKecamatan($search = null, $kecamatan_id)
{
    $builder = $this->select('document_submissions.*, document_templates.title, desa.nama as desa_nama')
                   ->join('desa', 'desa.id = document_submissions.desa_id', 'inner')
                   ->join('document_templates', 'document_templates.id = document_submissions.template_id', 'inner')
                   ->where('desa.kecamatan_id', $kecamatan_id)
                   ->groupStart()
                        ->where('document_submissions.status_desa',"submitted")
                        ->orWhere('document_submissions.status_kecamatan',"rejected")
                    ->groupEnd();

    if ($search) {
        $builder->groupStart()
                ->like('document_templates.title', $search)
                ->orLike('desa.nama', $search)
                ->groupEnd();
    }

    return $builder->orderBy('document_submissions.created_at', 'DESC');
}


}


