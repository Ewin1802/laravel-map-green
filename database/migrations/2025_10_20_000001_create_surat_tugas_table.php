<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Satu surat tugas (SPPD) bisa dipakai oleh beberapa pelaksana.
     * Tanggal, nomor, dan file surat tugas cukup diinput sekali di sini,
     * sedangkan biaya tiap pelaksana tetap tercatat di pemakaian_danas.
     */
    public function up(): void
    {
        Schema::create('surat_tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dana_id')->constrained()->onDelete('cascade');
            $table->string('nomor_surat')->nullable();
            $table->string('uraian');               // contoh: SPPD LUAR DAERAH - MANADO
            $table->string('no_bukti')->nullable(); // SP2D/SPM/SPP
            $table->date('tgl_bukti');
            $table->string('file_surat')->nullable(); // path PDF surat tugas
            $table->string('user_name')->nullable();
            $table->string('user_unit')->nullable();
            $table->year('thn_anggaran')->nullable();
            $table->timestamps();
        });

        Schema::table('pemakaian_danas', function (Blueprint $table) {
            $table->foreignId('surat_tugas_id')->nullable()->after('dana_id')
                  ->constrained('surat_tugas')->nullOnDelete();
            $table->string('nama_pelaksana')->nullable()->after('uraian');
        });
    }

    public function down(): void
    {
        Schema::table('pemakaian_danas', function (Blueprint $table) {
            $table->dropForeign(['surat_tugas_id']);
            $table->dropColumn(['surat_tugas_id', 'nama_pelaksana']);
        });

        Schema::dropIfExists('surat_tugas');
    }
};
