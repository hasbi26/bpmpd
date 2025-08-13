<?php

namespace App\Models;

use CodeIgniter\Model;

class UserAdminModel extends Model
{
    protected $table = 'user_admin';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'password', 'email', 'role', 'role_id', 'is_active', 'created_by' ,'updated_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $failedLoginTable = 'user_failed_logins';

    /**
     * Mencari user berdasarkan username dan role
     */
    public function getUserByUsernameAndRole(string $username, string $role)
    {
        $builder = $this->db->table('user_admin u')
            ->where('u.username', $username)
            ->where('u.role', $role)
            ->where('u.is_active', 1);

        $builder->select('u.id as user_id, u.username, u.password, u.role, u.role_id, u.is_active, NULL as wilayah_nama');
    
        return $builder->get()->getRowArray();
    }
    

//     public function getUserByUsernameAndRole(string $username, string $role)
// {
//     $builder = $this->select('user.*, desa.*, kecamatan.*') // kita bisa sesuaikan nanti
//                     ->where('user.username', $username)
//                     ->where('user.role', $role)
//                     ->where('user.is_active', 1);

//     if ($role === 'desa') {
//         $builder->join('desa', 'desa.id = user.role_id', 'left');
//     } elseif ($role === 'kecamatan') {
//         $builder->join('kecamatan', 'kecamatan.id = user.role_id', 'left');
//     }

//     return $builder->first();
// }




    /**
     * Mencatat upaya login gagal
     */
    public function logFailedAttempt(int $userId, $request)
    {
        $request = service('request');
        $db = db_connect();
        
        // Buat tabel jika belum ada
        if (!$db->tableExists($this->failedLoginTable)) {
            $db->query("CREATE TABLE {$this->failedLoginTable} (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                attempt_time DATETIME DEFAULT CURRENT_TIMESTAMP,
                ip_address VARCHAR(45) NOT NULL,
                user_agent VARCHAR(255) NOT NULL,
                FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
            )");
        }
        
        // Insert record
        $db->table($this->failedLoginTable)->insert([
            'user_id' => $userId,
            'ip_address' => $request->getIPAddress(),
            'user_agent' => $request->getUserAgent()->getAgentString()
        ]);
        
        // Dapatkan jumlah upaya gagal dalam 15 menit terakhir
        $recentAttempts = $db->table($this->failedLoginTable)
            ->where('user_id', $userId)
            ->where('attempt_time >', date('Y-m-d H:i:s', strtotime('-15 minutes')))
            ->countAllResults();
        
        // Jika lebih dari 5 kali, nonaktifkan akun sementara
        if ($recentAttempts >= 5) {
            $this->update($userId, ['is_active' => 0]);
        }
    }

    /**
     * Mengecek apakah akun terkunci karena terlalu banyak upaya gagal
     */
    public function isAccountLocked(int $userId): bool
    {
        $db = db_connect();
        
        if (!$db->tableExists($this->failedLoginTable)) {
            return false;
        }
        
        $lastAttempt = $db->table($this->failedLoginTable)
            ->where('user_id', $userId)
            ->orderBy('attempt_time', 'DESC')
            ->get(1)
            ->getRow();
        
        if (!$lastAttempt) {
            return false;
        }
        
        // Jika akun dinonaktifkan dalam 15 menit terakhir
        $user = $this->find($userId);
        if (!$user['is_active'] && strtotime($user['updated_at']) > strtotime('-15 minutes')) {
            return true;
        }
        
        return false;
    }
}