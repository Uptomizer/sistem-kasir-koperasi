<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10pt;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            padding: 0;
            font-size: 16pt;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 10pt;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #333;
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        td.number {
            text-align: right;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
        .total-box {
            display: inline-block;
            border: 1px solid #333;
            padding: 10px;
            background-color: #f9f9f9;
        }
        .meta-info {
            text-align: right;
            font-size: 8pt;
            margin-bottom: 10px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Penjualan</h1>
        <p>{{ $subtitle }}</p>
    </div>

    <div class="meta-info">
        Dicetak pada: {{ now()->format('d/m/Y H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 12%">Tanggal</th>
                <th style="width: 12%">Kasir</th>
                <th style="width: 20%">Barang</th>
                <th style="width: 5%">Qty</th>
                <th style="width: 11%">Harga Beli</th>
                <th style="width: 11%">Harga Jual</th>
                <th style="width: 10%">Diskon</th>
                <th style="width: 14%">Keuntungan</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($penjualan as $trx)
                @foreach($trx->detail as $index => $item)
                    @if($item->barang)
                        @php
                             $keuntunganItem = ($item->harga - $item->barang->harga_beli) * $item->jumlah;
                             // Adjust profit if discount exists? 
                             // Usually profit = (Sell - Buy) - DiscountShare. 
                             // But for simplicity let's just show raw profit and raw discount for now.
                        @endphp
                        <tr>
                            <td style="text-align: center">{{ $no++ }}</td>
                            <td>{{ $trx->tanggal instanceof \Carbon\Carbon ? $trx->tanggal->format('d/m/Y H:i') : \Carbon\Carbon::parse($trx->tanggal)->format('d/m/Y H:i') }}</td>
                            <td>{{ $trx->user->nama_user ?? 'Umum' }}</td>
                            <td>{{ $item->barang->nama_barang }}</td>
                            <td class="number">{{ $item->jumlah }}</td>
                            <td class="number">Rp {{ number_format($item->barang->harga_beli, 0, ',', '.') }}</td>
                            <td class="number">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td class="number">
                                {{-- Show discount only on the first item to avoid confusion or repetition? 
                                     Or simply show it to indicate the transaction had a discount. 
                                     Shows total discount of the transaction. --}}
                                @if($index === 0 && $trx->diskon > 0)
                                    Rp {{ number_format($trx->diskon, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="number">Rp {{ number_format($keuntunganItem, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                @endforeach
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="total-box">
            <strong>Total Keuntungan: Rp {{ number_format($totalKeuntungan, 0, ',', '.') }}</strong>
        </div>
    </div>
</body>
</html>
