@extends('layouts.admin')

@section('title', 'Tambah Kategori')
@section('page-title', 'Tambah Kategori')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-md">

    <form method="POST" action="{{ route('admin.kategori.store') }}">
        @csrf

        <div class="mb-4">
            <label class="text-sm">Nama Kategori</label>
            <input
                type="text"
                name="nama_kategori"
                required
                class="w-full border px-3 py-2 rounded mt-1"
            >
        </div>

        <button class="bg-blue-700 text-white px-4 py-2 rounded">
            Simpan
        </button>
    </form>

</div>
@endsection
