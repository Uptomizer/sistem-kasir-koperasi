@extends('layouts.admin')

@section('title', 'Update Stok')
@section('page-title', 'Update Stok')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-md">

<form method="POST"
      action="{{ route('admin.barang.updateStok', $barang) }}">
@csrf
@method('PUT')

<p class="mb-4 font-semibold">
    {{ $barang->nama_barang }}
</p>

<input type="number"
       name="stok"
       value="{{ $barang->stok }}"
       class="w-full border px-3 py-2 rounded mb-4">

<button class="bg-green-700 text-white px-4 py-2 rounded">
    Simpan Stok
</button>

</form>

</div>
@endsection
