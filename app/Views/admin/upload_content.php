<!-- Content Wrapper -->
<section class="content">
  <div class="container-fluid">
    <h3 class="mb-3">Create Templates Documents</h3>

    <div class="row mb-3 align-items-center">
  <!-- Tombol Tambah Template Desa -->
  <div class="col-auto">
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCreateDesa">
      <i class="fas fa-plus"></i> Tambah Template Desa
    </button>
  </div>

  <!-- Dropdown tampilkan entri -->
  <div class="col-auto">
    <label class="mb-0">
      Tampilkan
      <select id="perPage" class="form-select d-inline-block w-auto">
        <option value="5">5</option>
        <option value="10" selected>10</option>
        <option value="25">25</option>
      </select>
      entri
    </label>
  </div>

  <!-- Search -->
  <div class="col ms-auto">
    <input type="text" id="searchInput" class="form-control" placeholder="Cari dokumen...">
  </div>
</div>


    <!-- Tabel -->
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
      <tbody id="desaTableBody"></tbody>
    </table>

    <!-- Pagination -->
    <div class="card-footer clearfix d-flex justify-content-center">
      <div class="mt-2" id="pagination"></div>
    </div>
  </div>
</section>

<!-- Modal Create Desa -->
<div class="modal fade" id="modalCreateDesa" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <form action="<?= base_url('templates/create_templates') ?>" method="POST">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Template Desa</h5>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Document Title</label>
            <input type="text" name="title" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control"></textarea>
          </div>

          <!-- Tabs Desa/Kecamatan -->
          <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
              <button class="nav-link active" id="nav-desa-tab" data-bs-toggle="tab" data-bs-target="#nav-desa" type="button" role="tab" aria-controls="nav-desa" aria-selected="true">Desa</button>
              <button class="nav-link" id="nav-kecamatan-tab" data-bs-toggle="tab" data-bs-target="#nav-kecamatan" type="button" role="tab" aria-controls="nav-kecamatan" aria-selected="false">Kecamatan</button>
            </div>
          </nav>

          <div class="tab-content mt-3" id="nav-tabContent">
            <!-- Desa Tab -->
            <div class="tab-pane fade show active" id="nav-desa" role="tabpanel" aria-labelledby="nav-desa-tab">
              <div id="desa-container">
                <div class="input-group mb-2 desa-input">
                  <input type="text" name="desa[]" class="form-control" placeholder="Nama Document Desa">
                  <button class="btn btn-danger remove-desa" type="button">Hapus</button>
                </div>
              </div>
              <button id="add-desa" class="btn btn-primary" type="button">+ Tambah Document Desa</button>
            </div>

            <!-- Kecamatan Tab -->
            <div class="tab-pane fade" id="nav-kecamatan" role="tabpanel" aria-labelledby="nav-kecamatan-tab">
              <div id="kecamatan-container">
                <div class="input-group mb-2 kecamatan-input">
                  <input type="text" name="kecamatan[]" class="form-control" placeholder="Nama Document Kecamatan">
                  <button class="btn btn-danger remove-kecamatan" type="button">Hapus</button>
                </div>
              </div>
              <button id="add-kecamatan" class="btn btn-primary" type="button">+ Tambah Document Kecamatan</button>
            </div>
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
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <form id="formEditDesa" action="<?= base_url('templates/update_templates') ?>" method="POST">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Template Desa</h5>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="edit_id">

          <div class="mb-3">
            <label>Document Title</label>
            <input type="text" name="title" id="edit_title" class="form-control" required>
          </div>

          <input type="hidden" name="is_active" value="0">
        <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="edit_is_active" name="is_active" value="1">
        <label class="form-check-label" for="edit_is_active">Aktif</label>
        </div>

          <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="deskripsi" id="edit_deskripsi" class="form-control"></textarea>
          </div>

          <!-- Tabs Desa/Kecamatan -->
          <nav>
            <div class="nav nav-tabs" id="nav-tab-edit" role="tablist">
              <button class="nav-link active" id="nav-desa-edit-tab" data-bs-toggle="tab" data-bs-target="#nav-desa-edit" type="button" role="tab">Desa</button>
              <button class="nav-link" id="nav-kecamatan-edit-tab" data-bs-toggle="tab" data-bs-target="#nav-kecamatan-edit" type="button" role="tab">Kecamatan</button>
            </div>
          </nav>

          <div class="tab-content mt-3" id="nav-tabContent-edit">
            <!-- Desa Tab -->
            <div class="tab-pane fade show active" id="nav-desa-edit" role="tabpanel">
              <div id="desa-container-edit"></div>
              <button id="add-desa-edit" class="btn btn-primary" type="button">+ Tambah Document Desa</button>
            </div>

            <!-- Kecamatan Tab -->
            <div class="tab-pane fade" id="nav-kecamatan-edit" role="tabpanel">
              <div id="kecamatan-container-edit"></div>
              <button id="add-kecamatan-edit" class="btn btn-primary" type="button">+ Tambah Document Kecamatan</button>
            </div>
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