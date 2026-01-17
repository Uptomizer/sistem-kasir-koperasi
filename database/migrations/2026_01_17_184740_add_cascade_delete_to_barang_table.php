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
        Schema::table('barang', function (Blueprint $table) {
            // Drop existing foreign key (Laravel standard naming or specific if known)
            // Assuming laravel generated 'barang_id_kategori_foreign'
            $table->dropForeign(['id_kategori']);

            // Re-add with cascade
            $table->foreign('id_kategori')
                  ->references('id_kategori')->on('kategori')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->dropForeign(['id_kategori']);

            // Revert to default (restrict)
            $table->foreign('id_kategori')
                  ->references('id_kategori')->on('kategori');
        });
    }
};
