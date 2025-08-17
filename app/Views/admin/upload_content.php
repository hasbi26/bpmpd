<!-- Content Wrapper -->
    <section class="content">
        <div class="container-fluid">
            <h3 class="mb-3">Create Templates Documents</h3>
            <!-- Tabs -->
            <ul class="nav nav-tabs" id="templateTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="desa-tab" data-bs-toggle="tab" href="#desa" role="tab">Template Desa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="kecamatan-tab" data-bs-toggle="tab" href="#kecamatan" role="tab">Template Kecamatan</a>
                </li>
            </ul>

            <div class="tab-content mt-3" id="templateTabsContent">
                <!-- Tab Desa -->
                <div class="tab-pane fade show active" id="desa" role="tabpanel">
                    <button class="btn btn-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#modalCreateDesa">
                        <i class="fas fa-plus"></i> Tambah Template Desa
                    </button>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th>Judul</th>
                                <th>Deskripsi</th>
                                <th>Dibuat Oleh</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="desaTableBody">
                            <tr>
                                <td colspan="6" class="text-center">Memuat data...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Tab Kecamatan -->
                <div class="tab-pane fade" id="kecamatan" role="tabpanel">
                    <button class="btn btn-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#modalCreateKecamatan">
                        <i class="fas fa-plus"></i> Tambah Template Kecamatan
                    </button>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th>Judul</th>
                                <th>Deskripsi</th>
                                <th>Dibuat Oleh</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="kecamatanTableBody">
                        <tr>
                                <td colspan="6" class="text-center">Memuat data...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

<!-- Modal Create Desa -->
<div class="modal fade" id="modalCreateDesa" tabindex="-1">
  <div class="modal-dialog">
    <form action="<?= base_url('templates/create_desa') ?>" method="POST">
      <div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Tambah Template Desa</h5></div>
        <div class="modal-body">
            <div class="mb-3">
                <label>Judul</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit Desa -->
<div class="modal fade" id="modalEditDesa" tabindex="-1">
  <div class="modal-dialog">
    <form action="<?= base_url('templates/update_desa') ?>" method="POST">
      <div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Edit Template Desa</h5></div>
        <div class="modal-body">
            <input type="hidden" name="id" id="desa_id">
            <div class="mb-3">
                <label>Judul</label>
                <input type="text" name="title" id="desa_title" class="form-control" required>
            </div>

            <div class="form-check">
            <input class="form-check-input" type="checkbox" id="desa_is_active" name="is_active" value="1">
            <label class="form-check-label" for="desa_is_active">
                Aktif
            </label>
            </div>

            
            <div class="mb-3">
                <label>Deskripsi</label>
                <textarea name="deskripsi" id="desa_deskripsi" class="form-control"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Modal Create Kecamatan -->
<div class="modal fade" id="modalCreateKecamatan" tabindex="-1">
  <div class="modal-dialog">
    <form action="<?= base_url('templates/create_kecamatan') ?>" method="POST">
      <div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Tambah Template Kecamatan</h5></div>
        <div class="modal-body">
            <div class="mb-3">
                <label>Judul</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit Kecamatan -->
<div class="modal fade" id="modalEditKecamatan" tabindex="-1">
  <div class="modal-dialog">
    <form action="<?= base_url('templates/update_kecamatan') ?>" method="POST">
      <div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Edit Template Kecamatan</h5></div>
        <div class="modal-body">
            <input type="hidden" name="id" id="kecamatan_id">
            <div class="mb-3">
                <label>Judul</label>
                <input type="text" name="title" id="kecamatan_title" class="form-control" required>
            </div>

            <div class="form-check">
            <input class="form-check-input" type="checkbox" id="kecamatan_is_active" name="is_active" value="1">
            <label class="form-check-label" for="kecamatan_is_active">
                Aktif
            </label>
            </div>


            <div class="mb-3">
                <label>Deskripsi</label>
                <textarea name="deskripsi" id="kecamatan_deskripsi" class="form-control"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </div>
    </form>
  </div>
</div>









<script>
    // Fill Edit Desa Modal
    document.querySelectorAll('.btnEditDesa').forEach(btn => {
        btn.addEventListener('click', function(){
            document.getElementById('desa_id').value = this.dataset.id;
            document.getElementById('desa_title').value = this.dataset.title;
            document.getElementById('desa_deskripsi').value = this.dataset.deskripsi;
        });
    });

    // Fill Edit Kecamatan Modal
    document.querySelectorAll('.btnEditKecamatan').forEach(btn => {
        btn.addEventListener('click', function(){
            document.getElementById('kecamatan_id').value = this.dataset.id;
            document.getElementById('kecamatan_title').value = this.dataset.title;
            document.getElementById('kecamatan_deskripsi').value = this.dataset.deskripsi;
        });
    });
</script>


