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
            $table->foreignId('id_penjualan')->constrained('penjualan','id_penjualan')->onDelete('cascade');
            $table->foreignId('id_barang')->nullable()->constrained('barang','id_barang')->onDelete('set null');
            $table->integer('jumlah');
            $table->integer('harga');
            $table->integer('diskon')->default(0);
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
