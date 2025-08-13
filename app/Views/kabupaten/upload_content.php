<h3 class="mb-3" id="content-title">Upload Document <?= esc(ucfirst($role)) ?> <?= esc(ucfirst(strtolower($namaWilayah))) ?></h3>

<div class="card-body p-0">
  <table class="table table-bordered">
    <thead>
      <tr>
        <th style="width: 50px">No</th>
        <th>Nama Dokumen</th>
        <th>Pilih Dokumen</th>
        <th style="width: 120px">Aksi</th>
      </tr>
    </thead>
    <tbody>
      <!-- Baris 1 -->
      <tr>
        <td>1</td>
        <td>Laporan realisasi dan capaian keluaran TA 2024 (laporan penyerapan Dana Desa (PMK) Tahun 2024 keluaran Siskeudesa)</td>
        <td>
          <form action="/upload" method="post" enctype="multipart/form-data">
            <input type="hidden" name="doc_type" value="laporan_realisasi">
            <input class="form-control form-control-sm" type="file" id="document1" name="document1" required>
        </td>
        <td>
            <button type="submit" class="btn btn-primary btn-sm">Upload</button>
          </form>
        </td>
      </tr>
      
      <!-- Baris 2 -->
      <tr>
        <td>2</td>
        <td>Surat Komitmen pembentukan KDMP</td>
        <td>
          <form action="/upload" method="post" enctype="multipart/form-data">
            <input type="hidden" name="doc_type" value="surat_komitmen">
            <input class="form-control form-control-sm" type="file" id="document2" name="document2" required>
        </td>
        <td>
            <button type="submit" class="btn btn-primary btn-sm">Upload</button>
          </form>
        </td>
      </tr>
    </tbody>
  </table>
</div>