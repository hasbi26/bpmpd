<h3 class="mb-3" id="content-title">Profil Desa di <?= esc(ucfirst($role)) ?> <?= esc(ucfirst(strtolower($namaWilayah))) ?> Sumedang </h3>
<div class="d-flex justify-content-between mt-2 mb-3">
    <div>
        <label>
            Tampilkan
            <select id="perPageOnprofilDesa" class="form-select d-inline-block w-auto">
                <option value="5">5</option>
                <option value="10" selected>10</option>
                <option value="25">25</option>
            </select>
            entri
        </label>
    </div>
    <div>
        <input type="text" id="searchprofilDesa" class="form-control" placeholder="Cari dokumen...">
    </div>
</div>


<!-- begin table -->
              <div class="card-body p-0">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th style="width: 10px">No</th>
                          <th>Nama Desa</th>
                          <th>Kepala Desa</th>
                          <th>No Rekening</th>
                          <th>Alamat</th>
                          <th>Kecamatan</th>
                        </tr>
                      </thead>
                      <tbody id="profilDesa">
                        
                      </tbody>
                    </table>
                  </div>

              <!-- end table -->

<div class="card-footer clearfix d-flex justify-content-center">

<div class="mt-2" id="paginationprofilDesa"></div>

</div>
