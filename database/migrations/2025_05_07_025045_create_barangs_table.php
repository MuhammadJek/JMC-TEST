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
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('informasi_barang_id');
            $table->string('name');
            $table->integer('harga');
            $table->integer('jumlah_barang');
            $table->string('satuan');
            $table->integer('total_barang');
            $table->date('expired')->nullable();
            $table->timestamps();
            $table->foreign('informasi_barang_id')->references('id')->on('informasi_barangs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
