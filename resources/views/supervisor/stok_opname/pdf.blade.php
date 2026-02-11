<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Stok Opname - {{ $opname->kode_opname }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 20px; color: #333; }
        .header p { margin: 5px 0; font-size: 14px; color: #666; }
        
        .meta-table { width: 100%; margin-bottom: 20px; font-size: 14px; }
        .meta-table td { padding: 5px; }
        .meta-label { font-weight: bold; width: 120px; color: #555; }
        
        .items-table { width: 100%; border-collapse: collapse; font-size: 12px; }
        .items-table th { background-color: #f8f9fa; border: 1px solid #ddd; padding: 10px; text-align: left; }
        .items-table td { border: 1px solid #ddd; padding: 8px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .status-badge { 
            padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 10px; text-transform: uppercase;
            display: inline-block;
        }
        .status-selesai { background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .status-pending { background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .status-batal { background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

        .notes-section { margin-top: 30px; border: 1px solid #eee; padding: 15px; border-radius: 5px; background-color: #fcfcfc; }
        .notes-title { font-weight: bold; font-size: 12px; color: #555; margin-bottom: 5px; text-transform: uppercase; }
        .notes-content { font-size: 13px; font-style: italic; color: #444; }

        .footer { margin-top: 50px; text-align: right; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN HASIL STOK OPNAME</h1>
        <p>Koperasi SMK</p>
    </div>

    <table class="meta-table">
        <tr>
            <td class="meta-label">Kode Opname</td>
            <td>: {{ $opname->kode_opname }}</td>
            <td class="meta-label">Status</td>
            <td>
                : <span class="status-badge status-{{ $opname->status }}">{{ $opname->status }}</span>
            </td>
        </tr>
        <tr>
            <td class="meta-label">Tanggal Audit</td>
            <td>: {{ date('d F Y', strtotime($opname->tanggal)) }}</td>
            <td class="meta-label">Petugas</td>
            <td>: {{ $opname->user->nama_user ?? '-' }}</td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th>Barang</th>
                <th style="width: 15%">Kode</th>
                <th class="text-right" style="width: 15%">Stok Sistem</th>
                <th class="text-right" style="width: 15%">Stok Fisik</th>
                <th class="text-right" style="width: 15%">Selisih</th>
            </tr>
        </thead>
        <tbody>
            @foreach($opname->detail as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->barang->nama_barang ?? 'Deleted Item' }}</td>
                <td>{{ $item->barang->kode_barang ?? '-' }}</td>
                <td class="text-right">{{ $item->stok_sistem }}</td>
                <td class="text-right" style="background-color: #fafafa; font-weight: bold;">{{ $item->stok_fisik }}</td>
                <td class="text-right">
                    @if($item->selisih > 0)
                        <span style="color: green;">+{{ $item->selisih }}</span>
                    @elseif($item->selisih < 0)
                        <span style="color: red;">{{ $item->selisih }}</span>
                    @else
                        <span style="color: #999;">0</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($opname->catatan)
    @php
        $fullCatatan = $opname->catatan ?? '';
        $supervisorNotePrefix = '[Supervisor]:';
        
        $userNote = $fullCatatan;
        $supervisorNote = null;

        if (str_contains($fullCatatan, $supervisorNotePrefix)) {
            $parts = explode($supervisorNotePrefix, $fullCatatan);
            $userNote = trim($parts[0]);
            $supervisorNote = trim($parts[1] ?? '');
        }
    @endphp

    <div class="notes-section">
        <div class="notes-title">Catatan Petugas</div>
        <div class="notes-content">{{ $userNote ?: '-' }}</div>
        
        @if($supervisorNote)
        <div class="notes-title" style="margin-top: 15px; color: #0056b3;">Catatan Verifikasi Supervisor</div>
        <div class="notes-content">{{ $supervisorNote }}</div>
        @endif
    </div>
    @endif

    <div class="footer">
        <p>Dicetak pada: {{ date('d F Y H:i') }}</p>
        <p>Oleh: {{ Auth::user()->nama_user }} (Supervisor)</p>
    </div>
</body>
</html>
