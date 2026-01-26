<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Barcode - {{ $barang->nama_barang }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <style>
        @media print {
            @page {
                size: auto;
                margin: 5mm;
            }
            body {
                background: white;
            }
            .no-print {
                display: none !important;
            }
            .page-break {
                page-break-after: always;
            }
        }
        
        .label-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* 3 Kolom standar kertas label */
            gap: 1rem;
            padding: 1rem;
        }

        .label-item {
            border: 1px dashed #ccc;
            padding: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            min-height: 140px; /* Lebarkan area */
            background: white;
            border-radius: 8px;
        }

        /* Hilangkan border saat print agar bersih jika pakai kertas label potongan */
        @media print {
            .label-item {
                border: 1px solid #eee; /* Tipis saja atau none */
                box-shadow: none;
                break-inside: avoid; /* Jangan terpotong halaman */
            }
            .label-container {
                gap: 0.5cm;
                display: grid;
            }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Control Panel (No Print) -->
    <div class="no-print fixed top-0 left-0 right-0 bg-white border-b shadow-md p-4 flex justify-between items-center z-50">
        <div class="flex items-center gap-4">
            <button onclick="window.close()" class="text-gray-500 hover:text-red-600 flex items-center gap-1 transition-colors group">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Tutup
            </button>
            <div>
                <h1 class="font-bold text-lg text-gray-800">{{ $barang->nama_barang }}</h1>
                <p class="text-sm text-gray-500">{{ $barang->kode_barang ?? 'Tanpa Kode (Akan digenerate)' }}</p>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2">
                <label class="text-sm font-semibold text-gray-700">Jumlah:</label>
                <input type="number" id="qty" value="12" min="1" max="100" 
                       class="border border-gray-300 rounded px-2 py-1 w-20 text-center"
                       onchange="renderLabels()">
            </div>
            
            <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-bold shadow hover:bg-blue-700 transition flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak
            </button>
        </div>
    </div>

    <!-- Padding for fixed header -->
    <div class="h-24 no-print"></div>

    <!-- Label Area -->
    <div id="printArea" class="max-w-4xl mx-auto bg-white shadow-lg min-h-screen p-8 print:shadow-none print:w-full print:p-0">
        <div id="labelsGrid" class="label-container">
            <!-- Labels wil be injected here -->
        </div>
    </div>

    <script>
        const namaBarang = @json(Str::limit($barang->nama_barang, 25));
        
        // Fallback Logic:
        // Jika kode_barang kosong, gunakan ID yang dipadding agar tidak terlalu pendek
        // Contoh: ID 1 menjadi '0000001' (CODE128 lebih suka string agak panjang daripada cuma 1 char)
        let rawKode = @json($barang->kode_barang);
        let idBarang = @json($barang->id_barang);
        
        if (!rawKode) {
            // Padding ID to 8 digits
            rawKode = idBarang.toString().padStart(8, '0');
        }

        const kodeBarang = rawKode;
        const harga = @json($barang->harga_jual);
        
        // Settings
        
        function renderLabels() {
            const qty = document.getElementById('qty').value;
            const container = document.getElementById('labelsGrid');
            container.innerHTML = '';

            for (let i = 0; i < qty; i++) {
                const div = document.createElement('div');
                div.className = 'label-item';
                div.innerHTML = `
                    <div class="font-bold text-sm mb-1 uppercase tracking-wide truncate w-full px-2 leading-tight">${namaBarang}</div>
                    <svg class="barcode-svg w-full" 
                         jsbarcode-format="CODE128" 
                         jsbarcode-value="${kodeBarang}" 
                         jsbarcode-textmargin="0" 
                         jsbarcode-fontoptions="bold"
                         jsbarcode-height="80" 
                         jsbarcode-width="3"
                         jsbarcode-displayValue="true"
                         jsbarcode-fontSize="18"
                         jsbarcode-marginTop="2"
                         jsbarcode-marginBottom="2">
                    </svg>
                    <div class="font-bold text-lg mt-1">Rp ${new Intl.NumberFormat('id-ID').format(harga)}</div>
                `;
                container.appendChild(div);
            }

            // Init Barcodes
            try {
                JsBarcode(".barcode-svg").init();
            } catch (e) {
                console.error("JsBarcode failed:", e);
                container.innerHTML = '<div class="col-span-3 text-center text-red-500 p-4">Gagal memuat library barcode. Periksa koneksi internet.</div>';
            }
        }

        // Initial Render
        document.addEventListener('DOMContentLoaded', renderLabels);
    </script>
</body>
</html>
