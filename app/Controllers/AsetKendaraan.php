<?php

namespace App\Controllers;

use App\Models\AsetKendaraanModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options as DompdfOptions;

class AsetKendaraan extends BaseController
{
    protected AsetKendaraanModel $model;

    public function __construct()
    {
        $this->model = new AsetKendaraanModel();
    }

    public function index()
    {
        $desa = $this->getDesaContext();
        $rows = $desa ? $this->model->getByDesa((int) $desa['id']) : [];

        return view('aset_kendaraan/upload', [
            'title' => 'Import Data Aset Kendaraan',
            'rows'  => $rows,
        ]);
    }

    public function import()
    {
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

        $desaId = session()->get('role_id');
        if (empty($desaId) || session()->get('role') !== 'desa') {
            return redirect()->back()->with('error', 'Sesi desa tidak ditemukan. Silakan login ulang.');
        }

        try {
            $spreadsheet = IOFactory::load($file->getTempName());
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Gagal membaca file excel: ' . $e->getMessage());
        }

        $sheet = $spreadsheet->getSheetByName('Data Aset Kendaraan') ?? $spreadsheet->getActiveSheet();
        $highestRow = $sheet->getHighestDataRow();

        // Kolom sesuai template: A=KODE_BARANG, B=NUP, C=NAMA_BARANG, D=MERK_TIPE,
        // E=TAHUN_PEROLEHAN, F=NOMOR_IDENTITAS, G=NILAI_PEROLEHAN, H=KONDISI,
        // I=KETERANGAN, J=TANGGAL_REKAP, K=LINK_FOTO
        $rows      = [];
        $rowErrors = [];
        $startRow  = 2;

        for ($r = $startRow; $r <= $highestRow; $r++) {
            $kodeBarang    = trim((string) $sheet->getCell("A{$r}")->getValue());
            $nup           = trim((string) $sheet->getCell("B{$r}")->getValue());
            $namaBarang    = trim((string) $sheet->getCell("C{$r}")->getValue());
            $merkTipe      = trim((string) $sheet->getCell("D{$r}")->getValue());
            $tahun         = $sheet->getCell("E{$r}")->getValue();
            $nomorIdentitas = trim((string) $sheet->getCell("F{$r}")->getValue());
            $nilai         = $sheet->getCell("G{$r}")->getValue();
            $kondisi       = strtoupper(trim((string) $sheet->getCell("H{$r}")->getValue()));
            $keterangan    = trim((string) $sheet->getCell("I{$r}")->getValue());
            $tanggalCell   = $sheet->getCell("J{$r}");
            $linkFoto      = trim((string) $sheet->getCell("K{$r}")->getValue());

            if ($namaBarang === '' && $kodeBarang === '' && $nup === '') {
                continue;
            }

            if ($namaBarang === '') {
                $rowErrors[] = "Baris {$r}: kolom NAMA_BARANG (jenis kendaraan) wajib diisi.";
                continue;
            }

            if ($kondisi !== '' && !in_array($kondisi, ['B', 'RR', 'RB'], true)) {
                $rowErrors[] = "Baris {$r}: kolom KONDISI harus salah satu dari B, RR, atau RB (ditemukan: '{$kondisi}').";
                continue;
            }

            // TANGGAL_REKAP: sama seperti aset tanah, cek numerik dulu (excel date
            // serial), baru fallback ke beberapa pola teks umum.
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
                foreach (['Y-m-d', 'Y/m/d', 'd-m-Y', 'd/m/Y'] as $fmt) {
                    $dt = \DateTime::createFromFormat($fmt, $val);
                    if ($dt !== false && $dt->format($fmt) === $val) {
                        $tanggalRekap = $dt->format('Y-m-d');
                        break;
                    }
                }
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
                'merk_tipe'       => $merkTipe !== '' ? $merkTipe : null,
                'tahun_perolehan' => $tahun !== '' && $tahun !== null ? (int) $tahun : null,
                'nomor_identitas' => $nomorIdentitas !== '' ? $nomorIdentitas : null,
                'nilai_perolehan' => $nilai !== '' && $nilai !== null ? (float) $nilai : null,
                'kondisi'         => $kondisi !== '' ? $kondisi : null,
                'keterangan'      => $keterangan !== '' ? $keterangan : null,
                'foto'            => $linkFoto !== '' ? $linkFoto : null,
            ];
        }

        if (!empty($rowErrors)) {
            session()->setFlashdata('error', json_encode($rowErrors));
            return redirect()->to('desa/dashboard');    
        }

        if (empty($rows)) {
            session()->setFlashdata('error', 'Tidak ada data yang bisa dibaca dari file excel. Pastikan Anda menggunakan template yang benar.');
            return redirect()->to('desa/dashboard');  
        }

        $result = $this->model->replaceForDesa((int) $desaId, $rows);

        if (!$result['success']) {
            $messages = array_map(
                fn ($e) => "Baris {$e['baris']}: " . implode(', ', $e['pesan']),
                $result['errors']
            );

            session()->setFlashdata('error', json_encode($messages));
            return redirect()->to('desa/dashboard');    
        }

        
        session()->setFlashdata('success', "Berhasil mengganti data aset kendaraan dengan {$result['inserted']} baris baru dari file yang diupload.");
        return redirect()->to('desa/dashboard'); 


    }

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

        return $desa ?: null;
    }

    public function exportExcel()
    {
        $desa = $this->getDesaContext();
        if (!$desa) {
            return redirect()->back()->with('error', 'Sesi desa tidak ditemukan. Silakan login ulang.');
        }

        $rows = $this->model->getByDesa((int) $desa['id']);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Aset Kendaraan');

        $headers = [
            'KODE_BARANG', 'NUP', 'NAMA_BARANG', 'MERK_TIPE', 'TAHUN_PEROLEHAN',
            'NOMOR_IDENTITAS', 'NILAI_PEROLEHAN', 'KONDISI', 'KETERANGAN', 'TANGGAL_REKAP', 'LINK_FOTO',
        ];
        $sheet->fromArray($headers, null, 'A1');
        $sheet->getStyle('A1:K1')->getFont()->setBold(true);
        $sheet->getStyle('J2:J500')->getNumberFormat()->setFormatCode('@');

        $r = 2;
        foreach ($rows as $row) {
            $tanggalRekapFormatted = $row['tanggal_rekap']
                ? date('d-m-Y', strtotime($row['tanggal_rekap']))
                : null;

            $sheet->fromArray([
                $row['kode_barang'],
                $row['nup'],
                $row['nama_barang'],
                $row['merk_tipe'],
                $row['tahun_perolehan'],
                $row['nomor_identitas'],
                $row['nilai_perolehan'],
                $row['kondisi'],
                $row['keterangan'],
                $tanggalRekapFormatted,
                $row['foto'],
            ], null, "A{$r}");
            $r++;
        }

        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'aset_kendaraan_' . preg_replace('/\s+/', '_', strtolower($desa['nama'])) . '_' . date('Ymd_His') . '.xlsx';

        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function exportPdf()
    {
        $desa = $this->getDesaContext();
        if (!$desa) {
            return redirect()->back()->with('error', 'Sesi desa tidak ditemukan. Silakan login ulang.');
        }

        $rows = $this->model->getByDesa((int) $desa['id']);

        $totalNilai = 0;
        foreach ($rows as $row) {
            $totalNilai += (float) ($row['nilai_perolehan'] ?? 0);
        }

        $html = view('desa/kendaraan_pdf', [
            'desa'          => $desa,
            'rows'          => $rows,
            'tanggal_cetak' => date('d-m-Y'),
            'total_nilai'   => $totalNilai,
        ]);

        $options = new DompdfOptions();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('F4', 'landscape');
        $dompdf->render();

        $filename = 'aset_kendaraan_' . preg_replace('/\s+/', '_', strtolower($desa['nama'])) . '_' . date('Ymd_His') . '.pdf';

        $dompdf->stream($filename, ['Attachment' => false]);
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

    public function kecamatanExportPdf()
    {
        $kecamatan = $this->getKecamatanContext();
        if (!$kecamatan) {
            return redirect()->back()->with('error', 'Sesi kecamatan tidak ditemukan. Silakan login ulang.');
        }

        $desaIdRaw = $this->request->getGet('desa_id');
        $desaId    = ($desaIdRaw !== null && $desaIdRaw !== '') ? (int) $desaIdRaw : null;

        $rows = $this->model->getByKecamatan((int) $kecamatan['id'], $desaId);

        $totalNilai = 0;
        foreach ($rows as $row) {
            $totalNilai += (float) ($row['nilai_perolehan'] ?? 0);
        }

        $namaFilterDesa = null;
        if ($desaId && !empty($rows)) {
            $namaFilterDesa = $rows[0]['nama_desa'] ?? null;
        }

        $html = view('kecamatan/kendaraan_pdf_kecamatan', [
            'kecamatan'      => $kecamatan,
            'namaFilterDesa' => $namaFilterDesa,
            'rows'           => $rows,
            'tanggal_cetak'  => date('d-m-Y'),
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
        $filename = 'aset_kendaraan_' . preg_replace('/\s+/', '_', strtolower($filenamePart)) . '_' . date('Ymd_His') . '.pdf';

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

        $totalNilai = 0;
        foreach ($rows as $row) {
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

        $html = view('kabupaten/kendaraan_pdf_kabupaten', [
            'kabupaten'           => $kabupaten,
            'namaFilterKecamatan' => $namaFilterKecamatan,
            'namaFilterDesa'      => $namaFilterDesa,
            'rows'                => $rows,
            'tanggal_cetak'       => date('d-m-Y'),
            'total_nilai'         => $totalNilai,
        ]);

        $options = new DompdfOptions();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('F4', 'landscape');
        $dompdf->render();

        $filenamePart = $namaFilterDesa ?: ($namaFilterKecamatan ?: $kabupaten['nama']);
        $filename = 'aset_kendaraan_' . preg_replace('/\s+/', '_', strtolower($filenamePart)) . '_' . date('Ymd_His') . '.pdf';

        $dompdf->stream($filename, ['Attachment' => false]);
        exit;
    }
}