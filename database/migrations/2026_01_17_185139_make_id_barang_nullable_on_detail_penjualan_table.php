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
        Schema::table('detail_penjualan', function (Blueprint $table) {
            // Drop existing foreign key
            $table->dropForeign(['id_barang']);

            // Make column nullable
            $table->foreignId('id_barang')->nullable()->change();

            // Add new foreign key with set null
            $table->foreign('id_barang')
                  ->references('id_barang')->on('barang')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_penjualan', function (Blueprint $table) {
             // Drop the set null key
             $table->dropForeign(['id_barang']);

            // Revert column to not null (CAUTION: ensure no nulls exist or data loss)
            // But for rollback we try our best
            $table->foreignId('id_barang')->nullable(false)->change();

             // Add back default restrict foreign key
             $table->foreign('id_barang')
                   ->references('id_barang')->on('barang');
        });
    }
};
