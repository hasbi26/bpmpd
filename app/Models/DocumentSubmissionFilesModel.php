<?php namespace App\Models;

use CodeIgniter\Model;

class DocumentSubmissionFilesModel extends Model
{
    protected $table = 'document_submission_files';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'submission_id','template_detail_id','uploader_role',
        'file_path','file_name','file_size','status_verifikasi','catatan','uploaded_at'
    ];
    protected $useTimestamps = false;
}
