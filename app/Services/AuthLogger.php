<?php

namespace App\Services;

use CodeIgniter\HTTP\RequestInterface;
use Config\Database;

class AuthLogger
{
    protected $request;
    protected $db;
    protected $failedLoginTable = 'user_failed_logins';

    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
        $this->db = Database::connect();
    }

    public function logFailedAttempt(int $userId)
    {
        // Buat tabel jika belum ada
        if (!$this->db->tableExists($this->failedLoginTable)) {
            $this->createFailedLoginsTable();
        }
        
        // Insert record
        $this->db->table($this->failedLoginTable)->insert([
            'user_id' => $userId,
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString()
        ]);
    }

    protected function createFailedLoginsTable()
    {
        $this->db->query("CREATE TABLE {$this->failedLoginTable} (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            attempt_time DATETIME DEFAULT CURRENT_TIMESTAMP,
            ip_address VARCHAR(45) NOT NULL,
            user_agent VARCHAR(255) NOT NULL,
            FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
        )");
    }
}