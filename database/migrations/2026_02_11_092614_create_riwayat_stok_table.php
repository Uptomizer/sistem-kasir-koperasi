<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('riwayat_stok', function (Blueprint $table) {
            $table->id('id_riwayat');
            $table->foreignId('id_barang')->constrained('barang', 'id_barang')->onDelete('cascade');
            $table->foreignId('id_user')->nullable()->constrained('users', 'id_user')->nullOnDelete();
            
            // Tipe mutasi: Masuk (Pembelian/Retur), Keluar (Penjualan/Rusak), Penyesuaian (Opname)
            $table->enum('jenis', ['masuk', 'keluar', 'penyesuaian']);
            
            $table->integer('jumlah');      // Jumlah perubahan (absolute value)
            $table->integer('stok_awal');   // Snapshot sebelum perubahan
            $table->integer('stok_akhir');  // Snapshot setelah perubahan
            
            $table->string('referensi')->nullable(); // Cth: ID Transaksi, Kode Opname
            $table->string('keterangan')->nullable(); // Catatan tambahan
            
            $table->timestamps();
            
            // Indexing for faster history lookup
            $table->index(['id_barang', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_stok');
    }
};
