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
        Schema::create('danas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->onDelete('cascade');
            $table->foreignId('kegiatan_id')->constrained()->onDelete('cascade');
            $table->foreignId('subkegiatan_id')->constrained()->onDelete('cascade');
            $table->foreignId('organisasi_id')->constrained()->onDelete('cascade');
            $table->foreignId('sumberdana_id')->constrained()->onDelete('cascade');
            $table->foreignId('rekbelanja_id')->constrained()->onDelete('cascade');
            $table->bigInteger('jumlah_dana')->default(0);
            $table->unsignedBigInteger('dana_asal_id')->nullable(); // Tambahan untuk pergeseran
            $table->foreign('dana_asal_id')->references('id')->on('danas')->onDelete('set null');
            $table->string('user_name')->nullable(); // Nama user yang input
            $table->string('user_unit')->nullable(); // Unit user
            $table->year('thn_anggaran')->nullable(); // Tahun anggaran
            $table->string('ket')->nullable();
            $table->boolean('available')->default(1);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('danas');
    }
};
