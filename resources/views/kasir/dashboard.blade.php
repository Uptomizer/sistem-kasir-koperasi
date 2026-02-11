@extends('layouts.kasir')

@section('title', 'Kasir')
@section('page-title', 'Transaksi Kasir')

@section('content')
<div class="no-select">
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- DAFTAR BARANG --}}
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden flex flex-col h-[calc(100vh-8rem)]">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center gap-4">
            <div class="flex items-center gap-4">
                <h2 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                    <span class="text-xl">📦</span> Daftar Barang
                </h2>
                
                <div class="flex items-center gap-2">
                    <div class="relative">
                        <input type="text" name="search" id="searchInput" value="{{ request('search') }}" placeholder="Cari barang atau scan barcode..." 
                               class="bg-white border border-slate-300 text-slate-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-9 pr-4 py-1.5 shadow-sm outline-none">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <select name="kategori" id="categoryInput"
                            class="bg-white border border-slate-300 text-slate-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-3 pr-8 py-1.5 shadow-sm cursor-pointer outline-none max-w-[150px]">
                        <option value="">Semua Kategori</option>
                        @foreach($kategori as $k)
                            <option value="{{ $k->id_kategori }}" {{ request('kategori') == $k->id_kategori ? 'selected' : '' }}>
                                {{ $k->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>


            </div>

            <div class="text-sm text-slate-500 whitespace-nowrap">
                Total: <span class="font-semibold text-slate-700" id="filtered-item-count">{{ $barang->count() }}</span>
            </div>
        </div>

        <div class="overflow-y-auto flex-1 p-0">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-200 sticky top-0 z-10">
                    <tr>
                        <th class="px-6 py-3">Barang</th>
                        <th class="px-6 py-3 text-right">Harga</th>
                        <th class="px-6 py-3 text-center">Stok</th>
                        <th class="px-6 py-3 text-center w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100" id="itemsTableBody">
                    @include('kasir.partials.items_list', ['barang' => $barang])
                </tbody>
            </table>
        </div>
    </div>

    {{-- KERANJANG --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden flex flex-col h-[calc(100vh-8rem)] sticky top-24">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
            <h2 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                <span class="text-xl">🛒</span> Keranjang
            </h2>
        </div>

        <form id="formTransaksi"
              method="POST"
              action="{{ route('kasir.transaksi.store') }}"
              class="flex flex-col flex-1 overflow-hidden">
            @csrf

            <div class="flex-1 overflow-y-auto p-4 space-y-3" id="cart">
                {{-- Cart items will appear here --}}
                <div class="h-full flex flex-col items-center justify-center text-slate-400 space-y-2 opacity-60">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <p class="text-sm font-medium">Keranjang Kosong</p>
                </div>
            </div>

            <div class="p-4 bg-slate-50 border-t border-slate-100">
                
                <div id="errorMsg" class="hidden mb-3 text-xs text-red-600 bg-red-50 border border-red-200 px-3 py-2 rounded-lg flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                    <span class="msg-text">Error message</span>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-500 mb-2">Pilih Diskon / Promo</label>
                    <div id="discountContainer" class="space-y-2 max-h-32 overflow-y-auto border border-slate-200 rounded-lg p-2 bg-slate-50">
                        @forelse($discounts as $d)
                            <label class="flex items-center space-x-2 cursor-pointer p-1 hover:bg-slate-100 rounded">
                                <input type="checkbox" class="discount-checkbox rounded border-slate-300 text-blue-600 focus:ring-blue-500" 
                                       data-id="{{ $d->id }}" 
                                       data-type="{{ $d->type }}" 
                                       data-value="{{ $d->value }}"
                                       onchange="calculateDocDiscount()">
                                <span class="text-sm text-slate-700 flex-1">
                                    <span class="font-medium">{{ $d->name }}</span>
                                    <span class="text-xs text-slate-500 ml-1">
                                        ({{ $d->type == 'percent' ? $d->value . '%' : 'Rp ' . number_format($d->value) }})
                                    </span>
                                </span>
                            </label>
                        @empty
                            <div class="text-xs text-slate-400 text-center py-2">Tidak ada diskon tersedia</div>
                        @endforelse
                    </div>
                </div>

                <div class="flex justify-between items-center mb-1">
                    <span class="text-slate-500 font-medium">Subtotal</span>
                    <span id="subtotal" class="font-bold text-slate-700 font-mono">Rp 0</span>
                </div>
                
                <div class="flex justify-between items-center mb-1 text-green-600">
                    <span class="font-medium">Diskon</span>
                    <span id="discountDisplay" class="font-bold font-mono">- Rp 0</span>
                </div>

                <div class="flex justify-between items-center mb-4 pt-2 border-t border-slate-200">
                    <span class="text-slate-800 font-bold text-lg">Total Akhir</span>
                    <span id="grandTotal" class="font-bold text-2xl text-blue-600 font-mono">Rp 0</span>
                </div>

                <!-- Hidden inputs for form submission -->
                <input type="hidden" name="discount_ids" id="discountIds">
                <input type="hidden" name="diskon" id="inputDiskon">
                <input type="button" class="hidden" id="triggerCalc" onclick="calculateDocDiscount()">

                <input type="hidden" name="items" id="itemsInput">

                <button
                    type="button"
                    onclick="openPaymentModal()"
                    id="btnSubmit"
                    class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold shadow-lg shadow-blue-600/20 hover:bg-blue-700 hover:shadow-blue-700/30 transition-all active:scale-95 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none"
                    disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Selesaikan Transaksi
                </button>
            </div>
        </form>
    </div>

</div>

{{-- PAYMENT MODAL --}}
<div class="no-select">
<div id="paymentModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center px-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md animate-modal-in overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
            <h3 class="font-bold text-lg text-slate-800">Pembayaran & Kembalian</h3>
            <button onclick="closePaymentModal()" class="text-slate-400 hover:text-slate-600 text-2xl">&times;</button>
        </div>
        
        <div class="p-6 space-y-6">
            <div>
                <label class="block text-sm font-medium text-slate-500 mb-1">Total Tagihan</label>
                <div class="text-3xl font-bold text-slate-800 font-mono" id="modalTotal">Rp 0</div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-500 mb-1">Total Tagihan</label>
                <div class="text-3xl font-bold text-slate-800 font-mono" id="modalTotal">Rp 0</div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Uang Diterima (Rp)</label>
                <input type="number" id="uangDiterima" 
                       class="w-full text-lg font-mono border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                       placeholder="0">
            </div>

            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 mt-6">
                <div class="flex justify-between items-center">
                    <span class="text-slate-600 font-medium">Kembalian</span>
                    <span id="kembalian" class="font-bold text-xl text-slate-400 font-mono">Rp 0</span>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex gap-3">
            <button onclick="closePaymentModal()" class="flex-1 px-4 py-2 text-slate-600 hover:bg-slate-200 rounded-lg font-medium transition">
                Batal
            </button>
            <button onclick="submitTransaction()" id="btnConfirmPay" disabled class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-green-700 shadow-lg shadow-green-600/20 disabled:opacity-50 disabled:cursor-not-allowed transition">
                Konfirmasi Bayar
            </button>
        </div>
    </div>
</div>
</div>
{{-- ================= JS KASIR (FINAL) ================= --}}
<script>
// JS Logic
let cart = {}
let totalAmount = 0

const cartDiv    = document.getElementById('cart')
const totalEl    = document.getElementById('total')
const itemsInput = document.getElementById('itemsInput')
const errorMsg   = document.getElementById('errorMsg')
const errorText  = errorMsg.querySelector('.msg-text')
const btnSubmit  = document.getElementById('btnSubmit')
const form       = document.getElementById('formTransaksi')
const itemsTable = document.getElementById('itemsTableBody')

// Payment Modal Elements
const paymentModal = document.getElementById('paymentModal')
const modalTotalEl = document.getElementById('modalTotal')
const modalTotalAkhirEl = document.getElementById('modalTotalAkhir')
const inputDiskonEl = document.getElementById('inputDiskon')
const uangDiterimaEl = document.getElementById('uangDiterima')
const kembalianEl   = document.getElementById('kembalian')
const btnConfirmPay = document.getElementById('btnConfirmPay')

// ... (Existing code)

// === PAYMENT MODAL LOGIC ===
function openPaymentModal() {
    if(totalAmount <= 0) return
    
    modalTotalEl.innerText = 'Rp ' + totalAmount.toLocaleString()
    modalTotalAkhirEl.innerText = 'Rp ' + totalAmount.toLocaleString()
    
    inputDiskonEl.value = 0
    uangDiterimaEl.value = ''
    resetKembalian()
    
    paymentModal.classList.remove('hidden')
    // Reset Checkboxes
    document.querySelectorAll('.discount-checkbox').forEach(cb => cb.checked = false)
    calculateTotalDiscount()
    
    setTimeout(() => uangDiterimaEl.focus(), 100)
}

function calculateTotalDiscount() {
    let totalDiscount = 0
    let selectedIds = []

    document.querySelectorAll('.discount-checkbox:checked').forEach(cb => {
        const type = cb.dataset.type
        const val = parseFloat(cb.dataset.value)
        const id = cb.dataset.id

        selectedIds.push(id)

        if (type === 'percent') {
            totalDiscount += Math.round((val / 100) * totalAmount)
        } else {
            totalDiscount += val
        }
    })

    // Cap at totalAmount
    if (totalDiscount > totalAmount) totalDiscount = totalAmount

    inputDiskonEl.value = totalDiscount
    document.getElementById('discountIds').value = selectedIds.join(',')
    
    // Recalculate Payment
    calculatePayment()
}

function calculatePayment() {
    let diskon = parseInt(inputDiskonEl.value) || 0
    let uang   = parseInt(uangDiterimaEl.value) || 0
    
    const grandTotal = totalAmount - diskon

    // Validasi Max Uang (Agar DB tidak crash/overflow INT)
    if (uang > 1000000000) {
        uang = 1000000000
        uangDiterimaEl.value = 1000000000
    }

    const kembalian = uang - grandTotal
    
    if(uang >= grandTotal) {
        kembalianEl.innerText = 'Rp ' + kembalian.toLocaleString()
        kembalianEl.classList.remove('text-red-500', 'text-slate-400')
        kembalianEl.classList.add('text-green-600')
        btnConfirmPay.disabled = false
    } else {
        // Jika kurang bayar
        const kurang = grandTotal - uang
        kembalianEl.innerText = '- Rp ' + kurang.toLocaleString()
        kembalianEl.classList.remove('text-green-600', 'text-slate-400')
        kembalianEl.classList.add('text-red-500')
        btnConfirmPay.disabled = true
    }

    if(uangDiterimaEl.value === '') resetKembalian()
}

// Event Listeners for Calculation
uangDiterimaEl.addEventListener('input', calculatePayment)


function resetKembalian() {
    kembalianEl.innerText = 'Rp 0'
    kembalianEl.classList.remove('text-green-600', 'text-red-500')
    kembalianEl.classList.add('text-slate-400')
    btnConfirmPay.disabled = true
}

// Enter shortcut di input uang
uangDiterimaEl.addEventListener('keydown', (e) => {
    if(e.key === 'Enter' && !btnConfirmPay.disabled) {
        submitTransaction()
    }
})
// Enter shortcut di input diskon -> pindah focus
inputDiskonEl.addEventListener('keydown', (e) => {
    if(e.key === 'Enter') {
        uangDiterimaEl.focus()
    }
})

// Submit Transaksi Final
function submitTransaction() {
    // Show Loading on Button
    btnConfirmPay.disabled = true
    btnConfirmPay.innerHTML = `
        <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Memproses...
    `
    
    // Append Bayar Field
    const bayarVal = document.getElementById('uangDiterima').value || 0
    let hiddenBayar = document.getElementById('inputBayarHidden')
    if(!hiddenBayar) {
        hiddenBayar = document.createElement('input')
        hiddenBayar.type = 'hidden'
        hiddenBayar.name = 'bayar'
        hiddenBayar.id = 'inputBayarHidden'
        form.appendChild(hiddenBayar)
    }
    hiddenBayar.value = bayarVal

    // Append Diskon Field
    const diskonVal = document.getElementById('inputDiskon').value || 0
    let hiddenDiskon = document.getElementById('inputDiskonHidden')
    if(!hiddenDiskon) {
        hiddenDiskon = document.createElement('input')
        hiddenDiskon.type = 'hidden'
        hiddenDiskon.name = 'diskon'
        hiddenDiskon.id = 'inputDiskonHidden'
        form.appendChild(hiddenDiskon)
    }
    hiddenDiskon.value = diskonVal

    // Append Discount IDs
    const discountIdsVal = document.getElementById('discountIds').value || ''
    let hiddenDiscountIds = document.getElementById('inputDiscountIdsHidden')
    if(!hiddenDiscountIds) {
        hiddenDiscountIds = document.createElement('input')
        hiddenDiscountIds.type = 'hidden'
        hiddenDiscountIds.name = 'discount_ids'
        hiddenDiscountIds.id = 'inputDiscountIdsHidden'
        form.appendChild(hiddenDiscountIds)
    }
    hiddenDiscountIds.value = discountIdsVal
    
    // Submit Form
    form.submit()
}

const searchInput   = document.getElementById('searchInput')
const categoryInput = document.getElementById('categoryInput')

function fetchItems(animate = true) {
    const search = searchInput.value
    const category = categoryInput.value
    
    // Show loading state ONLY if animate is true (manual refresh)
    if (animate) {
        itemsTable.style.opacity = '0.5'
    }

    fetch(`{{ route('kasir.items.search') }}?search=${search}&kategori=${category}`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => {
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            } else {
                return response.text().then(text => { throw new Error('Expected JSON, got text'); });
            }
        })
        .then(data => {
            // Update Table
            itemsTable.innerHTML = data.html
            
            // Update Counters
            if(document.getElementById('filtered-item-count')) {
                document.getElementById('filtered-item-count').textContent = data.filtered_count;
            }
            if(document.getElementById('sidebar-item-count')) {
                document.getElementById('sidebar-item-count').textContent = data.total_items;
            }
            if(document.getElementById('sidebar-category-count')) {
                document.getElementById('sidebar-category-count').textContent = data.total_categories;
            }
            
            if (animate) {
                itemsTable.style.opacity = '1'
            }
            if (animate) {
                itemsTable.style.opacity = '1'
            }
            // reattachAddItemListeners() - Removed due to Event Delegation
        })
        .catch(err => {
            console.error('Error fetching items:', err)
            if (animate) {
                itemsTable.style.opacity = '1'
            }
        })
}

// Expose to global for refresh button (Manual refresh uses animation)
window.fetchItems = fetchItems;

// Auto Refresh every 15 seconds (15000ms) - Silent (no animation)
setInterval(() => {
    fetchItems(false);
}, 15000);

// Debounce helper
function debounce(func, timeout = 300){
  let timer;
  return (...args) => {
    clearTimeout(timer);
    timer = setTimeout(() => { func.apply(this, args); }, timeout);
  };
}

const debouncedFetch = debounce(() => fetchItems(), 300)
// Gunakan debounce agak cepat untuk search, dan langsung untuk select

if(searchInput) {
    searchInput.addEventListener('input', () => {
        debouncedFetch()
    })
    // Prevent form submit on enter
    searchInput.addEventListener('keydown', (e) => {
        if(e.key === 'Enter') {
            e.preventDefault()
            
            const val = searchInput.value.trim()
            if(!val) {
                fetchItems()
                return
            }

            // Coba scan sebagai barcode
            fetch(`{{ route('kasir.scan') }}?barcode=${encodeURIComponent(val)}`)
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        // Barang ditemukan via barcode
                        const item = data.item
                        // Panggil addItemToCart (pastikan parameter sesuai: id, nama, harga, stok)
                        addItemToCart(item.id_barang, item.nama_barang, item.harga_jual, item.stok)
                        
                        // Reset search box dan kembalikan list ke semua barang
                        searchInput.value = ''
                        fetchItems()
                        
                        // Opsional: Bunyi beep atau feedback visual
                    } else {
                        // Tidak ditemukan sebagai barcode, lakukan pencarian biasa
                        fetchItems() 
                    }
                })
                .catch(err => {
                    console.error('Error scanning:', err)
                    fetchItems()
                })
        }
    })
}

if(categoryInput) {
    categoryInput.addEventListener('change', () => {
        fetchItems()
    })
}

function showError(message) {
    errorText.textContent = message
    errorMsg.classList.remove('hidden')
    errorMsg.classList.add('flex')
    setTimeout(() => {
        errorMsg.classList.add('hidden')
        errorMsg.classList.remove('flex')
    }, 3000)
}

// Event Delegation for Add Item Buttons
itemsTable.addEventListener('click', function(e) {
    const btn = e.target.closest('.add-item');
    if (!btn) return;

    const stok = parseInt(btn.dataset.stok)

    if (stok <= 0) {
        showError('Stok barang habis')
        return
    }

    const id    = btn.dataset.id
    const nama  = btn.dataset.nama
    const harga = parseInt(btn.dataset.harga)

    addItemToCart(id, nama, harga, stok)
});

// function reattachAddItemListeners() { ... } // Removed

function addItemToCart(id, nama, harga, stok) {
    if (!cart[id]) {
        cart[id] = { nama, harga, qty: 1, stok }
    } else {
        if (cart[id].qty >= stok) {
            showError('Stok barang tersedia hanya ' + stok)
            return
        }
        cart[id].qty++
    }
    renderCart()
}

// Initial Listener Attach
// Initial Listener Attach
// reattachAddItemListeners() - Removed due to Event Delegation



// Render keranjang
function renderCart() {
    cartDiv.innerHTML = ''
    totalAmount = 0
    const cartKeys = Object.keys(cart)

    if (cartKeys.length === 0) {
        cartDiv.innerHTML = `
            <div class="h-full flex flex-col items-center justify-center text-slate-400 space-y-2 opacity-60">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <p class="text-sm font-medium">Keranjang Kosong</p>
            </div>
        `
        btnSubmit.disabled = true
        totalEl.textContent = 'Rp 0'
        itemsInput.value = ''
        return
    }

    cartKeys.forEach((id) => {
        const item = cart[id]
        const subtotal = item.harga * item.qty
        totalAmount += subtotal

        cartDiv.innerHTML += `
        <div class="bg-white p-3 rounded-xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-2">
                <div class="font-bold text-slate-800 text-sm leading-tight pr-2">${item.nama}</div>
                <button type="button"
                        onclick="removeItem('${id}')"
                        class="text-red-400 hover:text-red-600 hover:bg-red-50 p-1 rounded-lg transition-colors -mr-1"
                        title="Hapus Item">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>

            <div class="flex justify-between items-end">
                <div>
                     <div class="text-[10px] text-slate-500 mb-0.5">Rp ${item.harga.toLocaleString()} / unit</div>
                     <div class="text-sm font-bold text-slate-700 font-mono">Rp ${subtotal.toLocaleString()}</div>
                </div>
                
                <div class="flex items-center border border-slate-200 rounded-lg overflow-hidden bg-slate-50 h-8">
                    <button type="button"
                            onclick="changeQty('${id}', -1)"
                            class="px-2 text-slate-600 hover:bg-slate-200 hover:text-slate-900 transition-colors text-sm border-r border-slate-200 font-medium h-full flex items-center">−</button>
                    
                    <input type="number" 
                           value="${item.qty}" 
                           onchange="updateQty('${id}', this.value)"
                           class="w-10 text-center text-xs font-semibold text-slate-700 border-none bg-white focus:ring-0 outline-none p-0 m-0 appearance-none h-full"
                           min="1" 
                           max="${item.stok}">

                    <button type="button"
                            onclick="changeQty('${id}', 1)"
                            class="px-2 text-slate-600 hover:bg-slate-200 hover:text-slate-900 transition-colors text-sm border-l border-slate-200 font-medium h-full flex items-center">+</button>
                </div>
            </div>
        </div>`
    })

    // totalEl.textContent = 'Rp ' + totalAmount.toLocaleString() // Removed, using new structure
    itemsInput.value = JSON.stringify(cart)
    btnSubmit.disabled = false
    
    // Update Subtotal on Doc
    if(document.getElementById('subtotal')) {
        document.getElementById('subtotal').textContent = 'Rp ' + totalAmount.toLocaleString()
    }

    // Trigger Discount Calculation
    calculateDocDiscount()
}

// Update jumlah via input
function updateQty(id, value) {
    if (!cart[id]) return

    let newQty = parseInt(value)

    // Jika 0, hapus item
    if (newQty === 0) {
        removeItem(id)
        return
    }
    
    // Validasi input
    if (isNaN(newQty) || newQty < 0) {
        showError('Jumlah tidak valid')
        renderCart() // Reset UI ke nilai sebelumnya
        return
    }

    // Validasi stok
    if (newQty > cart[id].stok) {
        showError('Stok barang hanya tersedia ' + cart[id].stok)
        newQty = cart[id].stok
    }

    cart[id].qty = newQty
    renderCart()
}

// Ubah jumlah
function changeQty(id, delta) {
    if (!cart[id]) return

    const newQty = cart[id].qty + delta

    if (newQty < 1) {
        removeItem(id)
        return
    }

    if (newQty > cart[id].stok) {
        showError('Stok barang hanya tersedia ' + cart[id].stok)
        return
    }

    cart[id].qty = newQty
    renderCart()
}

// Hapus item
function removeItem(id) {
    delete cart[id]
    renderCart()
}

function calculateDocDiscount() {
    let totalDiscount = 0
    let selectedIds = []

    document.querySelectorAll('.discount-checkbox:checked').forEach(cb => {
        const type = cb.dataset.type
        const val = parseFloat(cb.dataset.value)
        const id = cb.dataset.id

        selectedIds.push(id)

        if (type === 'percent') {
            totalDiscount += Math.round((val / 100) * totalAmount)
        } else {
            totalDiscount += val
        }
    })

    // Cap at totalAmount
    if (totalDiscount > totalAmount) totalDiscount = totalAmount

    // Update Display
    if(document.getElementById('discountDisplay')) {
        document.getElementById('discountDisplay').textContent = '- Rp ' + totalDiscount.toLocaleString()
    }
    
    const grandTotal = totalAmount - totalDiscount
    
    if(document.getElementById('grandTotal')) {
        document.getElementById('grandTotal').textContent = 'Rp ' + grandTotal.toLocaleString()
    }

    // Update Hidden Inputs
    if(document.getElementById('inputDiskon')) {
        document.getElementById('inputDiskon').value = totalDiscount
    }
    if(document.getElementById('discountIds')) {
        document.getElementById('discountIds').value = selectedIds.join(',')
    }
}

// ... (Existing code)

// === PAYMENT MODAL LOGIC ===
function openPaymentModal() {
    // Recalculate everything just in case
    calculateDocDiscount()
    
    // Get current grand total from hidden input or recalculate
    let diskon = parseInt(document.getElementById('inputDiskon').value) || 0
    let grandTotal = totalAmount - diskon
    
    if(grandTotal < 0) grandTotal = 0
    if(totalAmount <= 0) return
    
    modalTotalEl.innerText = 'Rp ' + grandTotal.toLocaleString()
    // modalTotalAkhirEl ... removed from modal
    
    uangDiterimaEl.value = ''
    resetKembalian()
    
    paymentModal.classList.remove('hidden')
    setTimeout(() => uangDiterimaEl.focus(), 100)
}

function closePaymentModal() {
    paymentModal.classList.add('hidden')
}

// Tutup modal dengan ESC
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closePaymentModal()
})

// Validasi sebelum submit
form.addEventListener('submit', e => {
    if (Object.keys(cart).length === 0) {
        e.preventDefault()
        showError('Keranjang masih kosong')
        return
    }

    // Loading State
    const originalBtnContent = btnSubmit.innerHTML;
    btnSubmit.disabled = true;
    btnSubmit.innerHTML = `
        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Memproses...
    `;
    
    // Optional: Revert button state if submission is stopped (e.g. by browser validation) or after a timeout
    // In a real form submission, the page reload will naturally handle this.
})
</script>
@endsection
{{-- PAYMENT MODAL --}}
<div class="no-select">
<div id="paymentModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center px-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md animate-modal-in overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
            <h3 class="font-bold text-lg text-slate-800">Pembayaran & Kembalian</h3>
            <button onclick="closePaymentModal()" class="text-slate-400 hover:text-slate-600 text-2xl">&times;</button>
        </div>
        
        <div class="p-6 space-y-6">
            <div>
                <label class="block text-sm font-medium text-slate-500 mb-1">Total Tagihan</label>
                <div class="text-3xl font-bold text-slate-800 font-mono" id="modalTotal">Rp 0</div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Uang Diterima (Rp)</label>
                <input type="number" id="uangDiterima" 
                       class="w-full text-lg font-mono border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                       placeholder="0">
            </div>

            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                <div class="flex justify-between items-center">
                    <span class="text-slate-600 font-medium">Kembalian</span>
                    <span id="kembalian" class="font-bold text-xl text-slate-400 font-mono">Rp 0</span>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex gap-3">
            <button onclick="closePaymentModal()" class="flex-1 px-4 py-2 text-slate-600 hover:bg-slate-200 rounded-lg font-medium transition">
                Batal
            </button>
            <button onclick="submitTransaction()" id="btnConfirmPay" disabled class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-green-700 shadow-lg shadow-green-600/20 disabled:opacity-50 disabled:cursor-not-allowed transition">
                Konfirmasi Bayar
            </button>
        </div>
    </div>
</div>
</div>

{{-- DETAIL MODAL --}}
<div id="detailModal" class="fixed inset-0 z-[60] hidden bg-slate-900/50 backdrop-blur-sm items-center justify-center p-4 no-select">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md flex flex-col overflow-hidden animate-modal-in">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
            <h3 class="font-bold text-slate-800 text-lg">Detail Transaksi</h3>
            <button onclick="closeDetailModal()" class="text-slate-400 hover:text-slate-600 text-2xl">&times;</button>
        </div>
        <div id="detailContent" class="p-6 overflow-y-auto bg-white max-h-[80vh]"></div>
    </div>
</div>
</div>

{{-- PRINT CONTAINER (Hidden on Screen, Visible on Print) --}}
<div id="receipt-container" class="hidden print:block">
    <div id="receipt-content"></div>
</div>
@if(session('transaction_id'))
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Delay slightly to ensure everything is loaded
        setTimeout(() => {
            // Pass true to trigger auto-print
            openDetailModal({{ session('transaction_id') }}, true);
        }, 500);
    });
</script>
@endif

<script>
    // --- DETAIL MODAL LOGIC (Adapted for Kasir) ---
    function openDetailModal(id, autoPrint = false) {
        const modal = document.getElementById('detailModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        const contentDiv = document.getElementById('detailContent');
        contentDiv.innerHTML = '<div class="text-center py-10 text-slate-500">Memuat detail...</div>';

        // Use the Kasir route
        fetch(`{{ url('kasir/transaksi') }}/${id}`)
            .then(res => res.json())
            .then(data => {
                let itemsHtml = '';
                let printItemsHtml = '';
                
                data.items.forEach(item => {
                    // Modal HTML
                    itemsHtml += `
                        <div class="flex justify-between items-center py-2 border-b border-slate-50 last:border-0 no-select">
                            <div>
                                <div class="font-bold text-slate-800">${item.nama_barang}</div>
                                <div class="text-xs text-slate-500">${item.qty} x Rp ${new Intl.NumberFormat('id-ID').format(item.harga)}</div>
                            </div>
                            <div class="font-mono text-slate-700 font-bold">
                                Rp ${new Intl.NumberFormat('id-ID').format(item.subtotal)}
                            </div>
                        </div>
                    `;

                    // Print HTML (Simple tabular structure for alignment)
                    printItemsHtml += `
                        <div class="item">
                            <div class="name">${item.nama_barang}</div>
                            <div class="qty-price">${item.qty} x ${new Intl.NumberFormat('id-ID').format(item.harga)}</div>
                            <div class="subtotal">${new Intl.NumberFormat('id-ID').format(item.subtotal)}</div>
                        </div>
                    `;
                });

                // --- Update Modal Content ---
                contentDiv.innerHTML = `
                    <div class="space-y-4 no-select">
                        <div class="bg-emerald-50 p-4 rounded-xl border border-emerald-100 text-center mb-4">
                            <div class="text-emerald-600 font-bold text-lg mb-1">Transaksi Berhasil!</div>
                            <div class="text-emerald-500 text-sm">Terima kasih telah berbelanja</div>
                        </div>

                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-slate-500">No. Transaksi</span>
                                <span class="font-bold text-slate-800">#TRX-${data.id.toString().padStart(5, '0')}</span>
                            </div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-slate-500">Kasir</span>
                                <span class="font-bold text-slate-800">${data.kasir}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500">Waktu</span>
                                <span class="font-bold text-slate-800">${new Date(data.tanggal).toLocaleString('id-ID')}</span>
                            </div>
                        </div>

                        <div>
                            <h4 class="font-bold text-slate-800 mb-2 border-b border-slate-200 pb-2">Item Belanja</h4>
                            <div class="max-h-60 overflow-y-auto pr-1">
                                ${itemsHtml}
                            </div>
                        </div>

                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-slate-600 font-medium">Total</span>
                                <span class="font-bold text-slate-800">Rp ${new Intl.NumberFormat('id-ID').format(data.total)}</span>
                            </div>
                            
                            ${data.diskon > 0 ? `
                            <div class="flex justify-between items-center mb-2 text-sm text-red-500">
                                <span>Diskon</span>
                                <span>- Rp ${new Intl.NumberFormat('id-ID').format(data.diskon)}</span>
                            </div>
                            <div class="flex justify-between items-center mb-2 pt-2 border-t border-dashed border-slate-300">
                                <span class="text-slate-800 font-bold">Total Akhir</span>
                                <span class="font-bold text-xl text-blue-600">Rp ${new Intl.NumberFormat('id-ID').format(data.total - data.diskon)}</span>
                            </div>
                            ` : ''}

                            <div class="flex justify-between items-center mb-1 text-sm mt-2">
                                <span class="text-slate-500">Bayar (Cash)</span>
                                <span class="font-mono text-slate-700">Rp ${new Intl.NumberFormat('id-ID').format(data.bayar)}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm border-t border-slate-200 pt-2 mt-2">
                                <span class="text-slate-600 font-medium">Kembali</span>
                                <span class="font-bold text-emerald-600 font-mono">Rp ${new Intl.NumberFormat('id-ID').format(data.kembali)}</span>
                            </div>
                        </div>

                        <div class="mt-4 flex gap-2">
                             <button onclick="closeDetailModal()" class="flex-1 bg-blue-600 text-white py-2 rounded-xl font-bold hover:bg-blue-700 transition-colors">
                                Transaksi Baru
                             </button>
                        </div>
                    </div>
                `;

                // --- Update Receipt Content (Hidden) ---
                const receiptDiv = document.getElementById('receipt-content');
                if(receiptDiv) {
                    receiptDiv.innerHTML = `
                        <div class="header">
                            <h2>Koperasi</h2>
                            <p>Koperasi Siswa SMK Senopati</p>
                            </div>
                        <div class="divider">--------------------------------</div>
                        <div class="info">
                            <div class="row"><span>Tgl:</span> <span>${new Date(data.tanggal).toLocaleString('id-ID')}</span></div>
                            <div class="row"><span>Kasir:</span> <span>${data.kasir}</span></div>
                            <div class="row"><span>No:</span> <span>#TRX-${data.id}</span></div>
                        </div>
                        <div class="divider">--------------------------------</div>
                        <div class="items">
                            ${printItemsHtml}
                        </div>
                        <div class="divider">--------------------------------</div>
                        <div class="totals">
                            <div class="row total"><span>Total:</span> <span>${new Intl.NumberFormat('id-ID').format(data.total)}</span></div>
                            
                            ${data.diskon > 0 ? `
                                <div class="row"><span>Diskon:</span> <span>- ${new Intl.NumberFormat('id-ID').format(data.diskon)}</span></div>
                                <div class="row total"><span>Total Akhir:</span> <span>${new Intl.NumberFormat('id-ID').format(data.total - data.diskon)}</span></div>
                            ` : ''}

                            <div class="row"><span>Bayar:</span> <span>${new Intl.NumberFormat('id-ID').format(data.bayar)}</span></div>
                            <div class="row"><span>Kembali:</span> <span>${new Intl.NumberFormat('id-ID').format(data.kembali)}</span></div>
                        </div>
                        <div class="divider">--------------------------------</div>
                        <div class="footer">
                            <p>Terima Kasih</p>
                            <p>Barang yang sudah dibeli</p>
                            <p>tidak dapat ditukar/dikembalikan</p>
                        </div>
                        <div class="divider">--------------------------------</div>
                        <div class="divider">--------------------------------</div>
                        <div class="divider">--------------------------------</div>
                    `;
                }

                // Auto Print Logic
                if(autoPrint) {
                    setTimeout(() => {
                        window.print();
                    }, 500);
                }

            })
            .catch(err => {
                console.error(err);
                contentDiv.innerHTML = '<div class="text-center py-10 text-red-500">Gagal memuat detail transaksi.</div>';
            });
    }

    function closeDetailModal() {
        const modal = document.getElementById('detailModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // --- AUTO REFRESH DISCOUNTS ---
    function fetchDiscounts() {
        // Capture currently checked discount IDs
        const currentChecked = Array.from(document.querySelectorAll('.discount-checkbox:checked')).map(cb => cb.dataset.id);

        fetch('{{ route("kasir.discounts") }}')
            .then(res => res.json())
            .then(data => {
                const container = document.getElementById('discountContainer');
                
                if (data.length === 0) {
                    container.innerHTML = '<div class="text-xs text-slate-400 text-center py-2">Tidak ada diskon tersedia</div>';
                } else {
                    let html = '';
                    data.forEach(d => {
                        // Check if this discount was previously selected
                        const isChecked = currentChecked.includes(d.id.toString()) ? 'checked' : '';
                        
                        // Formatting
                        const typeDisplay = d.type === 'percent' ? d.value + '%' : 'Rp ' + new Intl.NumberFormat('id-ID').format(d.value);
                        
                        html += `
                            <label class="flex items-center space-x-2 cursor-pointer p-1 hover:bg-slate-100 rounded">
                                <input type="checkbox" class="discount-checkbox rounded border-slate-300 text-blue-600 focus:ring-blue-500" 
                                       data-id="${d.id}" 
                                       data-type="${d.type}" 
                                       data-value="${d.value}"
                                       onchange="calculateDocDiscount()"
                                       ${isChecked}>
                                <span class="text-sm text-slate-700 flex-1">
                                    <span class="font-medium">${d.name}</span>
                                    <span class="text-xs text-slate-500 ml-1">
                                        (${typeDisplay})
                                    </span>
                                </span>
                            </label>
                        `;
                    });
                    container.innerHTML = html;
                }
                
                // Recalculate to ensure totals are correct (e.g. if a selected discount expired and is now gone)
                calculateDocDiscount();
            })
            .catch(err => console.error('Failed to fetch discounts:', err));
    }

    // Refresh every 15 seconds
    setInterval(fetchDiscounts, 15000);
</script>

<style>
/* Hide Spin Button on Input Number */
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
  -webkit-appearance: none; 
  margin: 0; 
}
input[type=number] {
  -moz-appearance: textfield;
}

@keyframes modalIn {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}
.animate-modal-in {
    animation: modalIn 0.2s ease-out forwards;
}

/* === THERMAL PRINTER STYLES (58mm) === */
@media print {
    @page {
        size: 58mm auto; /* Target standard 58mm width */
        margin: 0;
    }
    
    body * {
        visibility: hidden;
        height: 0;
        overflow: hidden;
    }
    
    .no-select {
        display: none !important;
    }

    #receipt-container, #receipt-container * {
        visibility: visible;
        height: auto;
        overflow: visible;
    }

    #receipt-container {
        position: absolute;
        left: 0;
        top: 0;
        width: 48mm; /* Effective printable width for 58mm paper is usually around 48mm */
        padding: 2mm 0; /* Minimal padding */
        font-family: 'Courier New', Courier, monospace;
        font-size: 10px;
        line-height: 1.2;
        color: black;
        background: white;
    }

    /* Print Layout Styling */
    #receipt-content .header {
        text-align: center;
        margin-bottom: 5px;
    }
    #receipt-content .header h2 {
        font-size: 12px;
        font-weight: bold;
        text-transform: uppercase;
        margin: 0 0 2px 0;
    }
    #receipt-content .header p {
        margin: 0;
        font-size: 9px;
    }
    
    #receipt-content .divider {
        text-align: center;
        overflow: hidden;
        white-space: nowrap;
        margin: 2px 0;
    }

    #receipt-content .info {
        margin-bottom: 5px;
    }
    #receipt-content .row {
        display: flex;
        justify-content: space-between;
    }
    
    #receipt-content .items {
        margin-bottom: 5px;
    }
    #receipt-content .item {
        margin-bottom: 3px;
    }
    #receipt-content .item .name {
        font-weight: bold;
    }
    #receipt-content .item .qty-price {
        font-size: 9px;
    }
    #receipt-content .item .subtotal {
        text-align: right;
    }

    #receipt-content .totals {
        margin-top: 5px;
    }
    #receipt-content .totals .row.total {
        font-weight: bold;
        font-size: 11px;
    }
    
    #receipt-content .footer {
        text-align: center;
        margin-top: 10px;
        font-size: 9px;
    }
    #receipt-content .footer p {
        margin: 0;
    }
}
</style>