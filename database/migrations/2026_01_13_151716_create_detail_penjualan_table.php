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
        Schema::create('detail_penjualan', function (Blueprint $table) {
    $table->id('id_detail');
    $table->foreignId('id_penjualan')->constrained('penjualan','id_penjualan');
    $table->foreignId('id_barang')->constrained('barang','id_barang');
    $table->integer('jumlah');
    $table->integer('harga');
    $table->integer('subtotal');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_penjualan');
    }
};
