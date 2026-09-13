<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            // Cek apakah kolom status belum ada sebelum menambahkannya
            if (!Schema::hasColumn('submissions', 'status')) {
                $table->enum('status', ['pending', 'approved', 'revision'])->default('pending')->after('file_path');
            }
            
            // Cek apakah kolom notes belum ada
            if (!Schema::hasColumn('submissions', 'notes')) {
                $table->text('notes')->nullable()->after('status');
            }

            // Cek apakah kolom is_late belum ada
            if (!Schema::hasColumn('submissions', 'is_late')) {
                $table->boolean('is_late')->default(false)->after('notes');
            }
        });

        // Buat tabel untuk riwayat revisi (jika belum ada)
        if (!Schema::hasTable('submission_histories')) {
            Schema::create('submission_histories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('submission_id')->constrained()->onDelete('cascade');
                $table->string('file_path');
                $table->string('file_name')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('submission_histories');
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn(array_filter(['status', 'notes', 'is_late'], function($column) {
                return Schema::hasColumn('submissions', $column);
            }));
        });
    }
};