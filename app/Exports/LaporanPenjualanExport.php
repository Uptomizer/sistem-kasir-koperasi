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
        $query = Penjualan::with(['detail.barang', 'user'])
            ->orderBy('tanggal');

        if ($this->tanggal) {
            $query->whereDate('tanggal', $this->tanggal);
        }

        $rows = [];
        $totalKeuntungan = 0;
        $grandTotalKeuntungan = 0;

        foreach ($query->get() as $trx) {
            foreach ($trx->detail as $index => $item) {
                // Handle deletion case safely
                $namaBarang = $item->barang ? $item->barang->nama_barang : 'Barang Terhapus';
                $hargaBeli = $item->barang ? $item->barang->harga_beli : 0;
                $kasir = $trx->user->nama_user ?? 'Umum';

                $keuntunganItem = ($item->harga - $hargaBeli) * $item->jumlah;
                $grandTotalKeuntungan += $keuntunganItem;

                $diskonDisplay = ($index === 0 && $trx->diskon > 0) ? $trx->diskon : '';

                $rows[] = [
                    'tanggal'    => \Carbon\Carbon::parse($trx->tanggal)->format('d/m/Y H:i'),
                    'kasir'      => $kasir,
                    'barang'     => $namaBarang,
                    'qty'        => $item->jumlah,
                    'harga_beli' => $hargaBeli,
                    'harga_jual' => $item->harga,
                    'diskon'     => $diskonDisplay,
                    'keuntungan' => $keuntunganItem,
                ];
            }
        }

        // Baris total keuntungan (footer)
        // Adjust footer structure to match columns
        $rows[] = [
            'tanggal'    => '',
            'kasir'      => '',
            'barang'     => '',
            'qty'        => '',
            'harga_beli' => '',
            'harga_jual' => 'TOTAL KEUNTUNGAN',
            'diskon'     => '',
            'keuntungan' => $grandTotalKeuntungan,
        ];
        
        $this->rowCount = count($rows);

        return collect($rows);
    }


    public function headings(): array
    {
        return [
            'Tanggal',
            'Kasir',
            'Nama Barang',
            'Qty',
            'Harga Beli',
            'Harga Jual',
            'Diskon',
            'Keuntungan',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => '#,##0', // Harga Beli
            'F' => '#,##0', // Harga Jual
            'G' => '#,##0', // Diskon
            'H' => '#,##0', // Keuntungan
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style Header
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => '4F46E5'], // Indigo-600
            ],
            'alignment' => ['horizontal' => 'center'],
        ]);

        // Style Data Rows Borders
        $sheet->getStyle('A1:H' . ($this->rowCount + 1))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'CBD5E1'], // Slate-300
                ],
            ],
        ]);
        
        // Style Footer Row (Total)
        $lastRow = $this->rowCount + 1; // +1 because row 1 is header (already included in count if header is not part of collection, wait - headings are separate. rowCount includes data + footer row. So +1 for header row)
        // Wait, Maatwebsite adds header automatically at row 1.
        // My $rows contains data + footer.
        // So Header is row 1.
        // Data starts row 2.
        // Footer is row $rowCount + 1.
        
        // Actually $lastRow is correct based on rowCount of data.
        
        $sheet->getStyle("A{$lastRow}:H{$lastRow}")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => '1E293B']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'F1F5F9'], // Slate-100
            ],
        ]);

        $sheet->mergeCells("F{$lastRow}:G{$lastRow}");
        $sheet->getStyle("F{$lastRow}")->getAlignment()->setHorizontal('right');
    }
}
