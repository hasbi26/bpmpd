<h3 class="mb-3" id="content-title">KIB A - TANAH <?= esc(ucfirst($role)) ?>
    <?= esc(ucfirst(strtolower($namaWilayah))) ?>
</h3>


<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success">
    <?= session()->getFlashdata('success') ?>
</div>
<?php elseif (session()->getFlashdata('error')): ?>
<div class="alert alert-danger">
    <?= session()->getFlashdata('error') ?>
</div>
<?php endif; ?>

<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-lg-12 mx-auto">

                <div class="card card-primary card-outline mt-4">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="bi bi-upload me-1"></i> Import Data Aset Tanah
                        </h3>
                        <div class="card-tools">
                            <a href="<?= base_url('aset-tanah/export/excel') ?>" class="btn btn-sm btn-success">
                                <i class="bi bi-file-earmark-excel me-1"></i> Download Excel (Data Terakhir)
                            </a>
                            <a href="<?= base_url('aset-tanah/export/pdf') ?>" class="btn btn-sm btn-danger"
                                target="_blank">
                                <i class="bi bi-file-earmark-pdf me-1"></i> Download PDF
                            </a>
                        </div>
                    </div>

                    <div class="card-body">

                        <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= esc(session()->getFlashdata('success')) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= esc(session()->getFlashdata('error')) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Data gagal disimpan:</strong>
                            <ul class="mb-0 mt-2">
                                <?php foreach (session()->getFlashdata('errors') as $err): ?>
                                <li><?= esc(is_array($err) ? implode(' - ', $err) : $err) ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php endif; ?>

                        <p class="text-muted">
                            Unggah file excel data aset tanah sesuai template yang sudah disediakan.
                            Belum punya template?
                            <a href="<?= base_url('templates/template_aset_tanah.xlsx') ?>" download>
                                Unduh template di sini
                            </a>.
                        </p>

                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            <strong>Perhatian:</strong> mengupload file ini akan <strong>mengganti total</strong>
                            seluruh data aset tanah desa Anda yang sudah tersimpan sebelumnya.
                            Kalau hanya ingin menambah/mengubah sebagian data, download dulu data terbaru,
                            edit di file tersebut, baru upload ulang.
                        </div>

                        <?= form_open_multipart('aset-tanah/import') ?>

                        <div class="mb-3">
                            <label for="file_excel" class="form-label">File Excel (.xlsx / .xls)</label>
                            <input type="file" name="file_excel" id="file_excel" class="form-control"
                                accept=".xlsx,.xls" required>
                            <div class="form-text">Ukuran maksimal 5 MB.</div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-cloud-arrow-up me-1"></i> Upload &amp; Simpan
                        </button>

                        <?= form_close() ?>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>