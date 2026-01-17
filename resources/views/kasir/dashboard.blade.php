@extends('layouts.kasir')

@section('title', 'Kasir')
@section('page-title', 'Transaksi Kasir')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- DAFTAR BARANG --}}
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden flex flex-col h-[calc(100vh-8rem)]">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <h2 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                <span class="text-xl">📦</span> Daftar Barang
            </h2>
            <div class="text-sm text-slate-500">
                Total Barang: <span class="font-semibold text-slate-700">{{ $barang->count() }}</span>
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
                <tbody class="divide-y divide-slate-100">
                    @forelse ($barang as $b)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-6 py-3 font-medium text-slate-800">
                            {{ $b->nama_barang }}
                            <div class="text-xs text-slate-400 font-normal">{{ $b->kategori->nama_kategori ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-3 text-right font-medium text-slate-700 font-mono">
                            Rp {{ number_format($b->harga_jual) }}
                        </td>
                        <td class="px-6 py-3 text-center">
                            @if($b->stok <= 5 && $b->stok > 0)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                    {{ $b->stok }}
                                </span>
                            @elseif($b->stok == 0)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-500">
                                    0
                                </span>
                            @else
                                <span class="text-slate-600">{{ $b->stok }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-center">
                            @if ($b->stok > 0)
                                <button
                                    type="button"
                                    class="add-item bg-green-50 text-green-600 hover:bg-green-600 hover:text-white p-2 rounded-lg transition-all active:scale-95 shadow-sm hover:shadow-md border border-green-200 hover:border-green-600"
                                    title="Tambah ke Keranjang"
                                    data-id="{{ $b->id_barang }}"
                                    data-nama="{{ $b->nama_barang }}"
                                    data-harga="{{ $b->harga_jual }}"
                                    data-stok="{{ $b->stok }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </button>
                            @else
                                <button
                                    type="button"
                                    disabled
                                    class="bg-slate-100 text-slate-400 p-2 rounded-lg cursor-not-allowed border border-slate-200"
                                    title="Stok Habis">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                    </svg>
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-10 text-slate-500">
                            <span class="block mb-2 text-2xl opacity-40">🔍</span>
                            Tidak ada barang ditemukan
                        </td>
                    </tr>
                    @endforelse
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
                    type="submit"
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

{{-- ================= JS KASIR (FINAL) ================= --}}
<script>
// JS Logic
let cart = {}

const cartDiv    = document.getElementById('cart')
const totalEl    = document.getElementById('total')
const itemsInput = document.getElementById('itemsInput')
const errorMsg   = document.getElementById('errorMsg')
const errorText  = errorMsg.querySelector('.msg-text')
const btnSubmit  = document.getElementById('btnSubmit')
const form       = document.getElementById('formTransaksi')

function showError(message) {
    errorText.textContent = message
    errorMsg.classList.remove('hidden')
    errorMsg.classList.add('flex')
    setTimeout(() => {
        errorMsg.classList.add('hidden')
        errorMsg.classList.remove('flex')
    }, 3000)
}

// Tambah barang
document.querySelectorAll('.add-item').forEach(btn => {
    btn.addEventListener('click', () => {
        const stok = parseInt(btn.dataset.stok)

        if (stok <= 0) {
            showError('Stok barang habis')
            return
        }

        const id    = btn.dataset.id
        const nama  = btn.dataset.nama
        const harga = parseInt(btn.dataset.harga)

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
    })
})


// Render keranjang
function renderCart() {
    cartDiv.innerHTML = ''
    let total = 0
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
        total += subtotal

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
                    
                    <span class="px-2 py-1 text-xs font-semibold text-slate-700 min-w-[1.5rem] text-center bg-white">${item.qty}</span>

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

    totalEl.textContent = 'Rp ' + total.toLocaleString()
    itemsInput.value = JSON.stringify(cart)
    btnSubmit.disabled = false
}

// Ubah jumlah
function changeQty(id, delta) {
    if (!cart[id]) return

    const newQty = cart[id].qty + delta

    if (newQty < 1) {
        showError('Jumlah minimal 1')
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
