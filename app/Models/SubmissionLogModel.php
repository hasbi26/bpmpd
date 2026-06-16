<?php

namespace App\Models;

use CodeIgniter\Model;

class SubmissionLogModel extends Model
{
    protected $table = 'document_submission_logs';

    protected $allowedFields = [
        'submission_id',
        'actor_role',
        'actor_id',
        'action',
        'status_sebelum',
        'status_sesudah',
        'keterangan'
    ];
}