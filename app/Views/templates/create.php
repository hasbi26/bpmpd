<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">
    <h1>Template <?= ucfirst($type) ?></h1>
    
    <?php if(session('message')) : ?>
        <div class="alert alert-success"><?= session('message') ?></div>
    <?php endif ?>
    
    <a href="<?= base_url("templates/$type/create") ?>" class="btn btn-primary mb-3">Buat Template Baru</a>
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Deskripsi</th>
                <th>Tanggal Dibuat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($templates as $index => $template) : ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><?= $template['title'] ?></td>
                <td><?= $template['deskripsi'] ?></td>
                <td><?= date('d/m/Y H:i', strtotime($template['created_at'])) ?></td>
                <td>
                    <a href="<?= base_url("templates/$type/edit/".$template['id']) ?>" class="btn btn-warning">Edit</a>
                    <a href="<?= base_url("templates/$type/delete/".$template['id']) ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>