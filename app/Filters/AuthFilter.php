<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = \Config\Services::session();
        
        // Jika belum login
        if (!$session->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        // Jika memerlukan role tertentu
        if (!empty($arguments)) {
            $userRole = $session->get('role');
            
            if (!in_array($userRole, $arguments)) {
                throw \CodeIgniter\Exceptions\PageForbiddenException::forPageForbidden();
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}