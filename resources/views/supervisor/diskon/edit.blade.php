@extends('layouts.supervisor')

@section('title', 'Edit Diskon')
@section('page-title', 'Beri Potongan Harga')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm border border-slate-100 p-8">
    <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4">
        <h3 class="font-bold text-slate-800 text-lg">Edit Diskon</h3>
        <a href="{{ route('supervisor.diskon.index') }}" class="text-sm text-slate-500 hover:text-blue-600 font-medium">
            &larr; Kembali
        </a>
    </div>

    <form action="{{ route('supervisor.diskon.update', $diskon->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        {{-- Nama Promo --}}
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Nama Promo / Diskon</label>
            <input type="text" name="name" value="{{ old('name', $diskon->name) }}" class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors" required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Tipe Diskon --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Tipe Potongan</label>
                <select name="type" class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-slate-600 bg-white" required>
                    <option value="percent" {{ $diskon->type == 'percent' ? 'selected' : '' }}>Persentase (%)</option>
                    <option value="fixed" {{ $diskon->type == 'fixed' ? 'selected' : '' }}>Nominal (Rp)</option>
                </select>
            </div>

            {{-- Nilai --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Nilai Potongan</label>
                <div class="relative">
                    <input type="number" name="value" value="{{ old('value', $diskon->value) }}" class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors" min="0" required>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Tanggal Mulai --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ old('start_date', $diskon->start_date ? \Carbon\Carbon::parse($diskon->start_date)->format('Y-m-d') : '') }}" class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-slate-600">
            </div>

            {{-- Tanggal Selesai --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Tanggal Selesai</label>
                <input type="date" name="end_date" value="{{ old('end_date', $diskon->end_date ? \Carbon\Carbon::parse($diskon->end_date)->format('Y-m-d') : '') }}" class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-slate-600">
            </div>
        </div>

        {{-- Status --}}
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Status Promo</label>
            <div class="flex items-center gap-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="status" value="active" class="hidden peer" {{ $diskon->status == 'active' ? 'checked' : '' }}>
                    <div class="w-5 h-5 rounded-full border border-slate-300 peer-checked:border-emerald-500 peer-checked:bg-emerald-500 flex items-center justify-center">
                        <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                    </div>
                    <span class="text-slate-600 font-medium">Aktif</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="status" value="inactive" class="hidden peer" {{ $diskon->status == 'inactive' ? 'checked' : '' }}>
                    <div class="w-5 h-5 rounded-full border border-slate-300 peer-checked:border-red-500 peer-checked:bg-red-500 flex items-center justify-center">
                        <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                    </div>
                    <span class="text-slate-600 font-medium">Tidak Aktif</span>
                </label>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg font-bold shadow-lg shadow-blue-600/30 hover:bg-blue-700 hover:shadow-blue-700/40 transition-all active:scale-95">
                Simpan Perubahan
            </button>
        </div>

    </form>
</div>
@endsection
