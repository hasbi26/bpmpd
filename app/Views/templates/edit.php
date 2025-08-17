<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">
    <h1>Edit Template <?= ucfirst($type) ?></h1>
    
    
    <?php if(isset($errors)) : ?>
        <div class="alert alert-danger">
            <?php foreach($errors as $error) : ?>
                <p><?= $error ?></p>
            <?php endforeach ?>
        </div>
    <?php endif ?>
    
    <form action="<?= base_url("templates/$type/update/".$template['id']) ?>" method="post">
        <div class="form-group">
            <label>Judul Template</label>
            <input type="text" name="title" class="form-control" value="<?= $template['title'] ?>" required>
        </div>
        
        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3"><?= $template['deskripsi'] ?></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="<?= base_url("templates/$type") ?>" class="btn btn-secondary">Kembali</a>
    </form>
</div>
<?= $this->endSection() ?>