<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Kolom tambahan (Nullable karena User biasa tidak wajib isi)
            $table->string('phone')->nullable()->after('email');
            $table->string('nik')->nullable()->after('phone');
            $table->text('address')->nullable()->after('nik');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'nik', 'address']);
        });
    }
};