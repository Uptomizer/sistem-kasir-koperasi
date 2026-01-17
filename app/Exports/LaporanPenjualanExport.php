<?php

namespace App\Exports;

use App\Models\Penjualan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LaporanPenjualanExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithColumnFormatting
{
    protected $tanggal;
    protected $rowCount = 0;

    public function __construct($tanggal = null)
    {
        $this->tanggal = $tanggal;
    }

    public function collection(): Collection
    {
        $query = Penjualan::with(['detail.barang'])
            ->orderBy('tanggal');

        if ($this->tanggal) {
            $query->whereDate('tanggal', $this->tanggal);
        }

        $rows = [];
        $totalKeuntungan = 0;

        foreach ($query->get() as $trx) {
            foreach ($trx->detail as $item) {
                // Handle deletion case safely
                $namaBarang = $item->barang ? $item->barang->nama_barang : 'Barang Terhapus';
                $hargaBeli = $item->barang ? $item->barang->harga_beli : 0;

                $keuntunganItem = ($item->harga - $hargaBeli) * $item->jumlah;

                $totalKeuntungan += $keuntunganItem;

                $rows[] = [
                    'tanggal'    => \Carbon\Carbon::parse($trx->tanggal)->format('d-m-Y H:i'),
                    'barang'     => $namaBarang,
                    'qty'        => $item->jumlah,
                    'harga'      => $item->harga,
                    'subtotal'   => $item->subtotal,
                    'total_trx'  => $trx->total,
                    'keuntungan' => $keuntunganItem,
                ];
            }
        }

        // Baris total keuntungan (footer)
        $rows[] = [
            'tanggal'    => '',
            'barang'     => 'TOTAL KEUNTUNGAN',
            'qty'        => '',
            'harga'      => '',
            'subtotal'   => '',
            'total_trx'  => '',
            'keuntungan' => $totalKeuntungan,
        ];
        
        $this->rowCount = count($rows);

        return collect($rows);
    }


    public function headings(): array
    {
        return [
            'Tanggal Transaksi',
            'Nama Barang',
            'Qty',
            'Harga Satuan',
            'Subtotal',
            'Total Transaksi',
            'Keuntungan',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'D' => '#,##0', // Harga Satuan
            'E' => '#,##0', // Subtotal
            'F' => '#,##0', // Total Transaksi
            'G' => '#,##0', // Keuntungan
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style Header
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => '4F46E5'], // Indigo-600
            ],
            'alignment' => ['horizontal' => 'center'],
        ]);

        // Style Data Rows Borders
        $sheet->getStyle('A1:G' . ($this->rowCount + 1))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'CBD5E1'], // Slate-300
                ],
            ],
        ]);
        
        // Style Footer Row (Total)
        $lastRow = $this->rowCount + 1; // +1 because row 1 is header
        
        $sheet->getStyle("A{$lastRow}:G{$lastRow}")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => '1E293B']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'F1F5F9'], // Slate-100
            ],
        ]);

        $sheet->mergeCells("B{$lastRow}:D{$lastRow}");
        $sheet->getStyle("B{$lastRow}")->getAlignment()->setHorizontal('right');
    }
}
