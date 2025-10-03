<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('pendaftarans', function (Blueprint $table) {
            if (!Schema::hasColumn('pendaftarans','prodi_id')) {
                $table->foreignId('prodi_id')->nullable()->constrained('program_studi')->nullOnDelete();
            }
            if (!Schema::hasColumn('pendaftarans','verified_at')) {
                $table->timestamp('verified_at')->nullable()->index();
            }
            if (!Schema::hasColumn('pendaftarans','verified_by')) {
                $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('pendaftarans','verification_note')) {
                $table->text('verification_note')->nullable();
            }
        });
    }
    public function down(): void {
        Schema::table('pendaftarans', function (Blueprint $table) {
            if (Schema::hasColumn('pendaftarans','prodi_id'))          $table->dropConstrainedForeignId('prodi_id');
            if (Schema::hasColumn('pendaftarans','verified_by'))       $table->dropConstrainedForeignId('verified_by');
            if (Schema::hasColumn('pendaftarans','verified_at'))       $table->dropColumn('verified_at');
            if (Schema::hasColumn('pendaftarans','verification_note')) $table->dropColumn('verification_note');
        });
    }
};
