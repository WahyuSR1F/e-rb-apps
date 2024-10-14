<?php

namespace App\Exports;

use App\Models\Cluster;
use App\Models\ERBType;
use App\Models\Permasalahan;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class RBExport implements FromCollection, WithHeadings, WithEvents
{
    protected $idTema;
    protected $year;
    protected $namaTema;
    protected $namaCluster;
    public function __construct($idTema, $year)
    {
        $this->idTema = $idTema;
        $this->year =  $year;
        $tema =  $this->getNameTema($idTema);
        $this->namaTema =  $tema->nama;
        $cluster = $this->getNameCluster($tema->cluster_id);
        $this->namaCluster =  $cluster->cluster;

        
    }
    protected function getNameTema ($idTema){
        $tema =  ERBType::where('id', $idTema)->select('cluster_id','nama')->first();
        return $tema;
    }
    protected function getNameCluster ($idCluster){
        $tema =  Cluster::where('id', $idCluster)->select('id','cluster')->first();
        return $tema; 
    }
    public function collection()
    {
        $data = Permasalahan::with('allRelatedData')->where('erb_type_id', $this->idTema)->whereYear('created_at', $this->year)->get();
        // Siapkan array untuk data yang akan diekspor
        $exportData = [];

        $indexs = 1;
        // Loop melalui setiap permasalahan
        foreach ($data as $permasalahan) {
            // Loop melalui setiap rencana aksi yang terkait dengan permasalahan
            foreach ($permasalahan->allRelatedData as $rencanaAksi) {
                $exportData[] = [
                    'Permasalahan ID' => $indexs,
                    'Permasalahan' => $permasalahan->permasalahan,
                    'Sasaran' => $permasalahan->sasaran,
                    'Indikator' => $permasalahan->indikator,
                    'Target' => $permasalahan->target,
                    'Rencana Aksi' => $rencanaAksi->rencana_aksi,
                    'Indikator RencanaAksi' => $rencanaAksi->indikator,
                    'Satuan' => $rencanaAksi->satuan,

                    // Target Penyelesaian
                    'Target Penyelesaian TW I' => optional($rencanaAksi->targetPenyelesaian)->twI,
                    'Target Penyelesaian TW II' => optional($rencanaAksi->targetPenyelesaian)->twII,
                    'Target Penyelesaian TW III' => optional($rencanaAksi->targetPenyelesaian)->twIII,
                    'Target Penyelesaian TW IV' => optional($rencanaAksi->targetPenyelesaian)->twIV,
                    'Target Penyelesaian jumlah' => optional($rencanaAksi->targetPenyelesaian)->jumlah,

                    // Realisasi Penyelesaian
                    'Realisasi Penyelesaian TW I' => optional($rencanaAksi->realisasiPenyelesaian)->twI,
                    'Realisasi Penyelesaian TW II' => optional($rencanaAksi->realisasiPenyelesaian)->twII,
                    'Realisasi Penyelesaian TW III' => optional($rencanaAksi->realisasiPenyelesaian)->twIII,
                    'Realisasi Penyelesaian TW IV' => optional($rencanaAksi->realisasiPenyelesaian)->twIV,
                    'Total Realisasi Penyelesaian' => optional($rencanaAksi->realisasiPenyelesaian)->jumlah,
                    'Capaian Penyelesaian' => optional($rencanaAksi->realisasiPenyelesaian)->presentase,

                    'Subjek Penyelesaian' => optional($rencanaAksi->targetPenyelesaian)->subjek,

                    // Target Anggaran
                    'Target Anggaran TW I' => optional($rencanaAksi->targetAnggaran)->twI,
                    'Target Anggaran TW II' => optional($rencanaAksi->targetAnggaran)->twII,
                    'Target Anggaran TW III' => optional($rencanaAksi->targetAnggaran)->twIII,
                    'Target Anggaran TW IV' => optional($rencanaAksi->targetAnggaran)->twIV,
                    'Total Target Anggaran' => optional($rencanaAksi->targetAnggaran)->jumlah,

                    // Realisasi Anggaran
                    'Realisasi Anggaran TW I' => optional($rencanaAksi->realisasiAnggaran)->twI,
                    'Realisasi Anggaran TW II' => optional($rencanaAksi->realisasiAnggaran)->twII,
                    'Realisasi Anggaran TW III' => optional($rencanaAksi->realisasiAnggaran)->twIII,
                    'Realisasi Anggaran TW IV' => optional($rencanaAksi->realisasiAnggaran)->twIV,
                    'Total Realisasi Anggaran' => optional($rencanaAksi->realisasiAnggaran)->jumlah,
                    'Capaian Anggaran' => optional($rencanaAksi->realisasiAnggaran)->presentase,


                    //ending
                    'Koordinator' => $rencanaAksi->koordinator,
                    'Pelaksana' => $rencanaAksi->pelaksana,
                ];
                $indexs++;
            }
        }


        // Kembalikan data dalam bentuk koleksi untuk diekspor oleh Laravel Excel
        return collect($exportData);
    }

    public function headings(): array
    {
        return [
            // Header rows
            ['Realisasi Rencana Aksi Pelaksanaan Reformasi Birokrasi Pada Dinas Kesehatan Tahun '. $this->year],
            ['', 'SKDP', ': DINAS KESEHATAN'],
            ['', 'Reformasi Birokrasi', ': '.$this->namaCluster],
            ['', 'Indikator yang didukung', ': '. $this->namaTema],
            ['', 'Tribulan', ':TWI Tahun '. $this->year],
            [''],
            ['No', 'PERMASALAHAN', 'SASARAN', 'INDIKATOR', 'TARGET', 'Rencana Aksi', 'OUTPUT', '', 'PENYELESAIAN', '', '', '', '', '', '', '', '', '', 'CAPAIAN %', 'Jenis Kegiatan Aksi \n (Terkait atau tidak terkait langsung dengan Masyarakt Stekholder Utama)', 'ANGGARAN', '', '', '', '', '', '', '', '', '', 'CAPAIAN %', 'UNIT SATUAN PELAKSANAAN'],
            ['', '', '', '', '', '', 'INDIKATOR', 'SATUAN', 'TARGET', '', '', '', '', 'REALISASI', '', '', '', '', '', '', 'TARGET', '', '', '', '', 'REALISASI', '', '', '', '', '', 'KOORDINATOR', 'PELAKSANA'],
            ['', '', '', '', '', '', 'INDIKATOR', 'SATUAN', 'TWI', 'TWII', 'TWIII', 'TWIV', 'TOTAL', 'TWI', 'TWII', 'TWIII', 'TWIV', 'TOTAL', '', '', 'TWI', 'TWII', 'TWII', 'TWIV', 'TOTAL', 'TWI', 'TWII', 'TWII', 'TWIV', 'TOTAL'],
            ['']

        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Menggabungkan sel untuk judul utama di atas header
                $sheet->mergeCells('A1:V1'); // Menggabungkan semua kolom dari A hingga S di baris 1

                // Mengisi nilai untuk judul utama


                // Penggabungan sel untuk header tabel
                $sheet->mergeCells('A7:A9'); // No
                $sheet->mergeCells('G7:H7'); // Merge OUTPUT
                $sheet->mergeCells('G8:G9'); //Merge INDIKATOR
                $sheet->mergeCells('H8:H9'); // Merge TARGET
                $sheet->mergeCells('I7:R7'); // Penyelesaian
                $sheet->mergeCells('I8:M8'); // Merge TARGET DAN REALISASI
                $sheet->mergeCells('N8:R8');
                $sheet->mergeCells('N7:R7'); // Merge REALISASI
                $sheet->mergeCells('S7:S9'); // capaian Penyelesaian
                $sheet->mergeCells('AE7:AE9'); // Capaian
                $sheet->mergeCells('T7:T9'); // Jenis kegiatan
                $sheet->mergeCells('U7:AD7'); // ANGGARAN
                $sheet->mergeCells('U8:Y8'); //  TARGET Anggaran
                $sheet->mergeCells('Z8:AD8'); // Realisasi Anggaran
                $sheet->mergeCells('W8:Y8'); // Target Anggaran
                $sheet->mergeCells('AF7:AG7'); // Pelaksana
                $sheet->mergeCells('AF8:AF9'); // Koordinator
                $sheet->mergeCells('AG8:AG9'); // Pelaksana
                $sheet->mergeCells('B7:B9'); // Permasalahan
                $sheet->mergeCells('C7:C9'); // Sasaran
                $sheet->mergeCells('D7:D9'); // Indikator
                $sheet->mergeCells('E7:E9'); // Target
                $sheet->mergeCells('F7:F9'); // Rencana Aksi


                // Set lebar kolom sesuai kebutuhan
                $sheet->getColumnDimension('A')->setWidth(5);
                $sheet->getColumnDimension('B')->setWidth(50);
                $sheet->getColumnDimension('C')->setWidth(40);
                $sheet->getColumnDimension('D')->setWidth(40);
                $sheet->getColumnDimension('E')->setWidth(20);
                $sheet->getColumnDimension('F')->setWidth(60);
                $sheet->getColumnDimension('G')->setWidth(40);
                $sheet->getColumnDimension('H')->setWidth(15);
                $sheet->getColumnDimension('I')->setWidth(10);
                $sheet->getColumnDimension('J')->setWidth(10);
                $sheet->getColumnDimension('K')->setWidth(10);
                $sheet->getColumnDimension('L')->setWidth(10);
                $sheet->getColumnDimension('M')->setWidth(10);
                $sheet->getColumnDimension('N')->setWidth(10);
                $sheet->getColumnDimension('O')->setWidth(10);
                $sheet->getColumnDimension('P')->setWidth(10);
                $sheet->getColumnDimension('Q')->setWidth(10);
                $sheet->getColumnDimension('R')->setWidth(10);
                $sheet->getColumnDimension('S')->setWidth(15);
                $sheet->getColumnDimension('T')->setWidth(20);
                $sheet->getColumnDimension('U')->setWidth(10);
                $sheet->getColumnDimension('V')->setWidth(10);
                $sheet->getColumnDimension('X')->setWidth(10);
                $sheet->getColumnDimension('Y')->setWidth(10);
                $sheet->getColumnDimension('Z')->setWidth(10);
                $sheet->getColumnDimension('W')->setWidth(10);
                $sheet->getColumnDimension('AE')->setWidth(20);
                $sheet->getColumnDimension('AD')->setWidth(10);
                // Suggested code may be subject to a license. Learn more: ~LicenseLog:755005582.
                $sheet->getColumnDimension('AF')->setWidth(20);
                $sheet->getColumnDimension('AG')->setWidth(20);


                // Set styling untuk judul
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 25,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $sheet->getStyle('A:Z')->getAlignment()->setWrapText(true);
                // Set styling untuk header
                $sheet->getStyle('A7:AG9')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);

                // Set tinggi baris untuk judul dan header
                $sheet->getRowDimension(1)->setRowHeight(50);
                $sheet->getRowDimension(7)->setRowHeight(30);
                $sheet->getRowDimension(8)->setRowHeight(30);
                $sheet->getRowDimension(9)->setRowHeight(30);
            },
        ];
    }
}