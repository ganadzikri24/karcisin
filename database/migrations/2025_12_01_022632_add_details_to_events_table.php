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
    Schema::table('events', function (Blueprint $table) {
        // Kategori Event (Konser, Seminar, dll)
        $table->string('category')->default('General')->after('description');
        
        // Status: pending (Menunggu), approved (Tayang), rejected (Ditolak)
        $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('quota');
    });
}

public function down(): void
{
    Schema::table('events', function (Blueprint $table) {
        $table->dropColumn(['category', 'status']);
    });
}
};
