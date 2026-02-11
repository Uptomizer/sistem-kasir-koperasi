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
        Schema::create('stok_opname', function (Blueprint $table) {
            $table->id('id_stok_opname');
            $table->string('kode_opname')->unique();
            $table->date('tanggal');
            $table->foreignId('id_user')->constrained('users', 'id_user');
            $table->enum('status', ['pending', 'selesai', 'batal'])->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        Schema::create('detail_stok_opname', function (Blueprint $table) {
            $table->id('id_detail_stok_opname');
            $table->foreignId('id_stok_opname')->constrained('stok_opname', 'id_stok_opname')->onDelete('cascade');
            $table->foreignId('id_barang')->constrained('barang', 'id_barang');
            $table->integer('stok_sistem');
            $table->integer('stok_fisik');
            $table->integer('selisih');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_stok_opname');
        Schema::dropIfExists('stok_opname');
    }
};
