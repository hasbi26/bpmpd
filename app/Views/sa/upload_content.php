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
    <tr>
        <td>1</td>
        <td>Import data Admin Excel ke Database</td>
        <td colspan="2">
            <form action="<?= base_url('import/admin') ?>" method="post" enctype="multipart/form-data" style="display:flex; gap:5px; align-items:center;">
                <input class="form-control form-control-sm" type="file" name="file_excel" accept=".xls,.xlsx" required>
                <button type="submit" class="btn btn-primary btn-sm">Upload</button>
            </form>
        </td>
    </tr>

    <tr>
        <td>2</td>
        <td>Import data kecamatan Excel ke Database</td>
        <td colspan="2">
            <form action="<?= base_url('import/kecamatan') ?>" method="post" enctype="multipart/form-data" style="display:flex; gap:5px; align-items:center;">
                <input type="hidden" name="doc_type" value="data_kecamatan">
                <input class="form-control form-control-sm" type="file" name="file_excel" accept=".xls,.xlsx" required>
                <button type="submit" class="btn btn-primary btn-sm">Upload</button>
            </form>
        </td>
    </tr>

    <tr>
        <td>3</td>
        <td>Import data Desa Excel ke Database</td>
        <td colspan="2">
            <form action="<?= base_url('import/desa') ?>" method="post" enctype="multipart/form-data" style="display:flex; gap:5px; align-items:center;">
                <input type="hidden" name="doc_type" value="data_desa">
                <input class="form-control form-control-sm" type="file" name="file_excel" accept=".xls,.xlsx" required>
                <button type="submit" class="btn btn-primary btn-sm">Upload</button>
            </form>
        </td>
    </tr>

    <tr>
        <td>4</td>
        <td>Import data User Excel ke Database</td>
        <td colspan="2">
            <form action="<?= base_url('import/proses') ?>" method="post" enctype="multipart/form-data" style="display:flex; gap:5px; align-items:center;">
                <input type="hidden" name="doc_type" value="data_user">
                <input class="form-control form-control-sm" type="file" name="file_excel" accept=".xls,.xlsx" required>
                <button type="submit" class="btn btn-primary btn-sm">Upload</button>
            </form>
        </td>
    </tr>
</tbody>


  </table>
</div>