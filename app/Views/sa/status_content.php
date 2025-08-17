<h3 class="mb-3" id="content-title">Status Document <?= esc(ucfirst($role)) ?> <?= esc(ucfirst(strtolower($namaWilayah))) ?> </h3>
        <!-- begin table -->
              <div class="card-body p-0">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th style="width: 10px">No</th>
                          <th>Tanggal Upload</th>
                          <th>Desa</th>
                          <th>Jenis Dokumen</th>
                          <th>Keterangan</th>
                          <th>Status</th>
                          <th>Verifikasi</th>
                          <th>Tanggal Verifikasi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr class="align-middle">
                          <td>1.</td>
                          <td>07/08/2025 8:57:09</td>
                          <td>Cimalaka</td>
                          <td>Laporan realisasi dan capaian keluaran TA 2024  (laporan penyerapan Dana Desa (PMK) Tahun  2024 keluaran Siskeudesa)</td>
                          <td>ada kesalahan tanggal segera perbaiki</td>
                          <td><span class="badge text-bg-danger">Ditolak</span></td>
                          <td><a href="<?= base_url('') ?>" class="btn btn-info btn-flat float-end">Verifikasi</a></td>
                          <td>07/08/2025 8:57:09</td>
                        </tr>

                        <tr class="align-middle">
                          <td>2.</td>
                          <td>07/09/2025 10:57:09</td>
                          <td>Galudra</td>
                          <td>Surat Komitmen pembentukan KDMP</td>
                          <td>Proses Verifikasi Kabupaten </td>
                          <td><span class="badge text-bg-success">Diterima</span></td>
                          <td> <a href="<?= base_url('') ?>" class="btn btn-info btn-flat float-end">Verifikasi</a></td>
                          <td>07/08/2025 8:57:09</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

              <!-- end table -->