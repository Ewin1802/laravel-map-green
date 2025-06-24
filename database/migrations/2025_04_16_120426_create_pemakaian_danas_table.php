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
        Schema::create('pemakaian_danas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dana_id')->constrained()->onDelete('cascade');
            $table->string('uraian');
            $table->string('no_bukti');
            $table->bigInteger('jumlah_pemakaian')->default(0);
            $table->string('file_bukti')->nullable(); // path file PDF
            $table->date('tgl_bukti');
            $table->text('desk_belanja')->nullable();
            $table->string('jenis_belanja')->nullable();
            $table->string('user_name')->nullable(); // Nama user yang input
            $table->string('user_unit')->nullable(); // Unit user
            $table->year('thn_anggaran')->nullable(); // Tahun anggaran
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemakaian_danas');
    }
};
