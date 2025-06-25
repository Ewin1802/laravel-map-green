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
        Schema::create('rekbelanjas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_rek');
            $table->text('desk_belanja')->nullable();
            $table->text('jenis_belanja')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekbelanjas');
    }
};
