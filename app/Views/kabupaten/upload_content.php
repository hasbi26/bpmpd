<h3 class="mb-3" id="content-title">Verifikasi Document <?= esc(ucfirst($role)) ?> <?= esc(ucfirst(strtolower($namaWilayah))) ?></h3>

<div class="d-flex justify-content-between mt-2 mb-3">
    <div>
        <label>
            Tampilkan
            <select id="perPageStatus" class="form-select d-inline-block w-auto">
                <option value="5">5</option>
                <option value="10" selected>10</option>
                <option value="25">25</option>
            </select>
            entri
        </label>
    </div>
    <div>
        <input type="text" id="searchInputStatus" class="form-control" placeholder="Cari dokumen...">
    </div>
</div>


<!-- begin table -->
              <div class="card-body p-0">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th style="width: 10px">No</th>
                          <th>Tanggal Upload</th>
                          <th>Nama Document</th>
                          <th>Desa</th>
                          <th>Kecamatan</th>
                          <th>Status</th>
                          <th>Detail</th>
                        </tr>
                      </thead>
                      <tbody id="desaStatusBody">
                        
                      </tbody>
                    </table>
                  </div>

              <!-- end table -->

<div class="card-footer clearfix d-flex justify-content-center">

<div class="mt-2" id="paginationStatus"></div>

</div>



<div class="modal fade" id="modalUploadKabupaten" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form action="<?= base_url('templates/kabupaten/upload_files') ?>" method="POST" enctype="multipart/form-data">
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
          <div class="col-md-6">
  <label>Status</label>
  <select name="status_kecamatan" id="status_kecamatan" class="form-control">
    <option value="" disabled selected>-- Pilih Status --</option>
    <option value="approved">Approved</option>
    <option value="" disabled selected>Submitted</option>
    <option value="rejected">Rejected</option>
    <option value="" disabled selected>Resubmitted</option>
  </select>
</div>
      </div>

      <div class="mb-3">
    <label for="exampleTextarea" class="form-label">Keterangan</label>
    <textarea class="form-control" id="exampleTextarea" name="keterangan" id="keterangan_kecamatan" rows="2" placeholder=""></textarea>
    </div>

    <div id="uploadKecamatanBody"></div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary">Simpan</button>

        </div>
      </div>
    </form>
  </div>
</div>


