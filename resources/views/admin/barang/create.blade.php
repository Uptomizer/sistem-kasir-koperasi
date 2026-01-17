@extends('layouts.admin')

@section('title', 'Tambah Barang')
@section('page-title', 'Tambah Barang')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-lg">

<form method="POST" action="{{ route('admin.barang.store') }}">
@csrf

<div class="space-y-4">
    <input name="nama_barang" placeholder="Nama Barang"
           class="w-full border px-3 py-2 rounded">

    <select name="id_kategori" class="w-full border px-3 py-2 rounded">
        @foreach ($kategori as $k)
            <option value="{{ $k->id_kategori }}">{{ $k->nama_kategori }}</option>
        @endforeach
    </select>

    <input name="harga_beli" type="number" placeholder="Harga Beli"
           class="w-full border px-3 py-2 rounded">

    <input name="harga_jual" type="number" placeholder="Harga Jual"
           class="w-full border px-3 py-2 rounded">

    <input name="stok" type="number" placeholder="Stok Awal"
           class="w-full border px-3 py-2 rounded">

    <button class="bg-blue-700 text-white px-4 py-2 rounded">
        Simpan
    </button>
</div>

</form>
</div>
@endsection
