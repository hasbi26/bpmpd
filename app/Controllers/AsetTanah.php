<?php

namespace App\Controllers;

use App\Models\AsetTanahModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options as DompdfOptions;

class AsetTanah extends BaseController
{
    protected AsetTanahModel $model;

    public function __construct()
    {
        $this->model = new AsetTanahModel();
    }

    /**
     * Tampilkan form upload excel.
     */

    //  $namaWilayah = $this->session->get('wilayah_nama');

    public function index()
    {

        $desa = $this->getDesaContext();

        if (!$desa) {
            return redirect()->back()
                ->with('error', 'Sesi desa tidak ditemukan. Silakan login ulang.');
        }
    
        $rows = $this->model->getByDesa((int) $desa['id']);




        $data = [
            'title' => 'Import Data Aset Tanah',
            'role' =>  session()->get('role_id'),
            'namaWilayah' => session()->get('wilayah_nama'),
            'rows'        => $rows
        ];

        return view('desa/kiba_content', $data);
    }

    /**
     * Proses file excel yang diupload dan simpan ke tabel aset_tanah.
     */
    public function import()
    {
        // --- 1. Validasi file upload ---
        $validationRule = [
            'file_excel' => [
                'label' => 'File Excel',
                'rules' => 'uploaded[file_excel]'
                    . '|max_size[file_excel,5120]'
                    . '|ext_in[file_excel,xlsx,xls]',
                'errors' => [
                    'uploaded' => 'Silakan pilih file excel terlebih dahulu.',
                    'max_size' => 'Ukuran file maksimal 5 MB.',
                    'ext_in'   => 'File harus berformat .xlsx atau .xls.',
                ],
            ],
        ];

        if (!$this->validate($validationRule)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $file = $this->request->getFile('file_excel');

        if (!$file->isValid() || $file->hasMoved()) {
            return redirect()->back()->with('error', 'File tidak valid atau gagal diupload.');
        }

        // --- 2. Ambil desa_id milik user yang sedang login ---
        // Sesuaikan dengan mekanisme autentikasi Anda.
        // Contoh ini mengasumsikan session menyimpan 'role' dan 'role_id',
        // di mana untuk role 'desa', role_id == id pada tabel desa.
        $desaId = session()->get('role_id');

        if (empty($desaId) || session()->get('role') !== 'desa') {
            return redirect()->back()->with('error', 'Sesi desa tidak ditemukan. Silakan login ulang.');
        }

        // --- 3. Baca file excel dengan PhpSpreadsheet ---
        try {
            $spreadsheet = IOFactory::load($file->getTempName());
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Gagal membaca file excel: ' . $e->getMessage());
        }

        $sheet = $spreadsheet->getSheetByName('Data Aset Tanah') ?? $spreadsheet->getActiveSheet();
        $highestRow = $sheet->getHighestDataRow();

        // Kolom sesuai template: A=KODE_BARANG, B=NUP, C=JENIS_TANAH, D=LUAS_M2,
        // E=TAHUN_PEROLEHAN, F=ALAS_HAK, G=NILAI_PEROLEHAN, H=KETERANGAN,
        // I=TANGGAL_REKAP, J=LINK_FOTO
        $rows        = [];
        $rowErrors   = [];
        $startRow    = 2; // baris 1 = header

        for ($r = $startRow; $r <= $highestRow; $r++) {
            $kodeBarang = trim((string) $sheet->getCell("A{$r}")->getValue());
            $nup        = trim((string) $sheet->getCell("B{$r}")->getValue());
            $namaBarang = trim((string) $sheet->getCell("C{$r}")->getValue());
            $luas       = $sheet->getCell("D{$r}")->getValue();
            $tahun      = $sheet->getCell("E{$r}")->getValue();
            $alasHak    = trim((string) $sheet->getCell("F{$r}")->getValue());
            $nilai      = $sheet->getCell("G{$r}")->getValue();
            $keterangan = trim((string) $sheet->getCell("H{$r}")->getValue());
            $tanggalCell = $sheet->getCell("I{$r}");
            $linkFoto   = trim((string) $sheet->getCell("J{$r}")->getValue());

            // lewati baris yang benar-benar kosong
            if ($namaBarang === '' && $kodeBarang === '' && $nup === '') {
                continue;
            }

            if ($namaBarang === '') {
                $rowErrors[] = "Baris {$r}: kolom JENIS_TANAH wajib diisi.";
                continue;
            }

            // TANGGAL_REKAP: Excel sering otomatis mengonversi input seperti "2024-02-03"
            // jadi tanggal asli (serial number) dan menampilkannya ulang sesuai format
            // regional user (mis. "2024/02/03"). Jangan bergantung pada deteksi format
            // cell (isDateTime()) karena tidak selalu konsisten antar versi Excel/LibreOffice
            // -- cukup cek apakah nilainya numerik (berarti serial date Excel).
            $tanggalRekap = null;
            $rawTanggal   = $tanggalCell->getValue();

            if (is_numeric($rawTanggal)) {
                try {
                    $tanggalRekap = ExcelDate::excelToDateTimeObject($rawTanggal)->format('Y-m-d');
                } catch (\Throwable $e) {
                    $tanggalRekap = null;
                }
            } elseif (is_string($rawTanggal) && trim($rawTanggal) !== '') {
                $val = trim($rawTanggal);
                // Coba beberapa pola umum dulu supaya hasilnya presisi (bukan tebakan strtotime)
                foreach (['Y-m-d', 'Y/m/d', 'd-m-Y', 'd/m/Y'] as $fmt) {
                    $dt = \DateTime::createFromFormat($fmt, $val);
                    if ($dt !== false && $dt->format($fmt) === $val) {
                        $tanggalRekap = $dt->format('Y-m-d');
                        break;
                    }
                }
                // Fallback terakhir: biarkan PHP menebak
                if ($tanggalRekap === null) {
                    $ts = strtotime($val);
                    $tanggalRekap = $ts !== false ? date('Y-m-d', $ts) : null;
                }
            }

            if (empty($tanggalRekap)) {
                $rowErrors[] = "Baris {$r}: kolom TANGGAL_REKAP wajib diisi dengan format tanggal yang valid (YYYY-MM-DD).";
                continue;
            }

            $rows[] = [
                '_excel_row'      => $r,
                'desa_id'         => (int) $desaId,
                'tanggal_rekap'   => $tanggalRekap,
                'kode_barang'     => $kodeBarang !== '' ? $kodeBarang : null,
                'nup'             => $nup !== '' ? $nup : null,
                'nama_barang'     => $namaBarang,
                'luas'            => $luas !== '' && $luas !== null ? (float) $luas : null,
                'tahun_perolehan' => $tahun !== '' && $tahun !== null ? (int) $tahun : null,
                'alas_hak'        => $alasHak !== '' ? $alasHak : null,
                'nilai_perolehan' => $nilai !== '' && $nilai !== null ? (float) $nilai : null,
                'keterangan'      => $keterangan !== '' ? $keterangan : null,
                'foto'            => $linkFoto !== '' ? $linkFoto : null,
            ];
        }

        if (!empty($rowErrors)) {
            return redirect()->back()->with('errors', $rowErrors);
        }

        if (empty($rows)) {
            return redirect()->back()->with('error', 'Tidak ada data yang bisa dibaca dari file excel. Pastikan Anda menggunakan template yang benar.');
        }

        // --- 4. Simpan ke database: hapus data lama desa ini, ganti dengan data baru ---
        $result = $this->model->replaceForDesa((int) $desaId, $rows);

        if (!$result['success']) {
            $messages = array_map(
                fn ($e) => "Baris {$e['baris']}: " . implode(', ', $e['pesan']),
                $result['errors']
            );
            return redirect()->back()->with('errors', $messages);
        }

        // return $this->response->setJSON($result);


                session()->set('berhasil', $result['inserted']);
                return redirect()->to("desa/dashboard")->with('success', "Berhasil mengganti data aset tanah dengan {$result['inserted']} baris baru dari file yang diupload.");


    }

    /**
     * Ambil desa_id dari sesi user yang login + info desa (nama, kepala desa)
     * dipakai bersama oleh exportExcel() dan exportPdf().
     */
    private function getDesaContext(): ?array
    {
        $desaId = session()->get('role_id');
        if (empty($desaId) || session()->get('role') !== 'desa') {
            return null;
        }

        $desa = \Config\Database::connect()
            ->table('desa')
            ->select('id, nama, kepala_desa')
            ->where('id', $desaId)
            ->get()
            ->getRowArray();

        if (!$desa) {
            return null;
        }

        return $desa;
    }

    /**
     * Download data aset tanah TERAKHIR (kondisi saat ini di database) sebagai file excel,
     * memakai format kolom yang sama persis dengan template import.
     */
    public function exportExcel()
    {
        $desa = $this->getDesaContext();
        if (!$desa) {
            return redirect()->back()->with('error', 'Sesi desa tidak ditemukan. Silakan login ulang.');
        }

        $rows = $this->model->getByDesa((int) $desa['id']);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Aset Tanah');

        $headers = [
            'KODE_BARANG', 'NUP', 'JENIS_TANAH', 'LUAS_M2', 'TAHUN_PEROLEHAN',
            'ALAS_HAK', 'NILAI_PEROLEHAN', 'KETERANGAN', 'TANGGAL_REKAP', 'LINK_FOTO',
        ];
        $sheet->fromArray($headers, null, 'A1');
        $sheet->getStyle('A1:J1')->getFont()->setBold(true);

        // Paksa kolom TANGGAL_REKAP (I) sebagai text untuk banyak baris ke depan,
        // konsisten dengan template import, supaya baris baru yang ditambahkan user
        // tidak ikut di-auto-convert Excel jadi date-serial dengan format regional beda-beda.
        $sheet->getStyle('I2:I500')->getNumberFormat()->setFormatCode('@');

        $r = 2;
        foreach ($rows as $row) {
            $tanggalRekapFormatted = $row['tanggal_rekap']
            ? date('d-m-Y', strtotime($row['tanggal_rekap']))
            : null;
            $sheet->fromArray([
                $row['kode_barang'],
                $row['nup'],
                $row['nama_barang'],
                $row['luas'],
                $row['tahun_perolehan'],
                $row['alas_hak'],
                $row['nilai_perolehan'],
                $row['keterangan'],
                $tanggalRekapFormatted,
                $row['foto'],
            ], null, "A{$r}");
            $r++;
        }

        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'aset_tanah_' . preg_replace('/\s+/', '_', strtolower($desa['nama'])) . '_' . date('Ymd_His') . '.xlsx';

        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    /**
     * Download data aset tanah TERAKHIR sebagai PDF (untuk dilihat/dicetak),
     * lengkap dengan kolom tanda tangan Kepala Desa di bagian bawah.
     */
    public function exportPdf()
    {
        $desa = $this->getDesaContext();
        if (!$desa) {
            return redirect()->back()->with('error', 'Sesi desa tidak ditemukan. Silakan login ulang.');
        }

        $rows = $this->model->getByDesa((int) $desa['id']);

        $totalLuas  = 0;
        $totalNilai = 0;
        foreach ($rows as $row) {
            $totalLuas  += (float) ($row['luas'] ?? 0);
            $totalNilai += (float) ($row['nilai_perolehan'] ?? 0);
        }

        $html = view('desa/pdf', [
            'desa'   => $desa,
            'rows'   => $rows,
            'tanggal_cetak' => date('d-m-Y'),
            'total_luas'    => $totalLuas,
            'total_nilai'   => $totalNilai,
        ]);

        $options = new DompdfOptions();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('F4', 'landscape'); // F4/legal, landscape karena kolomnya banyak
        $dompdf->render();

        $filename = 'aset_tanah_' . preg_replace('/\s+/', '_', strtolower($desa['nama'])) . '_' . date('Ymd_His') . '.pdf';

        $dompdf->stream($filename, ['Attachment' => false]); // false = tampil di tab browser dulu, bukan langsung download
        exit;
    }


    private function getKecamatanContext(): ?array
    {
        $kecamatanId = session()->get('role_id');
        if (empty($kecamatanId) || session()->get('role') !== 'kecamatan') {
            return null;
        }
 
        $kecamatan = \Config\Database::connect()
            ->table('kecamatan')
            ->select('id, nama')
            ->where('id', $kecamatanId)
            ->get()
            ->getRowArray();
 
        return $kecamatan ?: null;
    }


    public function kecamatanIndex()
    {
        $kecamatan = $this->getKecamatanContext();
        if (!$kecamatan) {
            return redirect()->back()->with('error', 'Sesi kecamatan tidak ditemukan. Silakan login ulang.');
        }
 
        $desaIdRaw = $this->request->getGet('desa_id');
        $desaId    = ($desaIdRaw !== null && $desaIdRaw !== '') ? (int) $desaIdRaw : null;
 
        $rows = $this->model->getByKecamatan((int) $kecamatan['id'], $desaId);
 
        $desaList = \Config\Database::connect()
            ->table('desa')
            ->select('id, nama')
            ->where('kecamatan_id', $kecamatan['id'])
            ->orderBy('nama', 'ASC')
            ->get()
            ->getResultArray();
 
        return view('kecamatan/kiba_content', [
            'rows'           => $rows,
            'desaList'       => $desaList,
            'selectedDesaId' => $desaId,
            'role'           => 'kecamatan',
            'namaWilayah'    => $kecamatan['nama'],
        ]);
    }



    public function kecamatanExportPdf()
    {
        $kecamatan = $this->getKecamatanContext();
        if (!$kecamatan) {
            return redirect()->back()->with('error', 'Sesi kecamatan tidak ditemukan. Silakan login ulang.');
        }
 
        $desaIdRaw = $this->request->getGet('desa_id');
        $desaId    = ($desaIdRaw !== null && $desaIdRaw !== '') ? (int) $desaIdRaw : null;
 
        $rows = $this->model->getByKecamatan((int) $kecamatan['id'], $desaId);
 
        $totalLuas  = 0;
        $totalNilai = 0;
        foreach ($rows as $row) {
            $totalLuas  += (float) ($row['luas'] ?? 0);
            $totalNilai += (float) ($row['nilai_perolehan'] ?? 0);
        }
 
        // Kalau filter desa aktif, judul laporan pakai nama desa itu;
        // kalau tidak (tampilkan semua desa), pakai nama kecamatan.
        $namaFilterDesa = null;
        if ($desaId && !empty($rows)) {
            $namaFilterDesa = $rows[0]['nama_desa'] ?? null;
        }
 
        $html = view('kecamatan/pdf_kecamatan', [
            'kecamatan'      => $kecamatan,
            'namaFilterDesa' => $namaFilterDesa,
            'rows'           => $rows,
            'tanggal_cetak'  => date('d-m-Y'),
            'total_luas'     => $totalLuas,
            'total_nilai'    => $totalNilai,
        ]);
 
        $options = new DompdfOptions();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Helvetica');
 
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('F4', 'landscape');
        $dompdf->render();
 
        $filenamePart = $namaFilterDesa ?: $kecamatan['nama'];
        $filename = 'aset_tanah_' . preg_replace('/\s+/', '_', strtolower($filenamePart)) . '_' . date('Ymd_His') . '.pdf';
 
        $dompdf->stream($filename, ['Attachment' => false]);
        exit;
    }

    private function getKabupatenContext(): ?array
    {
        $kabupatenId = session()->get('role_id');
        if (empty($kabupatenId) || session()->get('role') !== 'kabupaten') {
            return null;
        }

        $kabupaten = \Config\Database::connect()
            ->table('kabupaten')
            ->select('id, nama')
            ->where('id', $kabupatenId)
            ->get()
            ->getRowArray();

        return $kabupaten ?: null;
    }

    /**
     * Download PDF data aset tanah untuk KABUPATEN, mengikuti filter
     * kecamatan_id dan/atau desa_id yang sama seperti tabel di halaman
     * kabupaten (dirender lewat ContentController::loadContent).
     */
    public function kabupatenExportPdf()
    {
        $kabupaten = $this->getKabupatenContext();
        if (!$kabupaten) {
            return redirect()->back()->with('error', 'Sesi kabupaten tidak ditemukan. Silakan login ulang.');
        }

        $kecIdRaw  = $this->request->getGet('kecamatan_id');
        $desaIdRaw = $this->request->getGet('desa_id');
        $kecamatanId = ($kecIdRaw !== null && $kecIdRaw !== '') ? (int) $kecIdRaw : null;
        $desaId      = ($desaIdRaw !== null && $desaIdRaw !== '') ? (int) $desaIdRaw : null;

        $rows = $this->model->getByKabupaten((int) $kabupaten['id'], $kecamatanId, $desaId);

        $totalLuas  = 0;
        $totalNilai = 0;
        foreach ($rows as $row) {
            $totalLuas  += (float) ($row['luas'] ?? 0);
            $totalNilai += (float) ($row['nilai_perolehan'] ?? 0);
        }

        $namaFilterKecamatan = null;
        $namaFilterDesa      = null;
        if (!empty($rows)) {
            if ($kecamatanId) {
                $namaFilterKecamatan = $rows[0]['nama_kecamatan'] ?? null;
            }
            if ($desaId) {
                $namaFilterDesa = $rows[0]['nama_desa'] ?? null;
            }
        }

        $html = view('kabupaten/pdf_kabupaten', [
            'kabupaten'            => $kabupaten,
            'namaFilterKecamatan'  => $namaFilterKecamatan,
            'namaFilterDesa'       => $namaFilterDesa,
            'rows'                 => $rows,
            'tanggal_cetak'        => date('d-m-Y'),
            'total_luas'           => $totalLuas,
            'total_nilai'          => $totalNilai,
        ]);

        $options = new DompdfOptions();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('F4', 'landscape');
        $dompdf->render();

        $filenamePart = $namaFilterDesa ?: ($namaFilterKecamatan ?: $kabupaten['nama']);
        $filename = 'aset_tanah_' . preg_replace('/\s+/', '_', strtolower($filenamePart)) . '_' . date('Ymd_His') . '.pdf';

        $dompdf->stream($filename, ['Attachment' => false]);
        exit;
    }

}