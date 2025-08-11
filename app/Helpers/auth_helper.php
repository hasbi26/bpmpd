<?php

use Config\Services;

if (!function_exists('logged_in')) {
    /**
     * Cek apakah user sudah login
     */
    function logged_in(): bool
    {
        $session = Services::session();
        return $session->has('logged_in') && $session->get('logged_in') === true;
    }
}

if (!function_exists('user')) {
    /**
     * Mengambil data user dari session
     */
    function user()
    {
        $session = Services::session();
        if (!logged_in()) {
            return null;
        }

        return (object)[
            'id' => $session->get('user_id'),
            'username' => $session->get('username'),
            'role' => $session->get('role'),
            'role_id' => $session->get('role_id')
        ];
    }
}

if (!function_exists('is_desa')) {
    function is_desa(): bool
    {
        return logged_in() && user()->role === 'desa';
    }
}

if (!function_exists('is_kecamatan')) {
    function is_kecamatan(): bool
    {
        return logged_in() && user()->role === 'kecamatan';
    }
}

if (!function_exists('is_kabupaten')) {
    function is_kabupaten(): bool
    {
        return logged_in() && user()->role === 'kabupaten';
    }
}