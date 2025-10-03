<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('gelombang_pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 120);
            $table->date('mulai');
            $table->date('selesai');
            $table->unsignedInteger('biaya')->default(0);
            $table->boolean('aktif')->default(false)->index();
            $table->timestamps();

            $table->index(['mulai','selesai']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('gelombang_pendaftaran');
    }
};
