<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->foreignId('gelombang_id')->nullable()->constrained('gelombang_pendaftaran')->nullOnDelete();
            $table->index('status');
        });
    }
    public function down(): void {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->dropConstrainedForeignId('gelombang_id');
            $table->dropIndex(['status']);
        });
    }
};
