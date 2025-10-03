<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('pendaftarans', function (Blueprint $table) {
            if (!Schema::hasColumn('pendaftarans','dokumen')) {
                $table->json('dokumen')->nullable();
            }
        });
    }
    public function down(): void {
        Schema::table('pendaftarans', function (Blueprint $table) {
            if (Schema::hasColumn('pendaftarans','dokumen')) {
                $table->dropColumn('dokumen');
            }
        });
    }
};
