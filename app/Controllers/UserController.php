<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserController extends BaseController
{
    public function updateEmail()
    {
        $userId = session()->get('user_id');
        $role = session()->get('role');

        if (!$userId) {
            return redirect()->to('/')->with('error', 'Anda harus login terlebih dahulu.');
        }
        $newEmail = $this->request->getPost('email');

        if (! filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            return redirect()->to("{$role}/dashboard")->with('error', 'Email tidak valid.');
        }

        $userModel = new UserModel();
        $userModel->update($userId, [
            'email' => $newEmail
        ]);

        // Perbarui email di session agar langsung terlihat di halaman
        session()->set('email', $newEmail);
        return redirect()->to("{$role}/dashboard")->with('success', 'Email berhasil diperbarui.');
    }


    public function updatePassword()
    {
        $session = session();
        $userId = $session->get('user_id'); // ID user dari session login
        $role = session()->get('role');

        
        if (!$userId) {
            return redirect()->to('/')->with('error', 'Anda harus login terlebih dahulu.');
        }

        $newPassword = $this->request->getPost('ChangePassword');
        $confirmPassword = $this->request->getPost('confirmPassword');

        // Validasi input
        if (empty($newPassword) || empty($confirmPassword)) {
            return redirect()->to("{$role}/dashboard")->with('error', 'Password tidak boleh kosong.');
        }

        if ($newPassword !== $confirmPassword) {
            return redirect()->to("{$role}/dashboard")->with('error', 'Password konfirmasi tidak sama');
        }

        // Update password
        $userModel = new UserModel();
        $updated = $userModel->updatePassword($userId, $newPassword);

        if ($updated) {
            return redirect()->to("{$role}/dashboard")->with('success', 'Password berhasil diperbarui.');
        } else {
            return redirect()->to("{$role}/dashboard")->with('error', 'Gagal memperbarui password.');
        }
    }
}