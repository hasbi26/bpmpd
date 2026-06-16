<?php

namespace App\Libraries;

use App\Models\SubmissionLogModel;

class LogSubmission
{
    public static function save(
        $submissionId,
        $role,
        $actorId,
        $action,
        $statusSebelum,
        $statusSesudah,
        $keterangan = null
    ) {

        $model = new SubmissionLogModel();

        $result = $model->insert([
            'submission_id' => $submissionId,
            'actor_role' => $role,
            'actor_id' => $actorId,
            'action' => $action,
            'status_sebelum' => $statusSebelum,
            'status_sesudah' => $statusSesudah,
            'keterangan' => $keterangan
        ]);
        

        if (!$result) {
        
            dd([
                'model_errors' => $model->errors(),
                'db_error' => $model->db->error(),
                'last_query' => (string) $model->db->getLastQuery()
            ]);
        }
    }
}