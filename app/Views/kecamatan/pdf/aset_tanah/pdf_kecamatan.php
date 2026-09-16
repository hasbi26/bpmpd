<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    @page { margin: 25px 30px; }
    body { font-family: Helvetica, Arial, sans-serif; font-size: 10px; color: #222; }
    h1 { font-size: 15px; text-align: center; margin: 0 0 2px 0; }
    h2 { font-size: 12px; text-align: center; margin: 0 0 14px 0; font-weight: normal; }
    .meta { margin-bottom: 10px; }
    .meta td { padding: 1px 4px; font-size: 10px; }
    table.data { width: 100%; border-collapse: collapse; margin-top: 6px; }
    table.data th, table.data td {
        border: 1px solid #666;
        padding: 4px 5px;
        font-size: 9px;
        vertical-align: top;
    }
    table.data th {
        background-color: #2C3E50;
        color: #fff;
        text-align: center;
    }
    table.data td.num { text-align: right; }
    table.data td.center { text-align: center; }

    .ttd-wrapper { margin-top: 40px; width: 100%; }
    .ttd-box {
        width: 260px;
        float: right;
        text-align: center;
        font-size: 10px;
    }
    .ttd-space { height: 70px; }
    .ttd-name { text-decoration: underline; font-weight: bold; }
    .clearfix::after { content: ""; display: table; clear: both; }
</style>
</head>
<body>

    <h1>LAPORAN DATA ASET TANAH</h1>
    <h2>
        Kecamatan <?= esc($kecamatan['nama']) ?>
        <?= $namaFilterDesa ? ' - Desa ' . esc($namaFilterDesa) : ' (Seluruh Desa)' ?>
    </h2>

    <table class="meta">
        <tr>
            <td><strong>Kecamatan</strong></td>
            <td>: <?= esc($kecamatan['nama']) ?></td>
            <td style="width: 30px;"></td>
            <td><strong>Tanggal Cetak</strong></td>
            <td>: <?= esc($tanggal_cetak) ?></td>
        </tr>
        <tr>
            <td><strong>Cakupan Desa</strong></td>
            <td>: <?= $namaFilterDesa ? esc($namaFilterDesa) : 'Seluruh desa di kecamatan ini' ?></td>
            <td style="width: 30px;"></td>
            <td><strong>Jumlah Aset</strong></td>
            <td>: <?= count($rows) ?> item</td>
        </tr>
        <tr>
            <td><strong>Total Luas</strong></td>
            <td>: <?= number_format($total_luas, 2, ',', '.') ?> m&sup2;</td>
            <td style="width: 30px;"></td>
            <td><strong>Total Nilai Perolehan</strong></td>
            <td>: Rp <?= number_format($total_nilai, 0, ',', '.') ?></td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th style="width:20px;">No</th>
                <th>Desa</th>
                <th>Kode Barang</th>
                <th>NUP</th>
                <th>Jenis Tanah</th>
                <th>Luas (m2)</th>
                <th>Tahun Perolehan</th>
                <th>Alas Hak</th>
                <th>Nilai Perolehan (Rp)</th>
                <th>Keterangan</th>
                <th>Tanggal Rekap</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($rows)): ?>
                <tr>
                    <td colspan="11" class="center">Belum ada data aset tanah.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($rows as $i => $row): ?>
                    <tr>
                        <td class="center"><?= $i + 1 ?></td>
                        <td><?= esc($row['nama_desa'] ?? '-') ?></td>
                        <td><?= esc($row['kode_barang'] ?? '-') ?></td>
                        <td class="center"><?= esc($row['nup'] ?? '-') ?></td>
                        <td><?= esc($row['nama_barang']) ?></td>
                        <td class="num"><?= $row['luas'] !== null ? number_format((float) $row['luas'], 2, ',', '.') : '-' ?></td>
                        <td class="center"><?= esc($row['tahun_perolehan'] ?? '-') ?></td>
                        <td><?= esc($row['alas_hak'] ?? '-') ?></td>
                        <td class="num"><?= $row['nilai_perolehan'] !== null ? number_format((float) $row['nilai_perolehan'], 0, ',', '.') : '-' ?></td>
                        <td><?= esc($row['keterangan'] ?? '-') ?></td>
                        <td class="center"><?= $row['tanggal_rekap'] ? esc(date('d-m-Y', strtotime($row['tanggal_rekap']))) : '-' ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="ttd-wrapper clearfix">
        <div class="ttd-box">
            <div>Mengetahui,</div>
            <div>Camat <?= esc($kecamatan['nama']) ?></div>
            <div class="ttd-space"></div>
            <div class="ttd-name">(.....................................)</div>
        </div>
    </div>

</body>
</html>
