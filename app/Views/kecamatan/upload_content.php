<h3 class="mb-3" id="content-title">Upload Document <?= esc(ucfirst($role)) ?> <?= esc(ucfirst(strtolower($namaWilayah))) ?></h3>

<div class="d-flex justify-content-between mt-2 mb-3">
    <div>
        <label>
            Tampilkan
            <select id="perPage" class="form-select d-inline-block w-auto">
                <option value="5">5</option>
                <option value="10" selected>10</option>
                <option value="25">25</option>
            </select>
            entri
        </label>
    </div>
    <div>
        <input type="text" id="searchInput" class="form-control" placeholder="Cari dokumen...">
    </div>
</div>


<div class="card-body p-0">
  <table class="table table-bordered">
    <thead>
      <tr>
        <th style="width: 50px">No</th>
        <th>Nama Dokumen</th>
        <th>Desa</th> 
        <th>Status</th>
        <th style="width: 120px">Aksi</th>
      </tr>
    </thead>
    <tbody id="documentKecamatan">
  </tbody>
  </table>
</div>

<div class="card-footer clearfix d-flex justify-content-center">

<div class="mt-2" id="pagination"></div>
</div>

<div class="modal fade" id="modalUploadKecamatan" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form action="<?= base_url('templates/kecamatan/upload_files') ?>" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id_template" id="id_template">
    <input type="hidden" name="desa_id" id="desa_id">
      <div class="modal-content">
      <div class="modal-header d-flex justify-content-between align-items-center">
      <h5 class="modal-title m-0"> <span id="uploadKecamatanTitle"></span>  <span id="desaTitle"></span></h5>
      </div>
        <div class="modal-body">

        <div class="mb-3">
          <div class="row">

          <div class="col-md-3">
            <label>Earmarked</label>
            <input disabled type="text" name="earmarked" id="earmarked" class="form-control">
          </div>
          <div class="col-md-3">
            <label>Non-Earmarked</label>
            <input disabled type="text"  name="non_earmarked" id="non_earmarked" class="form-control">
          </div>
          <div class="col-md-3">
            <label>Status Kecamatan</label>
            <input disabled type="text"  name="status_kecamatan" id="status_kecamatan" class="form-control">
          </div>
          <div class="col-md-3">
            <label>Status Desa</label>
            <select name="status_desa" id="status_desa" class="form-control">
              <option value="" disabled selected>-- Pilih Status --</option>
              <option value="approved">Approved</option>
              <!-- <option value="" disabled selected>Submitted</option> -->
              <option value="rejected">Rejected</option>
              <!-- <option value="" disabled selected>Resubmitted</option> -->
            </select>
          </div>
      </div>

      <div class="mb-3">
    <label for="exampleTextarea" class="form-label">Keterangan</label>
    <textarea class="form-control"  name="keterangan" id="keterangan" rows="2" placeholder=""></textarea>
    </div>

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
              <div id="uploadKecamatanBody"></div>
              </div>
            </div>

            <!-- Kecamatan Tab -->
            <div class="tab-pane fade" id="nav-kecamatan" role="tabpanel" aria-labelledby="nav-kecamatan-tab">
              <div id="kecamatan-container">
              <div id="uploadKecamatanBodyUpload"></div>
              </div>
            </div>
          </div>


        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary">Simpan</button>

        </div>
      </div>
    </form>
  </div>
</div>




