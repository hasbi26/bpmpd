<h3 class="mb-3" id="content-title">KIB B - KENDARAAN DESA DI <?= esc(ucfirst(strtoupper($role))) ?>
    <?= esc(ucfirst(strtoupper($namaWilayah))) ?>
</h3>

<div class="row">
    <div class="col-12">

        <div class="card card-outline card-secondary mt-4">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-table me-1"></i> Data Aset Kendaraan Seluruh Desa
                </h3>
                <div class="card-tools">
                    <a href="<?= base_url('kecamatan/aset-kendaraan/export/pdf') . ($selectedDesaId ? '?desa_id=' . $selectedDesaId : '') ?>"
                       class="btn btn-sm btn-danger" target="_blank">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Download PDF
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-2 mb-3">
                    <div class="col-auto">
                        <label for="desa_id" class="col-form-label">Filter Desa:</label>
                    </div>
                    <div class="col-auto">
                        <select name="desa_id" id="desa_id" class="form-select"
                                onchange="loadContent('kendaraan', this.value ? { desa_id: this.value } : {})">
                            <option value="">-- Semua Desa --</option>
                            <?php foreach ($desaList as $d): ?>
                                <option value="<?= esc($d['id']) ?>" <?= (string) $selectedDesaId === (string) $d['id'] ? 'selected' : '' ?>>
                                    <?= esc($d['nama']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php if (!empty($selectedDesaId)): ?>
                        <div class="col-auto">
                            <a href="#" onclick="loadContent('kendaraan'); return false;" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i> Reset Filter
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="tableAsetKendaraan" class="table table-striped table-bordered table-sm mb-0 datatable-auto">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Desa</th>
                                <th>Kode Barang</th>
                                <th>NUP</th>
                                <th>Jenis Kendaraan</th>
                                <th>Merk/Tipe</th>
                                <th>Tahun Perolehan</th>
                                <th>Nomor Identitas</th>
                                <th class="text-end">Nilai Perolehan (Rp)</th>
                                <th>Kondisi</th>
                                <th>Keterangan</th>
                                <th>Tanggal Rekap</th>
                                <th>Foto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rows as $i => $row): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= esc($row['nama_desa'] ?? '-') ?></td>
                                <td><?= esc($row['kode_barang'] ?? '-') ?></td>
                                <td><?= esc($row['nup'] ?? '-') ?></td>
                                <td><?= esc($row['nama_barang']) ?></td>
                                <td><?= esc($row['merk_tipe'] ?? '-') ?></td>
                                <td><?= esc($row['tahun_perolehan'] ?? '-') ?></td>
                                <td><?= esc($row['nomor_identitas'] ?? '-') ?></td>
                                <td class="text-end">
                                    <?= $row['nilai_perolehan'] !== null ? number_format((float) $row['nilai_perolehan'], 0, ',', '.') : '-' ?>
                                </td>
                                <td class="text-center"><?= esc($row['kondisi'] ?? '-') ?></td>
                                <td><?= esc($row['keterangan'] ?? '-') ?></td>
                                <td><?= $row['tanggal_rekap'] ? esc(date('d-m-Y', strtotime($row['tanggal_rekap']))) : '-' ?>
                                </td>
                                <td>
                                    <?php if (!empty($row['foto'])): ?>
                                    <a href="<?= esc($row['foto']) ?>" target="_blank" rel="noopener">Lihat</a>
                                    <?php else: ?>
                                    -
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <?php
                            $totalNilai = 0;
                            foreach ($rows as $row) { $totalNilai += (float) ($row['nilai_perolehan'] ?? 0); }
                        ?>
                        <tfoot>
                            <tr class="fw-bold table-light">
                                <td colspan="8" class="text-end">Total</td>
                                <td class="text-end"><?= number_format($totalNilai, 0, ',', '.') ?></td>
                                <td colspan="4"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
