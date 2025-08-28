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
  <table id="documentTable" class="table table-bordered">
    <thead>
      <tr>
        <th style="width: 50px">No</th>
        <th>Nama Dokumen</th>
        <th>Deskripsi</th> 
        <th style="width: 120px">Aksi</th>
      </tr>
    </thead>
    <tbody id="documentDesa">
  </tbody>
  </table>
</div>

<div class="card-footer clearfix d-flex justify-content-center">

<div class="mt-2" id="pagination"></div>

</div>




<div class="modal fade" id="modalUploadDesa" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form action="<?= base_url('templates/upload_files') ?>" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="id_template" id="id_template">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Detail Template : <span id="uploadDesaTitle"></span></h5>
        </div>
        <div class="modal-body">

          <div class="mb-3">
            <label>Earmarked</label>
            <input type="text" id="earmarked_display" class="form-control">
            <input type="hidden" name="earmarked" id="earmarked">
          </div>
          <div class="mb-3">
          <label>Non Earmarked</label>
            <input type="text" id="non_earmarked_display" class="form-control">
            <input type="hidden" name="non_earmarked" id="non_earmarked">
          </div>

          <div id="uploadDesaBody"></div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>

