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
        <th>Pilih Dokumen</th>
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

