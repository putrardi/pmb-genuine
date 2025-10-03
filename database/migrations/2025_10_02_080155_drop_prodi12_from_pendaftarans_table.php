<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends \Illuminate\Database\Migrations\Migration {
    public function up(): void {
        Schema::table('pendaftarans', function (Blueprint $table) {
            if (Schema::hasColumn('pendaftarans','prodi2_id')) $table->dropConstrainedForeignId('prodi2_id');
            if (Schema::hasColumn('pendaftarans','prodi1_id')) $table->dropConstrainedForeignId('prodi1_id');
        });
    }
    public function down(): void {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->foreignId('prodi1_id')->nullable()->constrained('program_studi')->nullOnDelete();
            $table->foreignId('prodi2_id')->nullable()->constrained('program_studi')->nullOnDelete();
        });
    }
};
