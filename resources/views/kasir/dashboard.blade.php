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
                
                <form action="{{ route('kasir.dashboard') }}" method="GET" class="flex items-center gap-2">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari barang..." 
                               class="bg-white border border-slate-300 text-slate-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-9 pr-4 py-1.5 shadow-sm outline-none">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <select name="kategori" 
                            class="bg-white border border-slate-300 text-slate-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-3 pr-8 py-1.5 shadow-sm cursor-pointer outline-none max-w-[150px]">
                        <option value="">Semua Kategori</option>
                        @foreach($kategori as $k)
                            <option value="{{ $k->id_kategori }}" {{ request('kategori') == $k->id_kategori ? 'selected' : '' }}>
                                {{ $k->nama_kategori }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="hidden">Cari</button>
                </form>

                <button
                    onclick="window.fetchItems ? window.fetchItems(true) : location.reload()"
                    class="bg-white border border-slate-300 text-slate-600 px-3 py-1.5 rounded-lg font-medium text-sm
                           hover:bg-slate-50 hover:text-blue-600 transition-colors flex items-center gap-2 whitespace-nowrap group"
                    title="Refresh Stok">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:rotate-180 duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </button>
            </div>

            <div class="text-sm text-slate-500 whitespace-nowrap">
                Total: <span class="font-semibold text-slate-700">{{ $barang->count() }}</span>
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

                <div class="flex justify-between items-center mb-4">
                    <span class="text-slate-500 font-medium">Total Harga</span>
                    <span id="total" class="font-bold text-2xl text-slate-800 font-mono">Rp 0</span>
                </div>

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
const uangDiterimaEl = document.getElementById('uangDiterima')
const kembalianEl   = document.getElementById('kembalian')
const btnConfirmPay = document.getElementById('btnConfirmPay')

// AJAX Search & Filter
// AJAX Search & Filter
const searchInput   = document.querySelector('input[name="search"]')
const categoryInput = document.querySelector('select[name="kategori"]')

function fetchItems(animate = true) {
    const search = searchInput.value
    const category = categoryInput.value
    
    // Show loading state ONLY if animate is true (manual refresh)
    if (animate) {
        itemsTable.style.opacity = '0.5'
    }

    fetch(`{{ route('kasir.items.search') }}?search=${search}&kategori=${category}`)
        .then(response => response.text())
        .then(html => {
            // Only update if content changed or just force update
            // For simplicity, we just update. Silent update won't flicker because we don't change opacity
            itemsTable.innerHTML = html
            
            if (animate) {
                itemsTable.style.opacity = '1'
            }
            reattachAddItemListeners()
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

// Auto Refresh every 45 seconds (45000ms) - Silent (no animation)
setInterval(() => {
    fetchItems(false);
}, 45000);

// Debounce helper
function debounce(func, timeout = 300){
  let timer;
  return (...args) => {
    clearTimeout(timer);
    timer = setTimeout(() => { func.apply(this, args); }, timeout);
  };
}

const debouncedFetch = debounce(() => fetchItems(), 100)
// Gunakan debounce agak cepat untuk search, dan langsung untuk select

if(searchInput) {
    searchInput.addEventListener('input', () => {
        debouncedFetch()
    })
    // Prevent form submit on enter
    searchInput.addEventListener('keydown', (e) => {
        if(e.key === 'Enter') {
            e.preventDefault()
            fetchItems() // fetch immediately on enter
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

function reattachAddItemListeners() {
    document.querySelectorAll('.add-item').forEach(btn => {
        // Clone button to remove old listeners (simple way to avoid duplicates if using addEventListener)
        // or just ensure we don't double bind. 
        // Better: use delegation on the table body or just re-bind safely.
        // Since we replace innerHTML, the old buttons are gone. We just bind new ones.
        
        btn.addEventListener('click', () => {
            const stok = parseInt(btn.dataset.stok)

            if (stok <= 0) {
                showError('Stok barang habis')
                return
            }

            const id    = btn.dataset.id
            const nama  = btn.dataset.nama
            const harga = parseInt(btn.dataset.harga)

            addItemToCart(id, nama, harga, stok)
        })
    })
}

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
reattachAddItemListeners()



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
        <div class="flex justify-between items-center bg-white p-3 rounded-xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
            <div>
                <div class="font-bold text-slate-800 text-sm">${item.nama}</div>
                <div class="text-xs text-slate-500 mt-1">Rp ${item.harga.toLocaleString()} x ${item.qty}</div>
            </div>

            <div class="flex items-center gap-3">
                <div class="text-sm font-bold text-slate-700 font-mono">Rp ${subtotal.toLocaleString()}</div>
                
                <div class="flex items-center border border-slate-200 rounded-lg overflow-hidden bg-slate-50">
                    <button type="button"
                            onclick="changeQty('${id}', -1)"
                            class="px-2 py-1 text-slate-600 hover:bg-slate-200 hover:text-slate-900 transition-colors text-sm border-r border-slate-200 font-medium">−</button>
                    
                    <input type="number" 
                           value="${item.qty}" 
                           onchange="updateQty('${id}', this.value)"
                           class="w-14 text-center text-xs font-semibold text-slate-700 border-none bg-white focus:ring-0 outline-none p-1 m-0 appearance-none"
                           min="1" 
                           max="${item.stok}">

                    <button type="button"
                            onclick="changeQty('${id}', 1)"
                            class="px-2 py-1 text-slate-600 hover:bg-slate-200 hover:text-slate-900 transition-colors text-sm border-l border-slate-200 font-medium">+</button>
                </div>

                <button type="button"
                        onclick="removeItem('${id}')"
                        class="text-red-400 hover:text-red-600 hover:bg-red-50 p-1.5 rounded-lg transition-colors"
                        title="Hapus Item">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
        </div>`
    })

    totalEl.textContent = 'Rp ' + totalAmount.toLocaleString()
    itemsInput.value = JSON.stringify(cart)
    btnSubmit.disabled = false
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

// === PAYMENT MODAL LOGIC ===
function openPaymentModal() {
    if(totalAmount <= 0) return
    
    modalTotalEl.innerText = 'Rp ' + totalAmount.toLocaleString()
    uangDiterimaEl.value = ''
    resetKembalian()
    
    paymentModal.classList.remove('hidden')
    setTimeout(() => uangDiterimaEl.focus(), 100)
}

function closePaymentModal() {
    paymentModal.classList.add('hidden')
}

// Hitung Kembalian
uangDiterimaEl.addEventListener('input', (e) => {
    const uang = parseInt(e.target.value) || 0
    const kembalian = uang - totalAmount
    
    if(uang >= totalAmount) {
        kembalianEl.innerText = 'Rp ' + kembalian.toLocaleString()
        kembalianEl.classList.remove('text-red-500', 'text-slate-400')
        kembalianEl.classList.add('text-green-600')
        btnConfirmPay.disabled = false
    } else {
        // Jika kurang bayar
        const kurang = totalAmount - uang
        kembalianEl.innerText = '- Rp ' + kurang.toLocaleString()
        kembalianEl.classList.remove('text-green-600', 'text-slate-400')
        kembalianEl.classList.add('text-red-500')
        btnConfirmPay.disabled = true
    }
    
    if(e.target.value === '') resetKembalian()
})

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

    form.submit()
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
</div>