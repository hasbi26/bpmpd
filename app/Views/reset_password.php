<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>

    <?php if(session()->getFlashdata('error')): ?>
        <p style="color:red"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>

    <form action="<?= base_url('process-reset-password') ?>" method="POST">
        <?= csrf_field() ?>
        <input type="hidden" name="token" value="<?= $token ?>">

        <label>Password Baru</label><br>
        <input type="password" name="password" required><br>

        <label>Konfirmasi Password</label><br>
        <input type="password" name="confirm" required><br>

        <button type="submit">Ubah Password</button>
    </form>
</body>
</html>
