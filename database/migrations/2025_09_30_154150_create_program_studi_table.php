<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('program_studi', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama', 150);
            $table->string('jenjang', 5); // S1, D3, D4
            $table->unsignedInteger('kuota')->default(0);
            $table->boolean('aktif')->default(true)->index();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('program_studi');
    }
};
