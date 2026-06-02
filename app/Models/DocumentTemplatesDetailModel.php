<?php namespace App\Models;

use CodeIgniter\Model;

class DocumentTemplatesDetailModel extends Model
{
    protected $table      = 'document_templates_detail';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_file', 'role', 'id_templates'];

 
    
}
